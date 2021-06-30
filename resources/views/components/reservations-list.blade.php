<tr {{ ($reservation->returned_date == null) ? 'class=table-info' : '' }}>
    <td scope="row"><a href="{{ route('manageReservation', $reservation->id) }}">{{ $reservation->id }}</a></td>
    <td>{{ $reservation->student_number }}</td>
    @if($reservation->student)
        <td><a href="{{ route('showStudent', $reservation->student->id) }}">{{ $reservation->student->name }}</a></td>
    @else
        <td>{{ $reservation->student_number }}</td>
    @endif
    <td>{{ $reservation->issue_date }}</td>
    <td>{{ $reservation->return_by_date }}</td>
    <td>{{ $reservation->returned_date }}</td>
    <td>
        <textarea readonly rows="2">{{ $reservation->note }}</textarea>
    </td>
</tr>
