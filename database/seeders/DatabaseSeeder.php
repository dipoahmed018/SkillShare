<?php

namespace Database\Seeders;

use App\Models\Catagory;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use App\Models\Group;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Price;
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('course_students')->truncate();
        DB::table('tuition_students')->truncate();
        DB::table('catagoryable')->truncate();
        DB::table('group_member')->truncate();
        User::truncate();
        Tuition::truncate();
        Post::truncate();
        Forum::truncate();
        Comment::truncate();
        Price::truncate();
        Referrel::truncate();
        Group::truncate();
        Catagory::truncate();
        Notification::truncate();
        Review::truncate();
        Message::truncate();
        Course::truncate();

        User::factory()->count(30)->create();
        Tuition::factory()->count(10)->create();
        Course::factory()->count(10)->create();
        Post::factory()->count(50)->create();
        Comment::factory()->count(200)->create();
    }
}
