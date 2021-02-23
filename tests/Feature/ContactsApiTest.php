<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Contact;
use Tests\TestCase;

class ContactsApiTest extends TestCase
{
    use RefreshDatabase;

    use RefreshDatabase;

    const USER_EMAIL = 'user@company';

    /**
     * @test
     * @group posts
     */
    public function api_is_accessible()
    {
        $this->json('get', 'api/contacts')
            ->assertStatus(200);
    }

    /**
     * @test
     * @group posts
     */
    public function user_can_create_contact()
    {
        Passport::actingAs(User::where('email', self::USER_EMAIL)->first());

        $data = [
            'name'   => 'Test name',
            'phone' => '+3706202456',
        ];

        $this->json('post', 'api/contacts', $data)
            ->assertStatus(201)
            ->getContent();

        $this->assertDatabaseHas('contacts', $data);
    }

    /**
     * @test
     * @group posts
     */
    public function user_can_update_a_contact()
    {
        Passport::actingAs(User::where('email', self::ADMIN_EMAIL)->first());

        $data = [
            'name' => 'Updated name',
        ];

        $contact = Contact::factory()->create();

        $this->json('put', 'api/contacts/' . $contact->id, $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('contacts', $data);
    }

    /**
     * @test
     * @group posts
     */
    public function user_can_delete_a_contact()
    {
        Passport::actingAs(User::where('email', self::USER_EMAIL)->first());

        $post = Contact::factory()->create();

        $this->json('delete', 'api/contacts/' . $post->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('contacts', $post->toArray());
    }

    /**
     * @test
     * @group posts
     */
    public function invalid_input_is_not_acceptable()
    {
        Passport::actingAs(User::where('email', self::USER_EMAIL)->first());

        $data = [
            'name' => 78363,
        ];

        $this->json('post', 'api/contacts/', $data)->assertStatus(422);

        $this->assertDatabaseMissing('contacts', $data);
    }

}
