<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ProductController extends Controller
{
    public function searchProducts(Request $request){
        // Log automatisch uit als we reserveringen gaan in-/uitchecken
        if(Auth::user()){
            Auth::logout();
        }

        return view('products.search');
    }

    public function reserveProduct(Request $request, $productId){
        // Zoek het product, of geef een 404 en kijk na of deze al een reservering heeft
        $product = Product::findOrFail($productId);
        $reservation = $product->currentReservation();

        // Als het product al gereserveerd is, stuur terug naar zoekvenster
        if($reservation){
            return redirect()->route('searchProducts')->with('warning', 'Dit product is reeds uitgeleend.');
        }

        // Stuur naar de reserveerpagina
        return view('products.reservation', [
            'product' => $product,
            'request' => $request,
        ]);

    }

    public function processReserveProduct(Request $request, $productId){
        // Zoek het product, of geef een 404
        $product = Product::findOrFail($productId);

        // Controlleer of de opgegeven data via het formulier valide is
        $request->validate([
            'studentNumber' => 'required|integer|min:100000',
            'returnBy' => 'required|date|after_or_equal:'.  Date("Y-m-d") .'',
            'note' => 'max:1000',
        ]);

        // Stel de nieuwe reservering op
        $reservation = new Reservation;
        $reservation->student_number = $request->input('studentNumber');
        $reservation->product_id = $productId;
        $reservation->issue_date = now();
        $reservation->return_by_date = $request->input('returnBy');
        $reservation->note = $request->input('note');

        // Probeer op te slaan, en geef een error als dat niet lukt
        try{
            $reservation->save();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('reserveProduct', [
                'productId' => $productId,
                'request' => $request,
            ])->with('error', 'Kon reservering niet opslaan! Probeer het nogmaals.');
        }

        // Opslaan gelukt, stuur terug naar de zoekpagina met een succesmelding
        return redirect()->route('searchProducts')->with(
            'success', $product->name .' is succesvol uitgeleend aan studentnummer '. $reservation->student_number
        );
    }

    public function findProducts(Request $request){
        // Ontvangt enkel AJAX requests
        if ($request->ajax()) {
            // Stel variabelen samen
            $listItems = [];
            $searchQuery = $request->search;

            // Kijk of er gezocht wordt naar producten. Zoniet, geef niets terug.
            if($searchQuery) {
                $products = Product::filterProducts($searchQuery);
            } else {
                return $listItems; // Zou leeg moeten zijn
            }

            // Als er producten gevonden zijn, loop door de producten heen en genereer de blokjes waar ze in komen te staan
            if($products){
                foreach($products as $product){
                    if ($searchQuery == $product->barcode) {
                        // Als de barcode precies klopt, laat gelijk doorgaan naar reservering of retour pagina
                        $listItems['redirect'] = $product->id;
                        if($product->currentReservation()){
                            $listItems['reservation'] = true;
                        } else {
                            $listItems['reservation'] = false;
                        }
                        return json_encode($listItems);
                    } else {
                        $listItems[] = view('components.product-card')->with('product', $product)->render();
                    }
                }
            }

            // Pak de gegenereerde blokjes, encodeer deze als JSON, en stuur terug naar de view
            return json_encode($listItems);
        }
        // Als het geen AJAX request is, laat een 404 zien.
        return abort(404);
    }

    public function manageProducts() {
        // Haal alle producten op
        $products = Product::all();

        // Stuur naar de producten manage pagina
        return view('products.manage', [
            'products' => $products,
        ]);
    }

    public function findManageProducts(Request $request){
        // Ontvangt enkel AJAX requests
        if ($request->ajax()) {
            // Stel variabelen samen
            $listItems = [];
            $searchQuery = $request->search;

            // Kijk of er gezocht wordt naar producten. Zoniet, geef ALLES terug.
            if($searchQuery) {
                $products = Product::filterProducts($searchQuery);
            } else {
                $products = Product::all();
            }

            // Als er producten gevonden zijn, loop door de producten heen en genereer de lijst waar ze in komen te staan
            if($products){
                foreach($products as $product){
                    if ($searchQuery == $product->barcode) {
                        // Als de barcode precies klopt, laat gelijk doorgaan naar de productpagina
                        $listItems['redirect'] = $product->id;
                        return json_encode($listItems);
                    }
                    $listItems[] = view('components.product-list')->with('product', $product)->render();
                }
            }

            // Pak de gegenereerde lijst, encodeer deze als JSON, en stuur terug naar de view
            return json_encode($listItems);
        }
        // Als het geen AJAX request is, laat een 404 zien.
        return abort(404);
    }

    public function manageProduct($productId){
        // Zoek het product, of geef een 404
        $product = Product::findOrFail($productId);
        $reservations = Reservation::where('product_id', $productId)->orderByDesc('issue_date')->get();

        // Stuur naar de product manage pagina
        return view('products.show', [
            'product' => $product,
            'reservations' => $reservations,
        ]);
    }

    public function createProduct(){
        // Haal alle product types op.
        $types = ProductType::all();

        // Stuur naar de product add pagina
        return view('products.create', [
            'productTypes' => $types,
        ]);
    }

    public function processCreateProduct(Request $request){
        // Controlleer of de opgegeven data via het formulier valide is
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'integer|min:6|nullable',
            'type' => 'integer|nullable|exists:product_types,id', // Checkt ook of de meegegeven type bestaat in de database
            'description' => 'string|nullable',
            'price' => 'numeric|nullable',
        ]);

        // Pas de nieuwe waardes op dit product toe
        $product = new Product;
        $product->name = $request->input('name');
        $product->barcode = $request->input('barcode');
        $product->type_id = $request->input('type');
        $product->description = $request->input('description');
        $product->price = $request->input('price');

        // Voeg image toe als er een nieuwe image verstuurd is.
        if($request->hasFile('image')){
            if($request->validate(['image' => 'image|mimes:jpg,png,jpeg|max:10000',])) {
                // Create directory if it does not already exist
                Storage::makeDirectory('public/images');
                $src = Storage::putFile('public/images', $request->file('image'));
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->setTimeout(10)->optimize(Storage::path($src));
                $product->image = str_replace('public', 'storage', $src);
            }
        }

        // Probeer op te slaan, en geef een error als dat niet lukt
        try{
            $product->save();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('createProduct', [
                'request' => $request,
            ])->with('error', 'Kon product niet opslaan! Probeer het nogmaals.');
        }

        // Opslaan gelukt, stuur terug naar de manage pagina van dit product met een succesmelding
        return redirect()->route('manageProducts', [
        ])->with('success', 'Product succesvol opgeslagen!');
    }

    public function editProduct($productId){
        // Zoek het product, of geef een 404. En haal alle product types op.
        $product = Product::findOrFail($productId);
        $types = ProductType::all();

        // Stuur naar de product edit pagina
        return view('products.edit', [
            'product' => $product,
            'productTypes' => $types,
        ]);
    }

    public function processEditProduct(Request $request, $productId){
        // Controlleer of de opgegeven data via het formulier valide is
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'integer|min:6|nullable',
            'type' => 'integer|nullable',
            'description' => 'string|nullable',
            'price' => 'numeric|nullable',
        ]);

        // Zoek het product, of geef een 404.
        $product = Product::findOrFail($productId);

        // Pas de nieuwe waardes op dit product toe
        $product->name = $request->input('name');
        $product->barcode = $request->input('barcode');
        $product->type_id = $request->input('type');
        $product->description = $request->input('description');
        $product->price = $request->input('price');

        // Zet oldImage variabele zodat we af kunnen vangen of de foto veranderd is
        $oldImage = null;

        // Pas de image aan als er een nieuwe image verstuurd is.
        if($request->hasFile('image')){
            if($request->validate(['image' => 'image|mimes:jpg,png,jpeg|max:10000',])) {
                // Pak de path naar de oude image, zodat we deze later kunnen verwijderen.
                $oldImage = $product->image;
                // Create directory if it does not already exist
                Storage::makeDirectory('public/images');
                $src = Storage::putFile('public/images', $request->file('image'));
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->setTimeout(10)->optimize(Storage::path($src));
                $product->image = str_replace('public', 'storage', $src);
            }
        }

        // Probeer op te slaan, en geef een error als dat niet lukt
        try{
            $product->save();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('editProduct', [
                'productId' => $productId,
                'request' => $request,
            ])->with('error', 'Kon product niet opslaan! Probeer het nogmaals.');
        }

        // Opslaan gelukt, verwijder waar nodig de oude image, en stuur terug naar de manage pagina van dit product met een succesmelding
        if($oldImage){
            $oldImage = str_replace('storage', 'public', $oldImage);
            unlink(storage_path('app/'.$oldImage));
        }

        return redirect()->route('manageProduct', [
            'productId' => $productId,
        ])->with('success', 'Product succesvol opgeslagen!');
    }

    public function processDeleteProduct($productId) {
        $product = Product::findOrFail($productId);
        $productName = $product->name;

        // Probeer te verwijderen, en geef een error als dat niet lukt
        try{
            $product->delete();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('manageProduct', [
                'productId' => $productId,
            ])->with('error', 'Kon product niet verwijderen! Probeer het nogmaals.');
        }

        return redirect()->route('manageProducts')->with('success', 'Product "'. $productName .'" succesvol verwijderd!');

    }

    public function returnProduct(Request $request, $productId){
        $product = Product::findOrFail($productId);
        $reservation = $product->currentReservation();

        if($reservation){
            if(Carbon::parse($reservation->return_by_date)->isPast() && !Carbon::parse($reservation->return_by_date)->isToday()){
                $request->session()->flash('warning', 'Product wordt te laat ingeleverd!');
            }

            return view('products.return', [
                'product' => $product,
            ]);
        } else {
            return redirect()->route('searchProducts')->with('error', 'Product heeft geen reservering!');
        }


    }

    public function processReturnProduct($productId){
        // Zoek het product, of geef een 404. En haal de reservering van het product op
        $product = Product::findOrFail($productId);
        $reservation = $product->currentReservation();

        // Check of de reservering bestaat
        if($reservation){
            // Zet de retourdatum op vandaag
            $reservation->returned_date = now();

            // Probeer op te slaan, en geef een error als dat niet lukt
            try{
                $reservation->save();
            } catch (\Throwable $e) {
                Log::error($e);
                return redirect()->route('returnProduct', [
                    'productId' => $productId,
                ])->with('error', 'Kon reservering niet opslaan! Probeer het nogmaals.');
            }

            return redirect()->route('searchProducts')->with('success', 'Product succesvol ingeleverd!');
        } else {
            return redirect()->route('searchProducts')->with('error', 'Product heeft geen reservering!');
        }


    }
}
