<div class="modal fade" id="archiveProductModal" tabindex="-1" role="dialog" aria-labelledby="archiveProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveProductModalLabel">Product Archiveren</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Weet je zeker dat je product "{{ $product->name }}" wilt archiveren?</p>
                <p><small>De gegevens en opgeslagen reserveringen van dit product blijven bewaard, maar deze zal niet meer gevonden worden bij het inscannen van de barcode.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                <a href="{{ route('processArchiveProduct', $product->id) }}" class="btn btn-danger">Archiveren</a>
            </div>
        </div>
    </div>
</div>
