<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Bids;
use App\Models\Slots;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
  
    //create user test
    public function test_create_user()
    {
        $response = $this->postJson('/api/user/create', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'User created successfully']);
    }
    //user login test
    public function test_user_login()
    {
        // First, create a user to log in
        $this->postJson('/api/user/create', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response = $this->postJson('/api/user/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'User logged in successfully']);
    }
    //user logout test
    public function test_user_logout()
    {
        $this->postJson('/api/user/create', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $this->postJson('/api/user/login', [
            'email' => 'testuser@example.com',
            'password' => 'password',
        ]);
        $response = $this->postJson('/api/user/logout');
 
        $response->assertStatus(200);
        $response->assertJson(['message' => 'User logged out successfully']);
    }
    //user bids history test
    public function test_user_bids_history()
    {
        // Create a user and some bids for that user
        $user = User::factory()->create();
        $slot = Slots::factory()->create(['status' => 'open']);
        Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 100]);
        Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 200]);

        $response = $this->actingAs($user)->getJson("/api/user/{$user->id}/bids");
//print as json response
        $response->dump();

        $response->assertStatus(200);
        $response->assertJsonStructure(structure: [
            'message',
            'data' => [
                '*' => [
                    'id',
                    'slot_id',
                    'user_id',
                    'amount',
                    'slot' => [
                        'id',
                        'status',
                        // Add other slot fields as needed
                    ],
                ],
            ],
        ]);

    }
}
