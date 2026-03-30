<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Database\Seeder;

class RsvpSeeder extends Seeder
{
    public function run(): void
{
    // Clear existing RSVPs to avoid conflicts
    Rsvp::query()->delete();
    
    // Get some active members and events
    $users = User::where('member_status', 'active')
        ->take(10)
        ->get();
    
    $events = Event::all();

    // Create RSVPs for the first few events
    foreach ($events->take(3) as $event) {
        foreach ($users->take(5) as $user) {
            // Randomly assign attending status (70% attending)
            $status = rand(1, 10) <= 7 ? 'attending' : (rand(1, 2) <= 1 ? 'maybe' : 'not_attending');
            
            Rsvp::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
}
