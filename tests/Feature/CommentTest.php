<?php

namespace Tests\Feature;

use App\Models\Problem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_can_access_comments_page(): void
    {
        // Create a user with contestant role
        $user = User::factory()->create([
            'role' => 'contestant'
        ]);

        // Create a problem
        $problem = Problem::factory()->create();

        // Act as the user and try to access the comments page
        $response = $this->actingAs($user)
                         ->get(route('comments.index', $problem));

        // Assert the response is successful
        $response->assertStatus(200);
    }
}
