<?php

namespace Tests;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn($user = null)
    {
        $user = $user ?: User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function create()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user ?? User::factory()
        ]);


        return $contact;
    }

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }
}
