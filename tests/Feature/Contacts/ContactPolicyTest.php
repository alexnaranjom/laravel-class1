<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Contact;

class ContactPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_delete_contact()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $contact = Contact::factory()->create();

        $this->actingAs($user)
             ->deleteJson('/api/contacts/'.$contact->id)
             ->assertStatus(403);

        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
    }

    public function test_admin_can_delete_contact()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $contact = Contact::factory()->create();

        $this->actingAs($user)
             ->deleteJson('/api/contacts/'.$contact->id)
             ->assertNoContent();

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
