<?php

namespace Tests\Feature;

use App\Models\Slots;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class SlotsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    // Test to list all slots with pagination using authenticated user
    public function test_list_slots()
    {
        // Assuming you have a user logged in, you can use the following code
         $user = User::factory()->create(); // Create a user for testing
        Slots::factory()->count(15)->create(); // Create 15 slots for testing
        
        $response = $this->actingAs($user)->getJson('/api/slots');


        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'start_time',
                    'end_time',
                    'status',
                    'price',
                ],
            ],
            'last_page',
            'total',
        ]);
        $this->assertCount(10, $response->json('data'));
    }
}
