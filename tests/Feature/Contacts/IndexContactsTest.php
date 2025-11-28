<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_array_when_no_contacts(): void
    {
        // Call the API
        $response = $this->getJson('/api/contacts');

        // (1) Make sure the request did NOT error (status 200)
        $response->assertStatus(200);

        // (3) Now assert the important parts of the paginator
        $this->assertSame([], $response->json('data'));   // empty list
        $this->assertSame(0, $response->json('total'));   // no records
    }
}
