<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_guest_is_redirected_to_projects(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/projects');
    }
}