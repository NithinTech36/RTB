<?php

namespace Tests\Feature;

use App\Models\Slots;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Bids;
use App\Models\BidWinner;


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

    //Test to view winning bid for a slot
    public function test_view_winning_bid()
    {
        $user = User::factory()->create();
        $slot = Slots::factory()->create(['status' => 'awarded']);
        $Bid = Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 200.00]);
        
        $winningBid = $slot->bidWinners()->create([
            'bid_id' => $Bid->id,
            'user_id' => $user->id,
            'amount' => 200.00,
        ]);

        $response = $this->actingAs($user)->getJson("/api/slots/{$slot->id}/bids");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Winning bid retrieved successfully',
            'data' => [
                'id' => $winningBid->id,
                'bid_id' => $winningBid->bid_id,
                'user_id' => $winningBid->user_id,
                'amount' => $winningBid->amount,
            ],
        ]);
    }
}
