<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use Illuminate\Support\Facades\Log;

class ProductTypeController extends Controller
{
    public function createProductType(){
        // Stuur naar de product type add pagina
        return view('types.create');
    }

    public function processCreateProductType(Request $request){
        // Controlleer of de opgegeven data via het formulier valide is
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Pas de nieuwe waardes op dit producttype toe
        $productType = new ProductType;
        $productType->name = $request->input('name');


        // Probeer op te slaan, en geef een error als dat niet lukt
        try{
            $productType->save();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('createProductType', [
                'request' => $request,
            ])->with('error', 'Kon producttype niet opslaan! Probeer het nogmaals.');
        }

        // Opslaan gelukt, stuur terug naar de manage pagina van dit product met een succesmelding
        return redirect()->route('manageProductTypes', [
        ])->with('success', 'Producttype succesvol opgeslagen!');
    }

    public function editProductType($productTypeId){
        $productType = ProductType::findOrFail($productTypeId);

        return view('types.edit', [
            'type' => $productType,
        ]);
    }

    public function processEditProductType(Request $request, $productTypeId){
        $productType = ProductType::findOrFail($productTypeId);

        $productType->name = $request->input('name');

        // Probeer op te slaan, en geef een error als dat niet lukt
        try{
            $productType->save();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('editProductType', [
                'type' => $productType,
                'request' => $request,
            ])->with('error', 'Kon producttype niet opslaan! Probeer het nogmaals.');
        }

        return redirect()->route('manageProductTypes')->with('success', 'Producttype "'. $productType->name .'" aangepast!');

    }

    public function processDeleteProductType($productTypeId){
        $productType = ProductType::findOrFail($productTypeId);
        $productTypeName = $productType->name;

        try{
            $productType->delete();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('manageProductTypes')->with('error', 'Kon producttype niet verwijderen! Probeer het nogmaals.');
        }

        return redirect()->route('manageProductTypes')->with('success', 'Producttype "'. $productTypeName .'" succesvol verwijderd!');

    }

    public function manageProductTypes() {
        // Haal alle producten op
        $productTypes = ProductType::all();

        // Stuur naar de producten manage pagina
        return view('types.manage', [
            'types' => $productTypes,
        ]);
    }

    public function findManageProductTypes(Request $request){
        // Ontvangt enkel AJAX requests
        if ($request->ajax()) {
            // Stel variabelen samen
            $listItems = [];
            $searchQuery = $request->search;

            // Kijk of er gezocht wordt naar types. Zoniet, geef ALLES terug.
            if($searchQuery) {
                $productTypes = ProductType::filterProductTypes($searchQuery);
            } else {
                $productTypes = ProductType::all();
            }

            // Als er types gevonden zijn, loop door de types heen en genereer de lijst waar ze in komen te staan
            if($productTypes){
                foreach($productTypes as $productType){
                    $listItems[] = view('components.product-type-list')->with('type', $productType)->render();
                }
            }

            // Pak de gegenereerde lijst, encodeer deze als JSON, en stuur terug naar de view
            return json_encode($listItems);
        }
        // Als het geen AJAX request is, laat een 404 zien.
        return abort(404);
    }
}
