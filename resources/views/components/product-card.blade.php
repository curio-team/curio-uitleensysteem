<div class="col-4 mb-2">
    <div class="card">
        @if ($product->image)
        <img class="card-img-top" src="{{ asset($product->image) }}" alt="Card image cap">
        @else
        <img class="card-img-top" src="{{ asset('img/default.jpg') }}" alt="Card image cap">
        @endif
        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            @if ($product->currentReservation()) <!-- Zolang er een reservering is -->
                <p class="card-text">Uitgeleend!</p>
                <a href="{{ route('returnProduct', $product->id) }}" class="btn btn-danger">Retour</a>
            @else <!-- Als er helemaal geen reservering gevonden kan worden -->
                <p class="card-text">Beschikbaar!</p>
                <a href="{{ route('reserveProduct', $product->id) }}" class="btn btn-primary">Uitlenen</a>
            @endif
        </div>
    </div>
</div>
