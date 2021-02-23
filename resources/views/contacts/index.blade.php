@extends('layouts.app')

@section('content')


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    <h3 class="m-5">People sharing</h3>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>shared by</th>
        </tr>
    @foreach($sharedContacts as $sharedContact)
        <tr>
            <td>{{ $sharedContact->id }}</td>
            <td>{{ $sharedContact->name }}</td>
            <td>{{ $sharedContact->phone }}</td>
            <td>{{ $sharedContact->user->name }}</td>

        </tr>
    @endforeach


    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Shared_with</th>
            <th width="280px">Action</th>
        </tr>
        <h3 class="m-5">Your Contacts</h3>
        <div class="row">
            <div class="col-lg-12">
                <div class="float-right mb-4">
                    <a class="btn btn-success" href="{{ route('contacts.create') }}"> Create New Contact</a>
                </div>
            </div>
        </div>
        @foreach ($contacts as $contact)
            <tr>
                <td>{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->phone }}</td>
                <td class>
                    @foreach($contact->sharing as $user)
                        <div class="d-flex flex-row">
                            <span class="mr-2">{{ $user->name }}</span>
                            <form method="POST"  action="{{ route('stop.share', $contact) }}">
                                @csrf

                                <input type="hidden" name="stopShare" value="{{$user->id}}">

                                <button type="submit" class="btn btn-danger btn-sm">X</button>
                            </form>
                        </div>
                            @endforeach


                </td>
                <td>
                    <form action="{{ route('contacts.destroy',$contact->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('contacts.show',$contact->id) }}">Show</a>
                        <a class="btn btn-primary" href="{{ route('contacts.edit',$contact->id) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>

                    <form method="POST" action="{{ route('share.share', $contact) }}">
                        @csrf
                        <select class="custom-select mt-3" name="selectedUsers">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-success">Share</button>
                    </form>
                </td>
            </tr>
        @endforeach

    </table>


@endsection
