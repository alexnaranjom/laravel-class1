<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_array_initially()
    {
        $response = $this->getJson('/api/contacts', [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();

        // Now we expect a paginated structure with an empty "data" array
        $this->assertSame([], $response->json('data'));
        $this->assertSame(0, $response->json('total'));


        
    }

}
