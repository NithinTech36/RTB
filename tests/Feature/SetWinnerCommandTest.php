<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Bids;
use App\Models\Slots;
use App\Models\User;

class SetWinnerCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     */
 // use RefreshDatabase;
    public function testSetWinnerCommand()
    {
        //create slots and bids for testing
        $user = User::factory()->create();
        $slot = Slots::factory()->create(['start_time' => now(), 'end_time' => now()->addMinutes(1), 'status' => 'open']);
        Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 150]);
        Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 200]);

        //update the slot status to closed
        $closedSlot = Slots::factory()->create(['start_time' => now()->subMinutes(2), 'end_time' => now()->subMinutes(1), 'status' => 'closed']);
        Bids::factory()->create(['slot_id' => $closedSlot->id, 'user_id' => $user->id, 'amount' => 300]);
        Bids::factory()->create(['slot_id' => $closedSlot->id, 'user_id' => $user->id, 'amount' => 400]);
        Bids::factory()->create(['slot_id' => $closedSlot->id, 'user_id' => $user->id, 'amount' => 400]);
        $this->assertDatabaseHas('slots', [
            'id' => $closedSlot->id,
            'status' => 'awarded' // Assuming the highest bid is the winner
        ]);
        //update the slot status to upcoming
        $upcomingSlot = Slots::factory()->create(['start_time' => now()->addMinutes(2), 'end_time' => now()->addMinutes(3), 'status' => 'upcoming']);
        Bids::factory()->create(['slot_id' => $upcomingSlot->id, 'user_id' => $user->id, 'amount' => 500]);
        Bids::factory()->create(['slot_id' => $upcomingSlot->id, 'user_id' => $user->id, 'amount' => 600]);


        $this->artisan('app:set-winner')->assertSuccessful();
    }
}
