<tr class="table-danger">
    <td scope="row"><a href="{{ route('manageReservation', $reservation->id) }}">{{ $reservation->id }}</a></td>
    @if($reservation->student)
        <td><a href="{{ route('showStudent', $reservation->student->id) }}">{{ $reservation->student->name }}</a></td>
    @elseif($reservation->teacher)
        <td><a href="{{ route('showTeacher', $reservation->teacher->id) }}">{{ $reservation->teacher->name }}</a></td>
    @else
        <td>{{ $reservation->student_number }}</td>
    @endif
    <td>{{ \Carbon\Carbon::parse($reservation->issue_date)->translatedFormat('l d F Y - h:i:s') }}</td>
    @if($reservation->return_by_date)
        <td>{{ \Carbon\Carbon::parse($reservation->return_by_date)->translatedFormat('l d F Y') }}</td>
    @else
        <td>Geen retourdatum bekend</td>
    @endif
</tr>
