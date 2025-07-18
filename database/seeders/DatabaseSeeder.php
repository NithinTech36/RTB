<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
  
    // For example, you can call the SlotSeeder to seed the slots table.

    public function run(): void
    {
        // User::factory(10)->create();

        // Call the SlotSeeder to seed the slots table
        $this->call(SlotSeeder::class);
  
    }
}
