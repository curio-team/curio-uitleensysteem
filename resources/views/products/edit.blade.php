@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Product Aanpassen</h1>
        </div>

        <div class="row">
            <div class="col-6">
                <form action="{{ route('processEditProduct', $product->id) }}" id="editProductForm" name="editProductForm" method="post" enctype="multipart/form-data" onSubmit="return false">
                    @csrf
                    <div class="row mb-2">
                        <span for="barcode" class="col-sm-4">Barcode:</span>
                        <input class="col-sm-8 px-1" name="barcode" type="text" value="{{ $product->barcode }}">
                    </div>
                    <div class="row mb-2">
                        <span for="name" class="col-sm-4">Naam:</span>
                        <input class="col-sm-8 px-1" name="name" type="text" value="{{ $product->name }}">
                    </div>
                    <div class="row mb-2">
                        <span for="type" class="col-sm-4">Type:</span>
                        <select class="col-sm-8 px-1" name="type">
                            <option value=""></option>
                            @foreach($productTypes as $productType)
                                <option {{ ($product->type_id == $productType->id) ? 'selected' : '' }} value="{{ $productType->id }}">{{ $productType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-2">
                        <span for="description" class="col-sm-4">Omschrijving:</span>
                        <textarea class="col-sm-8 px-1" name="description" rows="4">{{ $product->description }}</textarea>
                    </div>
                    <div class="row mb-2">
                        <span for="price" class="col-sm-4">Prijs:</span>
                        <input class="col-sm-8 px-1" name="price" type="number" value="{{ $product->price }}">
                    </div>
                    <div class="row mb-2">
                        <span for="image" class="col-sm-4">Foto:</span>
                        <input class="col-sm-8 px-0" type="file" name="image" id="image">
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 d-flex">
                            <input type="button" value="Aanpassen" class="btn btn-primary mr-1" onClick="document.editProductForm.submit()">
                            <a href="{{ route('manageProduct', $product->id) }}" class="btn btn-danger">Annuleren</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-6">
                @if(null !== $product->image)
                    <img class="img-fluid" id="imagePreview" src="{{ asset($product->image) }}">
                @else
                    <img class="img-fluid" id="imagePreview" src="{{ asset('img/default.jpg') }}">
                @endif
            </div>
        </div>
    </div>

    <script type="application/javascript">
        image.onchange = evt => {
            const [file] = image.files
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
            }
        }
    </script>


@endsection
