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
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            CategorySeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            FollowUserSeeder::class,
            FollowRequestSeeder::class,
            GroupSeeder::class,
            GroupMemberSeeder::class,
            LongContentSeeder::class,
            NotifySeeder::class,
            LikeSeeder::class,
        ]);
    }
}
