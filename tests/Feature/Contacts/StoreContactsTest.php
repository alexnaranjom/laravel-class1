<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Contact;

class StoreContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_contact_with_valid_data_and_returns_201()
    {
        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'phone' => '555-0100',
        ];

        $this->postJson('/api/contacts', $payload)
             ->assertCreated()
             ->assertJsonFragment([ 'name' => 'Alice', 'email' => 'alice@example.com' ]);

        $this->assertDatabaseHas('contacts', [ 'email' => 'alice@example.com' ]);
    }

    public function test_returns_422_when_required_fields_missing()
    {
        $this->postJson('/api/contacts', [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name', 'email']);
    }
}
