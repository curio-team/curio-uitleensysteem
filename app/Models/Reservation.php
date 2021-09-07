<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_number', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_code', 'code');
    }

    public function reservedBy()
    {
        return $this->belongsTo(Teacher::class, 'reserved_by', 'code');
    }

    static function filterReservations(string $searchQuery)
    {
        return self::all()->filter(function($reservation) use ($searchQuery) {

            // Splitted the checks in a semi-logical order so that i can return early in case we have a match.

            if($reservation->returned_date !== null){
                return false;
            }

            // If product name matches
            if(stripos($reservation->product->name, $searchQuery) !== false){
                return true;
            }

            // If product type matches
            if($reservation->product->type){
                if(stripos($reservation->product->type->name, $searchQuery) !== false){
                    return true;
                }
            }

            // If product barcode EXACTLY matches
            if($reservation->product->barcode == $searchQuery) {
                return true;
            }

            return false;
        });
    }
}
