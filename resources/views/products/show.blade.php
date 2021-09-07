@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Product Beheren</h1>
        </div>

        <div class="row mb-2">
            <div class="col-6">
                <div class="row">
                    <div class="col-sm-4">
                        <p>Barcode:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ $product->barcode }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Naam:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ $product->name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Type:</p>
                    </div>
                    <div class="col-sm-8">
                        @if($product->type)
                            <p>{{ $product->type->name }}</p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Omschrijving:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Prijs:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>&euro;{{ $product->price }}</p>
                    </div>
                </div>
                @if($product->currentReservation())
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Gereserveerd Door:</p>
                        </div>
                        <div class="col-sm-8">
                            @if($product->currentReservation()->student)
                                <p><a href="{{ route('showStudent', $product->currentReservation()->student->id) }}">{{ $product->currentReservation()->student->name }}</a></p>
                            @elseif($product->currentReservation()->teacher)
                                <p><a href="{{ route('showTeacher', $product->currentReservation()->teacher->id) }}">{{ $product->currentReservation()->teacher->name }}</a></p>
                            @else
                                <p>{{ $product->currentReservation()->student_number }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Gereserveerd Vanaf:</p>
                        </div>
                        <div class="col-sm-8">
                            <p>{{ \Carbon\Carbon::parse($product->currentReservation()->issue_date)->translatedFormat('l d F Y - h:i:s') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Gereserveerd Tot:</p>
                        </div>
                        <div class="col-sm-8">
                            @if($product->currentReservation()->return_by_date)
                                <p>{{ \Carbon\Carbon::parse($product->currentReservation()->return_by_date)->translatedFormat('l d F Y') }}</p>
                            @else
                                <p>Geen retourdatum bekend</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Uitgeleend door:</p>
                        </div>
                        <div class="col-sm-8">
                            @if($product->currentReservation()->reserved_by)
                                <td><a href="{{ route('showTeacher', $product->currentReservation()->reservedBy->id) }}">{{ $product->currentReservation()->reservedBy->name }}</a></td>
                            @else
                                <td>Onbekend</td>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-4 d-flex">
                        <a href="{{ route('editProduct', $product->id) }}" class="btn btn-primary mr-1">Aanpassen</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#archiveProductModal">
                            Archiveren
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-6">
                @if(null !== $product->image)
                    <img class="img-fluid" src="{{ asset($product->image) }}">
                @else
                    <img class="img-fluid" src="{{ asset('img/default.jpg') }}">
                @endif
            </div>
        </div>
        <div class="row">
            <h2>Reserveringen</h2>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Gereserveerd door</th>
                    <th scope="col">Gereserveerd op</th>
                    <th scope="col">Gereserveerd tot</th>
                    <th scope="col">Geretourneerd op</th>
                    <th scope="col">Uitgeleend door</th>
                    <th scope="col">Notites</th>
                </tr>
                </thead>
                <tbody id="productList">
                @foreach($reservations as $reservation)
                    @include('components.reservations-list', $reservation)
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center" id="paginationBar">
            {{ $reservations->onEachSide(5)->links('pagination::bootstrap-4') }}
        </div>
    </div>

    @include('components.archive-product-modal', $product)

@endsection
