<div class="modal fade" id="dearchiveProductModal" tabindex="-1" role="dialog" aria-labelledby="dearchiveProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dearchiveProductModalLabel">Product Dearchiveren</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Weet je zeker dat je product "{{ $product->name }}" wilt dearchiveren?</p>
                <p><small>Dit product zal weer gevonden kunnen worden bij het inscannen van de barcode.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                <a href="{{ route('processDearchiveProduct', $product->id) }}" class="btn btn-danger">Dearchiveren</a>
            </div>
        </div>
    </div>
</div>
