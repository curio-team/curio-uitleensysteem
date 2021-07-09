@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Producten Archief</h1>
        </div>

        <div class="form-group row align-items-center">
            <label for="productCode" class="col-2 col-form-label">Barcode / Naam / Type:</label>
            <input autofocus type="text" class="form-control col-4" id="productSearch" oninput="filterProducts()">
            <div class="col"></div>
            <div class="col-4 col-xl-2 d-flex justify-content-end align-items-center text-center">
                <a href="{{ route('createProduct') }}" class="btn-lg btn-primary">Nieuw Product</a>
            </div>
        </div>

        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Barcode</th>
                    <th scope="col">Naam</th>
                    <th scope="col">Type</th>
                    <th scope="col">Gereserveerd op</th>
                    <th scope="col">Gereserveerd tot</th>
                    <th scope="col">Gereserveerd door</th>
                </tr>
                </thead>
                <tbody id="productList">
                @foreach($products as $product)
                    @include('components.archived-product-list', $product)
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center" id="paginationBar">
            {{ $products->onEachSide(5)->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script type="application/javascript">

        function filterProducts() {
            var search = '?search=' + document.getElementById('productSearch').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ route("findManageProducts") }}' + search);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var items = xhr.response;
                    if(items['redirect']){
                        window.location.replace("/admin/producten/" + items['redirect']);
                        return;
                    }
                    document.getElementById('productList').innerHTML = "";
                    document.getElementById('productList').insertAdjacentHTML('beforeend', items.join(' '));
                    document.getElementById('paginationBar').classList.add('d-none');
                } else {
                    console.log('Filtering products failed.  Returned status of ' + xhr.status);
                }
            };
            xhr.responseType = 'json';
            xhr.send();
        }
    </script>

@endsection
