@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Reserveringen Beheren</h1>
        </div>

        <div class="form-group row align-items-center">
            <label for="productCode" class="col-2 col-form-label">Barcode / Naam / Type:</label>
            <input autofocus type="text" class="form-control col-4" id="productSearch" oninput="filterProducts()">
            <div class="col"></div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">
                <h3>Open Reserveringen</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Productnaam</th>
                        <th scope="col">Gereserveerd door</th>
                        <th scope="col">Gereserveerd op</th>
                        <th scope="col">Gereserveerd tot</th>
                    </tr>
                    </thead>
                    <tbody id="reservationsList">
                    @foreach($reservations as $reservation)
                        @include('components.manage-reservations-list', $reservation)
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center" id="paginationBar">
                    {{ $reservations->onEachSide(5)->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="d-flex justify-content-between">
                    <h3 class="col-6">Te laat:</h3>
                    <a href="{{ route('exportLateReservations') }}" class="col-3 btn btn-primary">CSV Export</a>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Productnaam</th>
                        <th scope="col">Gereserveerd door</th>
                        <th scope="col">Gereserveerd op</th>
                        <th scope="col">Gereserveerd tot</th>
                    </tr>
                    </thead>
                    <tbody id="lateReservationsList">
                    @foreach($lateReservations as $reservation)
                        @include('components.manage-reservations-list', $reservation)
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="application/javascript">

        function filterProducts() {
            var search = '?search=' + document.getElementById('productSearch').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '{{ route("findReservations") }}' + search);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var items = xhr.response;
                    if(items['redirect']){
                        window.location.replace("/admin/reserveringen/" + items['redirect']);
                        return;
                    }
                    document.getElementById('reservationsList').innerHTML = "";
                    document.getElementById('reservationsList').insertAdjacentHTML('beforeend', items.reservations.join(' '));
                    document.getElementById('lateReservationsList').innerHTML = "";
                    document.getElementById('lateReservationsList').insertAdjacentHTML('beforeend', items.lateReservations.join(' '));
                } else {
                    console.log('Filtering products failed.  Returned status of ' + xhr.status);
                }
            };
            xhr.responseType = 'json';
            xhr.send();
        }
    </script>

@endsection
