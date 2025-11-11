<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Ensure the API ping endpoint returns 200 and expected JSON.
     */
    public function test_api_ping_returns_pong()
    {
        $response = $this->getJson('/api/ping');

        $response->assertOk()
                 ->assertExactJson(['pong' => true]);
    }
}
