<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::firstOrCreate(
            ['slug' => 'laravel-bengaluru-nov-2025'],
            [
                'name' => 'Laravel Bengaluru Nov 2025',
                'description' => 'Laravel community meetup in Bengaluru, November 2025',
                'location' => 'Bengaluru, India',
                'starts_at' => '2025-11-22 09:00:00',
                'ends_at' => '2025-11-22 18:00:00',
                'is_active' => true,
            ]
        );
    }
}
