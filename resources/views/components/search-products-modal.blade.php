<div class="modal fade" id="searchProductsModal" tabindex="-1" role="dialog" aria-labelledby="searchProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Producten Uitlenen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Als je naar de "Producten Uitlenen" pagina gaat, wordt je uitgelogd. Weet je het zeker?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                <a href="{{ route('searchProducts') }}" class="btn btn-danger">Uitloggen en Doorgaan</a>
            </div>
        </div>
    </div>
</div>
