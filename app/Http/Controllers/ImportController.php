<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use StudioKaa\Amoclient\Facades\AmoAPI;
class ImportController extends Controller
{
    public function listImports(){
        return view('import.list');
    }

    public function processProductImport(Request $request){

        $products = Product::all();
        $row = 0;
        $headers = [];
        $filepath = $request->file('productImportCSV');
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

    public function processStudentImport(Request $request){
        $users = json_decode(AmoAPI::get('users'), true);

        if($users){
            Student::truncate();
            $idList = [];

            foreach($users as $user){
                if ($user['type'] === "student") {
                    $id = preg_replace('/^([a-zA-z]*)/', "", $user['id']);

                    // Skip als studentnummer al bekend is
                    if(!in_array($id, $idList)) {
                        $idList[] = $id;

                        $student = new Student;
                        $student->id = $id;
                        $student->name = $user['name'];
                        $student->email = $user['email'];
                        $student->save();
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
