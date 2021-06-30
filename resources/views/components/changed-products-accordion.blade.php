<div class="mb-2">
    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#changedList" aria-expanded="true"
                            aria-controls="changedList">
                        Aangepaste Producten
                    </button>
                </h5>
            </div>

            <div id="changedList" class="collapse" aria-labelledby="changedListHeading" data-parent="#accordion">
                <div class="card-body">
                    <h3>Oude Data:</h3>
                    <table class="table">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                        @foreach(session('changed')['old'] as $product)
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
                    <h3>Nieuwe Data:</h3>
                    <table class="table">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                        @foreach(session('changed')['new'] as $product)
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
