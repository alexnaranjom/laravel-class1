<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Contact;

class PestDuplicateEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_fails_when_email_is_duplicate()
    {
        Contact::factory()->create(['email' => 'dup@example.com']);

        $payload = [
            'name' => 'Bob',
            'email' => 'dup@example.com',
        ];

        $this->postJson('/api/contacts', $payload)
             ->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
    }

    public function test_update_allows_keeping_same_email_for_same_record()
    {
        $contact = Contact::factory()->create(['email' => 'keep@example.com']);

        $payload = [
            'name' => 'Updated',
            'email' => 'keep@example.com',
        ];

        $this->putJson('/api/contacts/' . $contact->id, $payload)
             ->assertOk()
             ->assertJsonFragment(['email' => 'keep@example.com']);
    }
}
