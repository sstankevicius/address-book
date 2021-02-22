@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="float-right mb-4">
                <a class="btn btn-success" href="{{ route('contacts.create') }}"> Create New Contact</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($contacts as $contact)
            <tr>
                <td>{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->phone }}</td>
                <td>
                    <form action="{{ route('contacts.destroy',$contact->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('contacts.show',$contact->id) }}">Show</a>
                        <a class="btn btn-primary" href="{{ route('contacts.edit',$contact->id) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach

    </table>


@endsection
