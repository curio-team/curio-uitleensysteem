<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use StudioKaa\Amoclient\Facades\AmoAPI;
use ZipArchive;

class ImportController extends Controller
{
    public function listImports(){
        return view('import.list');
    }

    public function processProductImport(Request $request){
        $request->validate([
            'productImportCSV' => 'required|file|mimes:csv,txt',
        ]);

        $products = Product::all();
        $row = 0;
        $headers = [];
        $filepath = $request->file('productImportCSV');
        $productTypes = ProductType::pluck('name', 'id')->toArray();
        $newCount = 0;
        $errors = [];
        if (($handle = fopen($filepath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if (++$row == 1) {
                    $headers = array_flip($data); // Get the column names from the header.
                    $headerCheck = [
                        'name' => 0,
                        'barcode' => 1,
                        'type' => 2,
                        'description' => 3,
                        'price' => 4,
                    ];
                    if($headers !== $headerCheck){
                        return redirect()->route('import')->with('error', 'De headers van de geimporteerde CSV komen niet overeen met het sjabloon. Kijk de CSV na en probeer nog een keer.');
                    }
                    continue;
                } else {
                    if(!($productTypeId = array_search($data[$headers['type']], $productTypes))){
                        $productType = new ProductType;
                        $productType->name = $data[$headers['type']];
                        $productType->save();

                        $productTypeId = $productType->id;
                        $productTypes[$productType->id] = $productType->name;
                    }

                    $product = $products->where('barcode', $data[$headers['barcode']])->first();
                    if(!$product){
                        $newCount++;
                        $product = new Product;
                        $product->name = $data[$headers['name']];
                        $product->barcode = $data[$headers['barcode']];
                        $product->type_id = $productTypeId;
                        $product->description = $data[$headers['description']];
                        $product->price = doubleval($data[$headers['price']]);

                        try{
                            $product->save();
                        } catch (\Throwable $e) {
                            Log::error($e);
                            $errors[] = 'Mislukt om product met barcode "'. $data[$headers['barcode']] .'" op te slaan. Kijk de CSV na voor fouten.';
                        }

                    }
                }
            }
            fclose($handle);
        }

        if($errors){
            $message = "";
            foreach ($errors as $error){
                $message .= $error.'<br>';
            }
            $request->session()->flash('error', nl2br($message));
        }

        $request->session()->flash('success', $newCount .' nieuwe producten toegevoegd.');

        return redirect()->route('import');
    }

    public function processProductImportOverwrite(Request $request){
        if (!Auth::user()->super_admin) {
            $request->session()->flash('error', 'Gebruiker is geen super admin.');

            return redirect()->route('import');
        }

        $request->validate([
            'productImportOverwriteCSV' => 'required|file|mimes:csv,txt',
        ]);

        $products = Product::all();
        $row = 0;
        $headers = [];
        $filepath = $request->file('productImportOverwriteCSV');
        $productTypes = ProductType::pluck('name', 'id')->toArray();
        $newCount = 0;
        $updateCount = 0;
        $errors = [];
        if (($handle = fopen($filepath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if (++$row == 1) {
                    $headers = array_flip($data); // Get the column names from the header.
                    $headerCheck = [
                        'name' => 0,
                        'barcode' => 1,
                        'type' => 2,
                        'description' => 3,
                        'price' => 4,
                    ];
                    if($headers !== $headerCheck){
                        return redirect()->route('import')->with('error', 'De headers van de geimporteerde CSV komen niet overeen met het sjabloon. Kijk de CSV na en probeer nog een keer.');
                    }
                    continue;
                } else {
                    if(!($productTypeId = array_search($data[$headers['type']], $productTypes))){
                        $productType = new ProductType;
                        $productType->name = $data[$headers['type']];
                        $productType->save();

                        $productTypeId = $productType->id;
                        $productTypes[$productType->id] = $productType->name;
                    }

                    $product = $products->where('barcode', $data[$headers['barcode']])->first();
                    if(!$product){
                        $newCount++;
                        $product = new Product;
                    } else {
                        $updateCount++;
                    }

                    $product->name = $data[$headers['name']];
                    $product->barcode = $data[$headers['barcode']];
                    $product->type_id = $productTypeId;
                    $product->description = $data[$headers['description']];
                    $product->price = doubleval($data[$headers['price']]);

                    try{
                        $product->save();
                    } catch (\Throwable $e) {
                        Log::error($e);
                        $errors[] = 'Mislukt om product met barcode "'. $data[$headers['barcode']] .'" op te slaan. Kijk de CSV na voor fouten.';
                    }
                }
            }
            fclose($handle);
        }

        if($errors){
            $message = "";
            foreach ($errors as $error){
                $message .= $error.'<br>';
            }
            $request->session()->flash('error', nl2br($message));
        }

        $request->session()->flash('success', $row-1 .' producten verwerkt, waarvan '. $newCount .' nieuwe en '. $updateCount .' updates.');

        return redirect()->route('import');
    }

    public function processImageImport(Request $request){
        $request->validate([
            'imageImportZip' => 'required|file|mimes:zip',
        ]);

        // Maak folder als deze niet bestaat
        Storage::makeDirectory('tmp/zip');
        $zipPath = Storage::path('tmp/zip');
        $lockfilePath = $zipPath.'/.lock';
        $allowedMimeTypes = [
            "image/png",
            "image/jpeg",
        ];

        if(!file_exists($lockfilePath)){

            $lockfile = fopen($lockfilePath, 'w');
            fclose($lockfile);

            try{
                $zip = new ZipArchive;
                $zip->open($request->file('imageImportZip'));
                $zip->extractTo($zipPath);
                $zip->close();

                // Loop door alle images heen, en sla ze op onder de juiste producten.
                // Verwijder die image van de tmp folder zodra hij klaar is

                $uploadCount = 0;
                $successCount = 0;

                foreach (Storage::files('tmp/zip') as $filePath) {
                    if(in_array(Storage::mimeType($filePath), $allowedMimeTypes)){
                        $barcode = basename(preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath));
                        $product = Product::where('barcode', $barcode)->first();
                        if($product){
                            $file = Storage::path($filePath);
                            $src = Storage::putFile('public/images', $file);
                            $optimizerChain = OptimizerChainFactory::create();
                            $optimizerChain->setTimeout(10)->optimize(Storage::path($src));
                            $oldSrc = str_replace('storage', 'public', $product->image);
                            Storage::delete($oldSrc);
                            $product->image = str_replace('public', 'storage', $src);
                            $product->save();
                            $successCount++;
                        }
                        Storage::delete($filePath);
                        $uploadCount++;
                    }

                }

                unlink($lockfilePath);

                if($uploadCount === $successCount) {
                    $request->session()->flash('success', $successCount .' van de '. $uploadCount .' afbeeldingen geimporteerd!');
                } else {
                    $request->session()->flash('warning', $successCount .' van de '. $uploadCount .' afbeeldingen geimporteerd! Kijk na of alle barcodes kloppen.');
                }

                return redirect()->route('import');
            } catch (\Throwable $e) {
                Log::error($e);
                unlink($lockfilePath);

                $request->session()->flash('error', 'Import mislukt. Probeer het nogmaals.');

                return redirect()->route('import');
            }
        } else {
            $request->session()->flash('error', 'Iemand is al aan het importeren. Wacht een moment af en probeer het later nogmaals.');

            return redirect()->route('import');
        }
    }

    public function processStudentImport(Request $request){
        $users = json_decode(AmoAPI::get('users'), true);

        if($users){
            Student::truncate();
            Teacher::truncate();
            $studentIdList = [];
            $teacherIdList = [];


            foreach($users as $user){
                if ($user['type'] === "student") {
                    $id = preg_replace('/^([a-zA-z]*)/', "", $user['id']);

                    // Skip als studentnummer al bekend is
                    if(!in_array($id, $studentIdList)) {
                        $studentIdList[] = $id;

                        $student = new Student;
                        $student->id = $id;
                        $student->name = $user['name'];
                        $student->email = $user['email'];
                        $student->save();
                    }
                }

                if ($user['type'] === "teacher") {
                    $id = strtolower($user['id']);

                    // Skip als docentnummer al bekend is
                    if(!in_array($id, $teacherIdList)) {
                        $teacherIdList[] = $id;

                        $teacher = new Teacher;
                        $teacher->code = $id;
                        $teacher->name = $user['name'];
                        $teacher->email = $user['email'];
                        $teacher->save();
                    }
                }
            }

            $request->session()->flash('success', 'Studenten succesvol geïmporteerd!');

            return redirect()->route('import');
        }

        $request->session()->flash('error', 'Kon studenten niet importeren! API gaf geen studenten terug.');

        return redirect()->route('import');
    }
}
