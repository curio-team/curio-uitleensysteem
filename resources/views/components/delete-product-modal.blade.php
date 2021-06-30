<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Verwijderen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Weet je zeker dat je product "{{ $product->name }}" permanent wilt verwijderen?</p>
                <p><small>De opgeslagen reserveringen van dit product zullen ook verwijderd worden.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                <a href="{{ route('processDeleteProduct', $product->id) }}" class="btn btn-danger">Verwijderen</a>
            </div>
        </div>
    </div>
</div>
