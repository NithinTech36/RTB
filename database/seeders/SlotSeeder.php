<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slots;

class SlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slots::factory()->count(15)->create();
        //seed slots table with multiple datas

        // Slots::insert([

        //         [
        //             'name' => 'Sample Slot',
        //             'start_time' => now()->addHours(1)->toTimeString(),
        //             'end_time' => now()->addHours(2)->toTimeString(),
        //             'status' => 'Upcoming',
        //             'price' => 100.00,
        //         ],
        //         [
        //             'name' => 'Sample Slot 2',
        //             'start_time' => now()->addHours(3)->toTimeString(),
        //             'end_time' => now()->addHours(4)->toTimeString(),
        //             'status' => 'Upcoming',
        //             'price' => 150.00,  
        //         ],
        //         // Add more slots as needed
        //         [
        //             'name' => 'Sample Slot 3',
        //             'start_time' => now()->addHours(5)->toTimeString(),
        //             'end_time' => now()->addHours(6)->toTimeString(),
        //             'status' => 'Upcoming',
        //             'price' => 200.00,
        //         ],
        //         // Add more slots as needed
        //         [
        //             'name' => 'Sample Slot 4',
        //             'start_time' => now()->addHours(7)->toTimeString(),
        //             'end_time' => now()->addHours(8)->toTimeString(),
        //             'status' => 'Upcoming',
        //             'price' => 250.00,
        //         ],
        //     ]);
    }
}
