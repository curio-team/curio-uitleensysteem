@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Reservering Beheren</h1>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-sm-4">
                        <p>Product:</p>
                    </div>
                    <div class="col-sm-8">
                        <a href="{{ route('manageProduct', $reservation->product_id) }}">{{ $reservation->product->name }}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Gereserveerd door:</p>
                    </div>
                    <div class="col-sm-8">
                        @if($reservation->student)
                            <p><a href="{{ route('showStudent', $reservation->student->id) }}">{{ $reservation->student->name }}</a></p>
                        @elseif($reservation->teacher)
                            <p><a href="{{ route('showTeacher', $reservation->teacher->id) }}">{{ $reservation->teacher->name }}</a></p>
                        @else
                            <p>{{ $reservation->student_number }}</p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Gereserveerd op:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ \Carbon\Carbon::parse($reservation->issue_date)->translatedFormat('l d F Y - h:i:s') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Gereserveerd tot:</p>
                    </div>
                    <div class="col-sm-8">
                        @if($reservation->return_by_date)
                            <p>{{ \Carbon\Carbon::parse($reservation->return_by_date)->translatedFormat('l d F Y') }}</p>
                        @else
                            <p>Geen retourdatum bekend</p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Geretourneerd op:</p>
                    </div>
                    <div class="col-sm-8">
                        @if($reservation->returned_date)
                            <p>{{ \Carbon\Carbon::parse($reservation->returned_date)->translatedFormat('l d F Y - h:i:s') }}</p>
                        @endif
                    </div>
                </div>
                <form action="{{ route('updateReservation', $reservation->id) }}" id="editReservationNoteForm" name="editReservationNoteForm" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <p>Notitie:</p>
                        </div>
                        <textarea class="col-sm-8 px-1" rows="4" name="note">{{ $reservation->note }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 d-flex">
                            <input type="submit" class="btn btn-primary mr-1" value="Notitie bijwerken">
                            @if(!$reservation->returned_date)
                                <a href="{{ route('returnProduct', $reservation->product_id) }}" class="btn btn-danger mr-1">Product Retourneren</a>
                            @endif
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#extendReservationModal">
                                Reservering Verlengen
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-6">
            </div>
        </div>
    </div>

        @include('components.extend-reservation-modal', $reservation)

@endsection
