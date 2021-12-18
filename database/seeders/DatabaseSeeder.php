<?php

namespace Database\Seeders;

use App\Models\Catagory;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Referrel;
use App\Models\Review;
use App\Models\Tuition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        Course::factory()->count(10)->create();

        Post::factory()->count(80)->create();
        Comment::factory()->count(20)->parent()->create();
        Comment::factory()->count(50)->reply()->create();
        Notification::factory()->count(50)->create();
        Review::factory()->count(30)->course()->create();
        Review::factory()->count(30)->reply()->create();
        Catagory::factory()->count(20)->create();
    }
}
