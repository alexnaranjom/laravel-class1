<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Contact;
use App\Models\User;

class DestroyContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletes_a_contact_and_returns_204()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $contact = Contact::factory()->create();

        $this->actingAs($user)
             ->deleteJson('/api/contacts/'.$contact->id)
             ->assertNoContent();

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
