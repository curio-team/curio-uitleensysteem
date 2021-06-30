<div class="modal fade" id="deleteProductTypeModal{{ $type->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteProductTypeModal{{ $type->id }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Producttype Verwijderen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Weet je zeker dat je producttype "{{ $type->name }}" permanent wilt verwijderen?</p>
                <p><small>Producten met dit producttype zullen hierna geen producttype meer hebben.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                <a href="{{ route('processDeleteProductType', $type->id) }}" class="btn btn-danger">Verwijderen</a>
            </div>
        </div>
    </div>
</div>
