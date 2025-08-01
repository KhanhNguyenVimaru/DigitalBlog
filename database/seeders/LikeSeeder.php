<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use App\Models\like;


class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả user và post hiện có trong database
        $users = User::all();
        $posts = Post::all();

        // Kiểm tra nếu không có dữ liệu thì không seed
        if ($users->count() === 0 || $posts->count() === 0) {
            $this->command->warn('⚠️ Không có User hoặc Post trong database. Vui lòng seed User và Post trước.');
            return;
        }

        $this->command->info('🌱 Bắt đầu tạo like...');

        // Tạo 50 comment
        like::factory()->count(50)->create()->each(function ($like) use ($users, $posts) {
            $like->update([
                'user_id' => $users->random()->id,
                'post_id' => $posts->random()->id,
            ]);
        });

        $this->command->info('✅ Đã tạo thành công ' . like::count() . ' like!');
    }
}
