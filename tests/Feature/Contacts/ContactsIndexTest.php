<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_api_contacts_returns_200_and_json()
    {
        $response = $this->getJson('/api/contacts');

        $response->assertOk()
                 ->assertJsonStructure([]);
    }
}