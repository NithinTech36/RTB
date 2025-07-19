<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Bids;
use App\Models\Slots;
use App\Models\User;

class BidsTest extends TestCase
{
 //create a bid for a slot
    use RefreshDatabase;

    public function test_create_bid_for_open_slot()
    {
        $user = User::factory()->create(); // Create a user for testing
        $slot = Slots::factory()->create(['status' => 'open']); // Create a slot for testing

        $response = $this->actingAs($user)->postJson('/api/bids', [
            'slot_id' => $slot->id,
            'amount' => 100,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                'id',
                'slot_id',
                'user_id',
                'amount',
             ]
             ]
        ]);
    }
    //create a bid for a closed slot
    public function test_create_bid_for_closed_slot()
    {
        $user = User::factory()->create(); // Create a user for testing
        $slot = Slots::factory()->create(['status' => 'closed']); // Create a slot for testing

        $response = $this->actingAs($user)->postJson('/api/bids', [
            'slot_id' => $slot->id,
            'amount' => 100,
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
            'data' => [],
        ]);
    }
    //create a bid for an awarded slot
    public function test_create_bid_for_awarded_slot()
    {
        $user = User::factory()->create(); // Create a user for testing
        $slot = Slots::factory()->create(['status' => 'awarded']); // Create a slot for testing

        $response = $this->actingAs($user)->postJson('/api/bids', [
            'slot_id' => $slot->id,
            'amount' => 100,
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
            'data' => [],
        ]);
    }
    //list all bids for a open slot
    public function test_list_all_bids_for_slot()
    {
        $user = User::factory()->create(); // Create a user for testing
        $slot = Slots::factory()->create(['status' => 'open']); // Create a slot for testing
        $bid1 = Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 100]);
        $bid2 = Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 200]);

        $response = $this->actingAs($user)->getJson("/api/bids/{$slot->id}?is_bidding=true");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'slot_id',
                    'user_id',
                    'amount',
                ],
            ],
        ]);
    }
    //list all bids for a closed slot
    public function test_list_all_bids_for_closed_slot()
    {
        $user = User::factory()->create(); // Create a user for testing
        $slot = Slots::factory()->create(['status' => 'closed']); // Create a slot for testing

        $response = $this->actingAs($user)->getJson("/api/bids/{$slot->id}?is_bidding=true");

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
            'data' => [],
        ]);
    }
    //list all bids without is_bidding parameter
    public function test_list_all_bids_without_is_bidding_parameter()
    {
        $user = User::factory()->create(); // Create a user for testing
        $slot = Slots::factory()->create(['status' => 'open']); // Create a slot for testing
        $bid1 = Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 100]);
        $bid2 = Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 200]);

        $response = $this->actingAs($user)->getJson("/api/bids/{$slot->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'slot_id',
                    'user_id',
                    'amount',
                ],
            ],
        ]);
    }
}
