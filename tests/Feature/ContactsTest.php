<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ContactFactory;
use Tests\TestCase;

class ContactsTest extends TestCase
{

    //generate data, ensuring after test we reset db

    use WithFaker,RefreshDatabase;


    /** @test */
    public function guests_cannot_manage_contacts()
    {
        $contact = Contact::factory()->create();

        $this->get('/contacts')->assertRedirect('login');
        $this->get('/contacts/create')->assertRedirect('login');
        $this->get($contact->path().'/edit')->assertRedirect('login');
        $this->get($contact->path())->assertRedirect('login');
        $this->post('/contacts', $contact->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_contact()
    {
        $this->signIn();

        $this->get('/contacts/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('/contacts', $attributes = Contact::factory()->raw())
            ->assertSee($attributes['name'])
            ->assertSee($attributes['phone']);
    }

    /** @test */
    function a_user_can_delete_a_contact()
    {
        $contact = $this->create();

        $this->actingAs($contact->user)
            ->delete($contact->path())
            ->assertRedirect('/contacts');

        $this->assertDatabaseMissing('contacts', $contact->only('id'));
    }

    /** @test */
    function a_user_can_update_a_contact()
    {
        $contact = $this->create();

        $this->actingAs($contact->user)
            ->patch($contact->path(), $attributes = ['name' => 'Changed', 'phone' => 'Changed'])
            ->assertRedirect($contact->path());

        $this->get($contact->path().'/edit')->assertOk();

        $this->assertDatabaseHas('contacts', $attributes);
    }

    /** @test */
    public function a_user_can_view_their_contact()
    {
        $contact = $this->create();

        $this->actingAs($contact->user)
            ->get($contact->path())
            ->assertSee($contact->name)
            ->assertSee($contact->phone);
    }

    /** @test */
    public function a_contact_requires_phone()
    {
        $this->signIn();

        $attributes = Contact::factory()->raw(['phone' => '']);

        $this->post('/contacts', $attributes)->assertSessionHasErrors('phone');
    }

    /** @test */
    public function a_contact_requires_name()
    {
        $this->signIn();

        $attributes = Contact::factory()->raw(['name' => '']);

        $this->post('/contacts', $attributes)->assertSessionHasErrors('name');
    }


}
