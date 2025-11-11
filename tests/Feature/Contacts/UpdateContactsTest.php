<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Contact;

class UpdateContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_updates_contact_phone_and_returns_200()
    {
        $contact = Contact::factory()->create(['phone' => '111-1111']);

        $this->putJson('/api/contacts/'.$contact->id, [ 'phone' => '222-2222' ])
             ->assertOk()
             ->assertJsonFragment([ 'phone' => '222-2222' ]);

        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'phone' => '222-2222']);
    }
}
