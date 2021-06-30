<tr class="table-danger">
    <td scope="row"><a href="{{ route('manageReservation', $reservation->id) }}">{{ $reservation->id }}</a></td>
    <td><a href="{{ route('manageProduct', $reservation->product->id) }}">{{ $reservation->product->name }}</a></td>
    <td>{{ $reservation->student_number }}</td>
    <td>{{ $reservation->issue_date }}</td>
    <td>{{ $reservation->return_by_date }}</td>
</tr>
