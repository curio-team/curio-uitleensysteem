<tr>
    <th scope="row"><a href="{{ route('showArchivedProduct', $product->id) }}">{{ $product->barcode }}</a></th>
    <td><a href="{{ route('showArchivedProduct', $product->id) }}">{{ $product->name }}</a></td>
    @if($product->type)
        <td>{{ $product->type->name }}</td>
    @else
        <td></td>
    @endif
    @if($product->currentReservation())
        <td>{{ \Carbon\Carbon::parse($product->currentReservation()->issue_date)->translatedFormat('l d F Y - h:i:s') }}</td>
        @if($product->currentReservation()->return_by_date)
            <td>{{ \Carbon\Carbon::parse($product->currentReservation()->return_by_date)->translatedFormat('l d F Y') }}</td>
        @else
            <td>Geen retourdatum bekend</td>
        @endif
        @if($product->currentReservation()->student)
            <td><a href="{{ route('showStudent', $product->currentReservation()->student->id) }}">{{ $product->currentReservation()->student->name }}</a></td>
        @elseif($product->currentReservation()->teacher)
            <td><a href="{{ route('showTeacher', $product->currentReservation()->teacher->id) }}">{{ $product->currentReservation()->teacher->name }}</a></td>
        @else
            <td>{{ $product->currentReservation()->student_number }}</td>
        @endif
    @else
        <td></td>
        <td></td>
        <td></td>
    @endif
</tr>
