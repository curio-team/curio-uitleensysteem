@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Student Beheren</h1>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-sm-4">
                        <p>Studentnummer:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ $student->id }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Naam:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ $student->name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Email:</p>
                    </div>
                    <div class="col-sm-8">
                        <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                    </div>
                </div>
            <div class="col-6">
            </div>
        </div>
    </div>

@endsection
