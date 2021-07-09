<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }

    public function currentReservation()
    {
        $reservation =  Reservation::where('product_id', $this->id)->where('returned_date', null)->first();

        if($reservation) {
            return $reservation;
        } else {
            return null;
        }
    }

    static function filterProducts(string $searchQuery)
    {
        return self::all()->filter(function($product) use ($searchQuery) {

            // Splitted the checks in a semi-logical order so that i can return early in case we have a match.

            // If name matches
            if(stripos($product->name, $searchQuery) !== false){
                return true;
            }

            // If type matches
            if($product->type){
                if(stripos($product->type->name, $searchQuery) !== false){
                    return true;
                }
            }

            // If barcode EXACTLY matches
            if($product->barcode === $searchQuery) {
                return true;
            }

            return false;
        });
    }
}
