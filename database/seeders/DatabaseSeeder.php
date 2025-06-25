<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lyrics;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 7 users, one for each of the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $user = User::factory()->create([
                'created_at' => Carbon::now()->subDays($i),
                'last_login_at' => Carbon::now()->subDays($i),
                'password' => Hash::make('password'),
            ]);
            // Create 1 lyric for each user, on the same day
            Lyrics::factory()->create([
                'user_id' => $user->id,
                'created_at' => Carbon::now()->subDays($i),
            ]);
        }
    }
}
