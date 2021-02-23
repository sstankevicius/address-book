<?php


namespace App\Http\Services;


use App\Models\Contact;
use App\Models\User;

class Help
{

    public function sharing($contact, $user, $action){

        if ($user) {
            if ($contact && Contact::where([['id', '=', $contact->id], ['user_id', '=', auth()->user()->id]])->first()) {
                if ($contact->sharing()->where('id', $user->id)->exists()) {
                    return redirect()->back()->with('error', 'You already sharing this contact with this user');
                } elseif ($user->id == auth()->user()->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot share contact with yourself'
                    ], 400);
                } else {
                    $contact->sharing()->attach($user->id);

                    return response()->json([
                        "message" => "shared successfully"
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

}
