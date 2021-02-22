<?php

namespace Tests\Unit;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class ContactTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function it_has_a_path()
    {
        $contact = Contact::factory()->create();

        $this->assertEquals('/contacts/' . $contact->id, $contact->path());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $contact = Contact::factory()->create();

        $this->assertInstanceOf(User::class, $contact->user);
    }
}
