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
                    <div class="row d-flex justify-content-end">
                        <input class="btn-lg btn-primary disabled mr-3 col-sm-4" type="button" id="submitButton" value="Uitlenen" onClick="document.reservationForm.submit()">
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

        function checkSubmit(){
            if(validStudentNumber && validReturnBy && validNote) {
                document.getElementById('submitButton').classList.remove('disabled');
            } else {
                document.getElementById('submitButton').classList.add('disabled');
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
