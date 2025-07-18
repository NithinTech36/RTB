<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Jobs\SetWinnerJob;
use App\Models\Slots;
use App\Models\User;
use App\Models\Bids;

class SetWinnerJobTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    //test the SetWinnerJob
   //use RefreshDatabase;
    public function test_example(): void
    {

        $user = User::factory()->create();
        $slot = Slots::factory()->create(['status' => 'closed']);   
        $bid1 = Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 150]);
        $bid2 = Bids::factory()->create(['slot_id' => $slot->id, 'user_id' => $user->id, 'amount' => 200]);
        dispatch(new SetWinnerJob($slot->id))->afterCommit();
         $this->artisan('queue:work --once')->assertSuccessful();
       
        $this->assertDatabaseHas('slots', [
            'id' => $slot->id,
            'status' => 'awarded' // Assuming the highest bid is the winner
        ]);
        $this->assertTrue(true); // Replace with actual assertions to verify the job's behavior.
        // Additional assertions can be added here to verify the job's behavior.
    }


}
