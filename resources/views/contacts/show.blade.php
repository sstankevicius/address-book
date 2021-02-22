@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-12 margin-tb">
            <div class="float-left">
                <h3> Show Contact</h3>
            </div>
            <div class="float-right">
                <a class="btn btn-primary" href="{{ route('contacts.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $contact->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Phone:</strong>
                {{ $contact->phone }}
            </div>
        </div>
    </div>
@endsection
