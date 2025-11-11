<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_array_initially(): void
    {
        $response = $this->getJson('/api/contacts', [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $this->assertSame([], $response->json()); // exact empty array
    }
}
