@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Producttypes Beheren</h1>
        </div>

        <div class="form-group row align-items-center">
            <label for="typeSearch" class="col-2 col-form-label">Naam:</label>
            <input type="text" class="form-control col-4" id="typeSearch" oninput="filterTypes()">
            <div class="col"></div>
            <div class="col-4 col-xl-2 d-flex justify-content-end align-items-center text-center">
                <a href="{{ route('createProductType') }}" class="btn-lg btn-primary">Nieuw Producttype</a>
            </div>
        </div>

        <div class="row">
            <table class="table col-8">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Naam</th>
                    <th scope="col">Toegevoegd op</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody id="typeList">
                @foreach($types as $type)
                    @include('components.product-type-list', $type)
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @foreach($types as $type)
        @include('components.delete-type-modal', $type)
    @endforeach

    <script type="application/javascript">

        function filterTypes() {
            var search = '?search=' + document.getElementById('typeSearch').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ route("findManageProductTypes") }}' + search);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var items = xhr.response;
                    document.getElementById('typeList').innerHTML = "";
                    document.getElementById('typeList').insertAdjacentHTML('beforeend', items.join(' '));
                } else {
                    console.log('Filtering product types failed.  Returned status of ' + xhr.status);
                }
            };
            xhr.responseType = 'json';
            xhr.send();
        }
    </script>

@endsection
