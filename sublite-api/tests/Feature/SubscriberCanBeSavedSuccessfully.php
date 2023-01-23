<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_subscriber_can_be_saved_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}