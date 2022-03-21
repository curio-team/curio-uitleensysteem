<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportProducts()
    {
        $products = Product::all();

        $productRows = [];

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=producten_'. Carbon::today()->format('d-m-Y') .'.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('name', 'barcode', 'type', 'description', 'price'), ';');

        // put data in rows
        foreach ($products as $product) {

            $productRows[] = [
                $product->name,
                $product->barcode,
                $product->type->name,
                $product->description,
                $product->price
            ];

        }

        // loop over the rows, outputting them
        foreach ($productRows as $productRow){
            fputcsv($output, $productRow, ';');
        }
    }

    public function exportLateReservations() {
        $lateReservations = Reservation::where('returned_date', null)
            ->where('return_by_date', '<', Carbon::today())
            ->with('product')
            ->get();

        $lateReservationsRows = [];

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=te_laat_reserveringen_'. Carbon::today()->format('d-m-Y') .'.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('reservering_id', 'product_naam', 'gereserveerd_door', 'email', 'gereserveerd_op', 'gereserveerd_tot', 'uitgegeven_door', 'email_docent'), ';');

        // put data in rows
        foreach ($lateReservations as $lateReservation) {
            if($lateReservation->student_number){
                if($lateReservation->student) {
                    $reservedBy = $lateReservation->student->name;
                    $email = $lateReservation->student->email;
                } else {
                    $reservedBy = $lateReservation->student_number;
                    $email = 'onbekend';
                }
            } elseif ($lateReservation->teacher_code){
                if($lateReservation->teacher) {
                    $reservedBy = $lateReservation->teacher->name;
                    $email = $lateReservation->teacher->email;
                } else {
                    $reservedBy = $lateReservation->teacher_code;
                    $email = 'onbekend';
                }
            } else {
                $reservedBy = 'onbekend';
                $email = 'onbekend';
            }

            $lateReservationsRows[] = [
                $lateReservation->id,
                $lateReservation->product->name,
                $reservedBy,
                $email,
                Carbon::parse($lateReservation->issue_date)->translatedFormat('l d F Y - h:i:s'),
                Carbon::parse($lateReservation->return_by_date)->translatedFormat('l d F Y'),
                $lateReservation->reservedBy->name,
                $lateReservation->reservedBy->email
            ];
        }

        // loop over the rows, outputting them
        foreach ($lateReservationsRows as $lateReservationsRow){
            fputcsv($output, $lateReservationsRow, ';');
        }
    }
}
