@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Producten Uitlenen</h1>
        </div>
        <div class="form-group row align-items-center">
            <label for="productCode" class="col-2 col-form-label">Barcode:</label>
            <div class="col-5 d-flex align-items-center">
                <input autofocus type="text" class="form-control mr-2" id="productSearch" oninput="filterProducts()">
                <button class="btn btn-danger" onclick="document.getElementById('productSearch').value = ''">Clear</button>
            </div>
        </div>
        <p>Als de scanner niets registreert, druk nog even op het invoerveld om deze te selecteren, en probeer het nogmaals.</p>
    </div>

    <script type="application/javascript">

        function filterProducts() {
            var search = '?search=' + document.getElementById('productSearch').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ route("findProducts") }}' + search);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var items = xhr.response;
                    if(items['redirect']){
                        if(items['reservation']){
                            window.location.replace("/retour/" + items['redirect']);
                        } else {
                            window.location.replace("/reserveren/" + items['redirect']);
                        }
                        return;
                    }
                } else {
                    console.log('Filtering products failed.  Returned status of ' + xhr.status);
                }
            };
            xhr.responseType = 'json';
            xhr.send();
        }
    </script>

@endsection
