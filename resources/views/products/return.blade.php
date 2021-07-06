@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Product Retourneren</h1>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 row">
                <div class="col-6 col-lg-12">
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
                </div>
                <div class="col-6 col-lg-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Gereserveerd Door:</p>
                        </div>
                        <div class="col-sm-8">
                            <p><a href="{{ route('showStudent', $product->currentReservation()->student->id) }}">{{ $product->currentReservation()->student->name }}</a></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Gereserveerd Vanaf:</p>
                        </div>
                        <div class="col-sm-8">
                            <p>{{ $product->currentReservation()->issue_date }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Gereserveerd Tot:</p>
                        </div>
                        <div class="col-sm-8">
                            <p>{{ $product->currentReservation()->return_by_date }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Notities:</p>
                        </div>
                        <textarea class="col-sm-8 px-1" rows="4" readonly>{{ $product->currentReservation()->note }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="font-weight-bold">Het product is in goede staat en compleet:</p>
                        </div>
                        <div class="col-sm-6 form-check">
                            <input id="productCheck" style="width: 30px; height: 30px;" class="form-check-input" name="productCheck" type="checkbox">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 d-flex">
                            <a id="submitButton" href="{{ route('processReturnProduct', $product->id) }}" class="btn btn-primary mr-1 disabled">Retourneren</a>
                            <a href="{{ route('searchProducts') }}" class="btn btn-danger">Annuleren</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                @if(null !== $product->image)
                    <img class="img-fluid" src="{{ asset($product->image) }}">
                @else
                    <img class="img-fluid" src="{{ asset('img/default.jpg') }}">
                @endif
            </div>
        </div>
    </div>

    <script type="text/javascript">

        document.getElementById('productCheck').addEventListener("click", checkProductCheck);

        function checkProductCheck() {
            if(document.getElementById('productCheck').checked === true) {
                document.getElementById('submitButton').classList.remove('disabled');
            } else {
                document.getElementById('submitButton').classList.add('disabled');
            }
        }

    </script>

@endsection
