@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Product Reserveren</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('processReserveProduct', $product->id) }}" id="reservationForm" name="reservationForm" method="post" onSubmit="return false">
            @csrf
            <h2>{{ $product->name }}</h2>
            <div class="row">
                <div class="col-6">
                    <div class="mb-1 mb-lg-4 row">
                        <label for="reserveFor" class="col-sm-4 col-form-label">Uitlenen aan:</label>
                        <div class="col-sm-4 col-form-label">
                            <input type="radio" id="radioStudent" name="reserveFor" value="student" {{ (($request->input('reserveFor') === "student") || !$request->input('reserveFor')) ? 'checked' : '' }}>
                            <label for="radioStudent">Student</label>
                        </div>
                        <div class="col-sm-4 col-form-label">
                            <input type="radio" id="radioTeacher" name="reserveFor" value="teacher" {{ ($request->input('reserveFor') === "teacher") ? 'checked' : '' }}>
                            <label for="radioTeacher">Docent</label>
                        </div>
                        </div>
                    <div id="studentNumberInput" class="mb-1 mb-lg-4 row">
                        <label for="studentNumber" class="col-sm-4 col-form-label">Studentnummer:</label>
                        <div class="col-sm-8">
                            <input autofocus required type="number" class="form-control" id="studentNumber" name="studentNumber" {{ $request->input('studentNumber') ? 'value="'. $request->input('studentNumber') .'"' : '' }}>
                            <p class="d-none mb-0" id="studentNumberValidation1" style="color:red;">Vul een studentnummer in!</p>
                            <p class="d-none mb-0" id="studentNumberValidation2" style="color:red;">Studentnummer te kort!</p>
                        </div>
                    </div>
                    <div id="teacherInput" class="mb-1 mb-lg-4 row d-none">
                        <label for="teacher" class="col-sm-4 col-form-label">Docent:</label>
                        <div class="col-sm-8">
                            <select id="teacher" name="teacher" class="form-control">
                                <option value=""></option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ ($request->input('teacher') === $teacher->id) ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-1 mb-lg-4 row">
                        <label for="returnBy" class="col-sm-4 col-form-label">Gereserveerd tot:</label>
                        <div class="col-sm-8">
                                <input required type="date" class="form-control" id="returnBy" name="returnBy" onchange="checkReturnBy()" {{ $request->input('returnBy') ? 'value="'. $request->input('returnBy') .'"' : '' }}>
                            <p class="d-none mb-0" id="dateValidation" style="color:red;">Datum moet vandaag of later zijn!</p>
                        </div>
                    </div>
                    <div class="mb-1 mb-lg-4 row">
                        <label for="note" class="col-sm-4 col-form-label">Notitie:</label>
                        <div class="col-sm-8">
                                <textarea rows="3" class="form-control" id="note" name="note" placeholder="Max 1000 karakters">{{ $request->input('note') }}</textarea>
                                <p class="d-none mb-0" id="noteValidation" style="color:red;">Maximaal 1000 karakters!</p>
                        </div>
                    </div>
                    <div id="reservedByInput" class="mb-1 mb-lg-4 row">
                        <label for="reservedBy" class="col-sm-4 col-form-label">Gereserveerd door:</label>
                        <div class="col-sm-8">
                            <select id="reservedBy" name="reservedBy" class="form-control">
                                    <option value=""></option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ ($request->input('reservedBy') === $teacher->id) ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-1 mb-lg-4 row">
                        <div class="col-sm-6">
                            <p class="font-weight-bold">De ontvanger gaat akkoord met de voorwaarden:</p>
                        </div>
                        <div class="col-sm-6 form-check">
                            <input id="agreementCheck" style="width: 30px; height: 30px;" class="form-check-input" name="productCheck" type="checkbox">
                        </div>
                    </div>
                    <div class="row d-flex justify-content-end">
                        <input class="btn-lg btn-primary disabled mr-3 col-sm-4" type="button" id="submitButton" value="Uitlenen" onclick="submitForm()">
                    </div>
                </div>
                <div class="col-6">
                    @if(null !== $product->image)
                        <img class="img-fluid" src="{{ asset($product->image) }}">
                    @else
                        <img class="img-fluid" src="{{ asset('img/default.jpg') }}">
                    @endif
                </div>
            </div>

        </form>
        <div id="agreement-text">
            <h3>Voorwaarden:</h3>
            <ol>
                <li>De ontvanger is aansprakelijk voor de op diens naam geleende materialen. Wordt bij het terugbrengen van de materialen een beschadiging of verontreiniging geconstateerd, of zijn de materialen gestolen of vermist, dan is de ontvanger verantwoordelijk. In dat geval worden er afspraken gemaakt tussen de beheerder (Steven van Rosendaal, <a href="mailto:s.vanrosendaal@curio.nl">s.vanrosendaal@curio.nl</a>) en de ontvanger over de afhandeling daarvan. Probeer bij schade nooit zelf te repareren.</li>
                <li>De ontvanger leent materialen voor de daarvoor vastgestelde leentermijn. Indien de ontvanger materialen na het verstrijken van de leentermijn ze nog niet heeft ingeleverd, dan wordt dit zo snel mogelijk gemeld bij de uitgever of de SLB'er. Bij verder uitblijven van het inleveren worden verdere stappen ondernomen.</li>
                <ul>
                    <li>Als de ontvanger onder de 18 jaar is worden de ouders op de hoogte gebracht.</li>
                    <li>Als de ontvanger boven de 18 jaar is wordt er contact met de ontvanger zelf opgenomen</li>
                    <li>Bij langdurig uitblijven van de vastgestelde leentermijn wordt er altijd aangifte gedaan.</li>
                </ul>
                <li>Gezien de ontvanger verantwoordelijk is voor de geleende materialen, is het niet toegestaan om het product verder uit te lenen. Als een andere student/docent wenst de materialen te lenen, moeten die opnieuw door ons uitgeleend worden.</li>
                <ul>
                    <li>Het is wel toegestaan dat de materialen door anderen gebruikt worden als de ontvanger toezicht houdt. De ontvanger blijft verantwoordelijk voor de materialen.</li>
                </ul>
            </ol>

        </div>
    </div>

    <script type="text/javascript">

        document.getElementById('radioStudent').addEventListener('change', checkReserveFor);
        document.getElementById('radioTeacher').addEventListener('change', checkReserveFor);
        document.getElementById('studentNumber').addEventListener("focusout", checkStudentNumber);
        document.getElementById('note').addEventListener("focusout", checkNote);
        document.getElementById('returnBy').valueAsDate = new Date();

        var validStudentNumber = false;
        var validReturnBy = false;
        var validNote = true;
        var checkedAgreement = false;

        function checkReserveFor() {
            if(document.getElementById('radioStudent').checked){
                document.getElementById('studentNumberInput').classList.remove('d-none');
                document.getElementById('studentNumber').disabled = false;

                document.getElementById('teacherInput').classList.add('d-none');
                document.getElementById('teacher').disabled = true;
            }

            if(document.getElementById('radioTeacher').checked){
                document.getElementById('teacherInput').classList.remove('d-none');
                document.getElementById('teacher').disabled = false;

                document.getElementById('studentNumberInput').classList.add('d-none');
                document.getElementById('studentNumber').disabled = true;
            }
        }

        function checkStudentNumber() {
            if(document.getElementById('studentNumber').value.length === 0) {
                document.getElementById('studentNumberValidation1').classList.remove('d-none');
                document.getElementById('studentNumberValidation1').classList.add('d-block');
                validStudentNumber = false;
            } else if(document.getElementById('studentNumber').value.length < 6){
                document.getElementById('studentNumberValidation2').classList.remove('d-none');
                document.getElementById('studentNumberValidation2').classList.add('d-block');
                validStudentNumber = false;
            } else {
                document.getElementById('studentNumberValidation1').classList.add('d-none');
                document.getElementById('studentNumberValidation1').classList.remove('d-block');
                document.getElementById('studentNumberValidation2').classList.add('d-none');
                document.getElementById('studentNumberValidation2').classList.remove('d-block');
                validStudentNumber = true;
            }
            checkSubmit();
        }

        function checkReturnBy() {
            var dateString = document.getElementById('returnBy').value;
            var returnByDate = new Date(dateString);
            var today = new Date().setHours(0,0,0,0);
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

        function checkNote() {
            if(document.getElementById('note').value.length > 1000) {
                document.getElementById('noteValidation').classList.remove('d-none');
                document.getElementById('noteValidation').classList.add('d-block');
                validNote = false;
            } else {
                document.getElementById('noteValidation').classList.add('d-none');
                document.getElementById('noteValidation').classList.remove('d-block');
                validNote = true;
            }
            checkSubmit();
        }

        document.getElementById('agreementCheck').addEventListener("click", checkAgreementCheck);

        function checkAgreementCheck() {
            if(document.getElementById('agreementCheck').checked === true) {
                checkedAgreement = true;
            } else {
                checkedAgreement = false;
            }
            checkSubmit();
        }

        function checkSubmit(){
            if(validStudentNumber && validReturnBy && validNote && checkedAgreement) {
                document.getElementById('submitButton').disabled = false;
            } else {
                document.getElementById('submitButton').disabled = true;
            }
        }

        function submitForm() {
            if(validStudentNumber && validReturnBy && validNote && checkedAgreement) {
                document.reservationForm.submit()
            }
        }

        @if(null !== $request->input('studentNumber') || null !== $request->input('teacher') || null !== $request->input('returnBy') || null !== $request->input('note')) {
            checkReserveFor();
            checkStudentNumber();
            checkReturnBy();
            checkNote();
        }
        @endif
    </script>

@endsection
