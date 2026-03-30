<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => '50th Reunion Grand Celebration',
                'description' => 'Join us for the biggest celebration of our class! This will be an unforgettable evening with dinner, dancing, and memories.',
                'event_date' => now()->addDays(30)->setHour(19)->setMinute(0)->setSecond(0),
                'location' => 'Grand Ballroom, Hilton Hotel',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Class of 1975 Golf Tournament',
                'description' => 'A friendly golf tournament for all skill levels. Prizes for longest drive, closest to pin, and best score.',
                'event_date' => now()->addDays(15)->setHour(9)->setMinute(0)->setSecond(0),
                'location' => 'Royal Kingston Golf Club',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Memory Lane Photo Exhibition',
                'description' => 'Walk through our 50-year journey with rare photographs and memorabilia from our school days.',
                'event_date' => now()->addDays(7)->setHour(14)->setMinute(0)->setSecond(0),
                'location' => 'School Auditorium',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Family Picnic & BBQ',
                'description' => 'Bring your families for a relaxed afternoon of food, games, and catching up. Kids welcome!',
                'event_date' => now()->addDays(45)->setHour(12)->setMinute(0)->setSecond(0),
                'location' => 'Central Park Pavilion',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Annual General Meeting',
                'description' => 'Important meeting to discuss class affairs, future plans, and elect new committee members.',
                'event_date' => now()->addDays(60)->setHour(10)->setMinute(0)->setSecond(0),
                'location' => 'School Conference Room',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Event::insert($events);
    }
}
