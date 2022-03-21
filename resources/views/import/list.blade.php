@extends('layouts.app')

@section('content')

    <div class="container">
        <table class="col-12 col-lg-6">
            <tr>
                <td>Producten</td>
                <td class="d-flex align-items-center">
                    <form id="productImportForm" method="post" enctype="multipart/form-data" action="{{ route('processProductImport') }}">
                        @csrf
                        <input type="file" id="productImport" name="productImportCSV" class="d-none" onchange="document.getElementById('productImportForm').submit()" />
                        <input type="button" class="btn btn-primary mr-2" value="Import Uploaden" onclick="document.getElementById('productImport').click();" />
                    </form>
                    <a download class="btn btn-primary mr-2" href="{{ asset('/downloads/Producten_Sjabloon.csv') }}">Sjabloon Downloaden</a>
                    <a download class="btn btn-primary" href="{{ route('exportProducts') }}">Producten Exporteren</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Let op: Producten met een bestaande barcode worden NIET overschreven. Als een meegegeven producttype nog niet bestaat, wordt deze automatisch aangemaakt.</p>
                </td>
            </tr>
            @if(Auth::user()->super_admin)
                <tr>
                    <td>Producten Overschrijven</td>
                    <td class="d-flex">
                        <form id="productImportForm" method="post" enctype="multipart/form-data" action="{{ route('processProductImportOverwrite') }}">
                            @csrf
                            <input type="file" id="productImportOverwrite" name="productImportOverwriteCSV" class="d-none" onchange="document.getElementById('productImportOverwriteForm').submit()" />
                            <input type="button" class="btn btn-primary mr-2" value="Import Uploaden" onclick="document.getElementById('productImportOverwrite').click();" />
                        </form>
                    </td>
                </tr>
            @endif
            <tr>
                <td>Afbeeldingen</td>
                <td class="d-flex">
                    <form id="imageImportForm" method="post" enctype="multipart/form-data" action="{{ route('processImageImport') }}">
                        @csrf
                        <input type="file" id="imageImport" name="imageImportZip" class="d-none" onchange="document.getElementById('imageImportForm').submit()" />
                        <input type="button" class="btn btn-primary mr-2" value="Zip Uploaden" onclick="document.getElementById('imageImport').click();" />
                    </form>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Hier kun je een zip uploaden met afbeeldingen voor de producten. Zorg ervoor dat de afbeeldingen de barcode van het product als naam hebben. Plaatjes mogen .png of .jpg/.jpeg zijn. Als een product al een afbeelding had, wordt deze overschreven.</p>
                </td>
            </tr>
            <tr>
                <td>Studenten</td>
                <td>
                    <a href="{{ route('processStudentImport') }}" class="btn btn-primary">Updaten met Curio API</a>
                </td>
            </tr>
        </table>
    </div>

@endsection
