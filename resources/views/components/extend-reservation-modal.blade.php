<div class="modal fade" id="extendReservationModal" tabindex="-1" role="dialog" aria-labelledby="extendReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('extendReservation', $reservation->id) }}" id="extendReservationForm" name="extendReservationForm" method="post" onSubmit="return false">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="extendReservationModalLabel">Product Archiveren</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tot wanneer moet deze reservering verlengd worden?</p>
                    <label for="returnBy" class="col-sm-4 col-form-label">Gereserveerd tot:</label>
                    <div class="col-sm-8">
                        <input required type="date" class="form-control" id="returnBy" name="returnBy" onchange="checkReturnBy()" value="{{ $reservation->return_by_date }}">
                        <p class="d-none mb-0" id="dateValidation" style="color:red;">Datum moet vandaag of later zijn!</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-1" data-dismiss="modal">Annuleren</button>
                    <input class="btn btn-primary" type="button" id="extendReservationSubmitButton" value="Uitstellen" onClick="document.extendReservationForm.submit()">
                    @if(Auth::user()->super_admin)
                        <a href="{{ route('extendReservationIndefinitely', $reservation->id) }}" class="btn btn-danger">Reservering oneindig laten doorlopen</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var validReturnBy = false;

    function checkReturnBy() {
        var dateString = document.getElementById('returnBy').value;
        var returnByDate = new Date(dateString);
        var today = new Date();
        if ( returnByDate < today ) {
            document.getElementById('dateValidation').classList.remove('d-none');
            document.getElementById('dateValidation').classList.add('d-block');
            validReturnBy = false;
        } else {
            document.getElementById('dateValidation').classList.remove('d-block');
            document.getElementById('dateValidation').classList.add('d-none');
            validReturnBy = true;
        }
        checkSubmit();
    }

    function checkSubmit(){
        if(validReturnBy) {
            document.getElementById('extendReservationSubmitButton').disabled = false;
        } else {
            document.getElementById('extendReservationSubmitButton').disabled = true;
        }
    }
</script>
