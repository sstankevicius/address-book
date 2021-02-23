<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactApiController extends Controller
{

    // get list of contacts this user sharing
    // validate pivot duplicate
    // share, stopshare ne is linko o per request paduot kuris contactas
    public function index()
    {
        $contacts = Auth::user()->accessibleContacts();

        $sharedContacts = Auth::user()->shared;

        $sharableContacts = [];

        foreach ($contacts as $contact) {
            if($contact->sharing()->exists()) {
                $sharableContacts[] = $contact;
            }
        }

        return response()->json([
            'success' => true,
            'yourContacts' => $contacts,
            'sharedContacts' => $sharedContacts,
            'sharing' => $sharableContacts
        ]);
    }

    public function show($id)
    {
        $contact = auth()->user()->contacts()->find($id);

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found '
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $contact->toArray()
        ], 400);
    }

    public function store(CreateContactRequest $request)
    {
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->phone = $request->phone;

        if (auth()->user()->contacts()->save($contact))
            return response()->json([
                'success' => true,
                'data' => $contact->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Contact not added'
            ], 400);
    }

    public function update(UpdateContactRequest $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact){
            return response()->json([
                'success' => false,
                'message' => 'Contact not found'
            ], 500);
        }else {
            $contact->update($request->all());
            return response()->json([
                "message" => "Contact updated successfully"
            ], 200);
        }



    }

    public function destroy($id)
    {
        $contact = auth()->user()->contacts()->find($id);

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact not found'
            ], 400);
        }else{
            $contact->delete();
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted'
            ]);
        }
    }

    public function share(Request $request)
    {
        $contact = Contact::find($request->contact);
        $user = User::find($request->user);

        if ($user) {
            if ($contact && Contact::where([['id', '=', $contact->id], ['user_id', '=', auth()->user()->id]])->first()) {
                if ($contact->sharing()->where('id', $user->id)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You already sharing this contact with this user'
                    ], 400);
                } elseif ($user->id == auth()->user()->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot share contact with yourself'
                    ], 400);
                } else {
                    $contact->sharing()->attach($user->id);

                    return response()->json([
                        "message" => "Contact shared successfully"
                    ], 200);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Contact not found'
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 400);
        }
    }

    public function stopShare( Request $request)
    {

        $contact = Contact::where([['id', '=', $request->contact],['user_id', '=', auth()->user()->id ]])->first();
        $user = User::find($request->user);

        $accesibleContacts = Auth::user()->accessibleContacts();

        if ($contact){
            if ($user){
                foreach ($accesibleContacts as $accesibleContact) {
                    if($accesibleContact->sharing()->exists()) {
                        if ($contact->id == $accesibleContact->id){
                            if($contact->sharing()->detach($user->id)){
                                return response()->json([
                                    "message" => "You stopped sharing this contact with this user",
                                    "data" => $accesibleContact
                                ], 200);
                            }
                        }else{
                            return response()->json([
                                'success' => false,
                                "message" => "You are not sharing this contact with this user"
                            ], 400);
                        }
                    }else{
                        continue;
                    }
                }
            }else{
                return response()->json([
                    'success' => false,
                    "message" => "User not found"
                ], 400);
            }

        }else{
            return response()->json([
                'success' => false,
                "message" => "Contact not found"
            ], 400);
        }






    }
}
