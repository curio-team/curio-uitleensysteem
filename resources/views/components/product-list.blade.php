<tr>
    <th scope="row"><a href="{{ route('manageProduct', $product->id) }}">{{ $product->barcode }}</a></th>
    <td><a href="{{ route('manageProduct', $product->id) }}">{{ $product->name }}</a></td>
    @if($product->type)
        <td>{{ $product->type->name }}</td>
    @else
        <td></td>
    @endif
    @if($product->currentReservation())
        <td>{{ $product->currentReservation()->issue_date }}</td>
        <td>{{ $product->currentReservation()->return_by_date }}</td>
        <td>{{ $product->currentReservation()->student_number }}</td>
    @else
        <td></td>
        <td></td>
        <td></td>
    @endif
</tr>
