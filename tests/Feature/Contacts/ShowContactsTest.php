<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Contact;

class ShowContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_a_created_contact()
    {
        $contact = Contact::factory()->create();

        $this->getJson('/api/contacts/'.$contact->id)
             ->assertOk()
             ->assertJsonFragment(['email' => $contact->email]);
    }
}
