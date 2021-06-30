@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Producttype Toevoegen</h1>
        </div>

        <div class="row">
            <div class="col-6">
                <form action="{{ route('processCreateProductType') }}" id="addProductTypeForm" name="addProductTypeForm" method="post">
                    @csrf
                    <div class="row mb-2">
                        <span for="name" class="col-sm-4">Naam:</span>
                        <input class="col-sm-8 px-1" name="name" type="text">
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 d-flex">
                            <input type="submit" value="Toevoegen" class="btn btn-primary mr-1">
                            <a href="{{ route('manageProductTypes') }}" class="btn btn-danger">Annuleren</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col">
            </div>
        </div>
    </div>
@endsection
