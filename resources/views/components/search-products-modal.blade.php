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
                <p class="text-danger">LET OP: Je wordt uitgelogd op deze app, maar NIET op de Curio Codes. Als dit systeem gebruikt wordt om producten op uit te lenen, is het aan te raden om ook op de Curio Codes uit te loggen, anders worden gebruikers automatisch ingelogd op het openstaande account als er op inloggen geklikt wordt.</p>
                <div class="d-flex">
                    <label class="mt-1 mr-2 font-weight-bold" for="logoutCheck">Ik ben uitgelogd op Curio Codes, of ik besef de risico's:</label>
                    <input id="logoutCheck" style="width: 30px; height: 30px;" name="logoutCheck" type="checkbox">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                <a id="logoutButton" href="{{ route('searchProducts') }}" class="btn btn-danger disabled">Uitloggen en Doorgaan</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    document.getElementById('logoutCheck').addEventListener("click", checkProductCheck);

    function checkProductCheck() {
        if(document.getElementById('logoutCheck').checked === true) {
            document.getElementById('logoutButton').classList.remove('disabled');
        } else {
            document.getElementById('logoutButton').classList.add('disabled');
        }
    }

</script>
