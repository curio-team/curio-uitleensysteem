<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    static function filterProductTypes(string $searchQuery)
    {
        return self::all()->filter(function($productType) use ($searchQuery) {

            // Splitted the checks in a semi-logical order so that i can return early in case we have a match.

            // If name matches
            if(stripos($productType->name, $searchQuery) !== false){
                return true;
            }

            return false;
        });
    }
}
