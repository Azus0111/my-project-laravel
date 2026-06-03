<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'title' => "Post Title $i",
                'slug' => "post-title-$i",
                'content' => "This is the content of post $i.",
                'user_id' => rand(1, 10),
            ]);
        }
    }
}
