@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h1>Docent Beheren</h1>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-sm-4">
                        <p>Docent Code:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ $teacher->code }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Naam:</p>
                    </div>
                    <div class="col-sm-8">
                        <p>{{ $teacher->name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p>Email:</p>
                    </div>
                    <div class="col-sm-8">
                        <a href="mailto:{{ $teacher->email }}">{{ $teacher->email }}</a>
                    </div>
                </div>
            <div class="col-6">
            </div>
        </div>
    </div>

@endsection
