<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\ShareContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->accessibleContacts();

        $sharedContacts = Auth::user()->shared;

        $users = User::all()->except(Auth::id());

        return view('contacts.index', compact('contacts', 'sharedContacts', 'users'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(CreateContactRequest $request)
    {
        $contact = auth()->user()->contacts()->create($request->all());

        return redirect($contact->path());
    }

    public function show($id)
    {
        $contact = auth()->user()->contacts()->find($id);

        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit',compact('contact'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->all());

        return redirect($contact->path());
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect('/contacts')->with('success', 'Contact removed');
    }

    public function share(Request $request, Contact $contact)
    {
        if ($contact->sharing()->where('id', $request->input('selectedUsers'))->exists()){
            return redirect()->back()->with('error', 'You already sharing this contact with this user');
        }
        $contact->sharing()->attach($request->input('selectedUsers'));

        return redirect()->back()->with('success', 'Contact shared');
    }

    public function stopShare(Contact $contact, Request $request)
    {
        $contact->sharing()->detach($request->input('stopShare'));

        return redirect()->back()->with('success', 'You stopped sharing this contact');
    }

}
