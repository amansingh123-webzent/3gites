<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poll = Poll::create([
            'question' => 'What is your favorite memory from our school days?',
            'is_published' => true,
            'is_closed' => false,
            'created_by' => 1, // Admin user
        ]);

        PollOption::create([
            'poll_id' => $poll->id,
            'option_text' => 'Annual Sports Day',
        ]);

        PollOption::create([
            'poll_id' => $poll->id,
            'option_text' => 'Cultural Festival',
        ]);

        PollOption::create([
            'poll_id' => $poll->id,
            'option_text' => 'Class Picnics',
        ]);

        PollOption::create([
            'poll_id' => $poll->id,
            'option_text' => 'Final Exam Preparation',
        ]);
    }
}
