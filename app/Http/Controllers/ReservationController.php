<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function manageReservation($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        return view('reservations.show', [
            'reservation' => $reservation,
        ]);
    }

    public function updateReservation(Request $request, $reservationId)
    {
        // Controlleer of de opgegeven data via het formulier valide is
        $request->validate([
            'note' => 'max:1000',
        ]);

        $reservation = Reservation::findOrFail($reservationId);

        $reservation->note = $request->input('note');

        // Probeer op te slaan, en geef een error als dat niet lukt
        try {
            $reservation->save();
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->route('manageReservation', [
                'reservationId' => $reservationId,
                'request' => $request,
            ])->with('error', 'Kon reservering niet opslaan! Probeer het nogmaals.');
        }

        return redirect()->route('manageReservation', $reservationId)->with('success', 'Reservering geüpdate.');
    }

    public function listReservations()
    {
        $reservations = Reservation::where('returned_date', null)->with('product')->get();
        $lateReservations = array();

        foreach ($reservations as $reservation) {
            if (Carbon::parse($reservation->return_by_date)->isPast() && !Carbon::parse($reservation->return_by_date)->isToday()) {
                $lateReservations[] = $reservation;
            }
        }

        return view('reservations.list', [
            'reservations' => $reservations,
            'lateReservations' => $lateReservations,
        ]);
    }

    public function findReservations(Request $request)
    {
        // Ontvangt enkel AJAX requests
        if ($request->ajax()) {
            // Stel variabelen samen
            $listItems['reservations'] = [];
            $listItems['lateReservations'] = [];
            $searchQuery = $request->search;

            // Kijk of er gezocht wordt naar reserveringen. Zoniet, geef alle openstaande reserveringen terug.
            if ($searchQuery) {
                $reservations = Reservation::filterReservations($searchQuery);
            } else {
                $reservations = Reservation::where('returned_date', null);
            }

            // Als er reserveringen gevonden zijn, loop door de reserveringen heen en genereer de lijst waar ze in komen te staan
            if ($reservations) {
                foreach ($reservations as $reservation) {
                    if ($searchQuery == $reservation->product->barcode) {
                        // Als de barcode precies klopt, laat gelijk doorgaan naar de reservering
                        $listItems['redirect'] = $reservation->id;
                        return json_encode($listItems);
                    } elseif (Carbon::parse($reservation->return_by_date)->isPast() && !Carbon::parse($reservation->return_by_date)->isToday()) {
                        $listItems['lateReservations'][] = view('components.manage-reservations-list')->with('reservation', $reservation)->render();
                    }
                    $listItems['reservations'][] = view('components.manage-reservations-list')->with('reservation', $reservation)->render();
                }
            }

            // Pak de gegenereerde blokjes, encodeer deze als JSON, en stuur terug naar de view
            return json_encode($listItems);
        }
        // Als het geen AJAX request is, laat een 404 zien.
        return abort(404);
    }
}