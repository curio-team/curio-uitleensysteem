@extends('layouts.app')

@section('content')

    <div class="container">
        @if(session('added'))
            @include('components.added-products-accordion', session('added'))
        @endif
        @if(session('changed'))
            @include('components.changed-products-accordion', session('changed'))
        @endif
        <table class="col-12 col-lg-6">
            <tr>
                <td>Producten</td>
                <td class="d-flex">
                    <form id="productImportForm" method="post" enctype="multipart/form-data" action="{{ route('processProductImport') }}">
                        @csrf
                        <input type="file" id="productImport" name="productImportCSV" class="d-none" onchange="document.getElementById('productImportForm').submit()" />
                        <input type="button" class="btn btn-primary mr-2" value="Import Uploaden" onclick="document.getElementById('productImport').click();" />
                    </form>
                    <a download class="btn btn-primary" href="{{ asset('/downloads/Producten_Sjabloon.csv') }}">Sjabloon Downloaden</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Let op: Producten met een bestaande barcode worden overschreven. Als een meegegeven producttype nog niet bestaat, wordt deze automatisch aangemaakt.</p>
                </td>
            </tr>
            <tr>
                <td>Studenten</td>
                <td>
                    <button class="btn btn-primary">Updaten met Curio API</button>
                </td>
            </tr>
        </table>
    </div>

@endsection
