<?php

namespace Tests\Feature\Contacts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexContactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_array_when_no_contacts()
    {
        $this->getJson('/api/contacts')
             ->assertOk()
             ->assertExactJson([]);
    }
}
