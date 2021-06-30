<div class="mb-2">
    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#addedList" aria-expanded="true"
                            aria-controls="addedList">
                        Toegevoegde Producten
                    </button>
                </h5>
            </div>

            <div id="addedList" class="collapse" aria-labelledby="addedListHeading" data-parent="#accordion">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                        @foreach(session('added') as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->barcode }}</td>
                                <td>{{ $product->type->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>&euro;{{ $product->price }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
