<tr>
    <th scope="row">{{ $type->id }}</th>
    <td>{{ $type->name }}</td>
    <td>{{ $type->created_at }}</td>
    <td class="d-flex justify-content-end">
        <a href="{{ route('editProductType', $type->id) }}" class="btn-lg btn-primary mr-1">Aanpassen</a>
        <button type="button" class="btn-lg btn-danger" data-toggle="modal" data-target="#deleteProductTypeModal{{ $type->id }}">
            Verwijderen
        </button>
    </td>
</tr>
