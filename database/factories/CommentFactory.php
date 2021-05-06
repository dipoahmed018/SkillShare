<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Forum;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function configure()
    {
        return $this->afterCreating(function (Comment $comment) {
            if ($comment->commentable_type !== 'reply') {
                $post =  $comment->parent;
                if ($post->answer == null) {
                    $post->answer = $comment->id;
                    $post->save();
                }
            }
        });
    }
    public function definition()
    {
        $post = Post::all()->random();
        $owner_type = null;
        $owner_type_id = null;
        $owner = collect(Forum::find($post->postable_id)->members)->random()->id;
        $owner_type = $this->faker->randomElement(['parent', 'answer']);
        $owner_type_id = $post->id;
        return [
            'content' => $this->faker->paragraph(1),
            'owner' => $owner,
            'vote' => rand(1, 500),
            'commentable_id' => $owner_type_id,
            'commentable_type' => $owner_type,
        ];
    }
    public function reply()
    {
        return $this->state(function (array $attributes)
        {
            $owner = $attributes['owner'];
            $forum =collect( DB::table('forum')
                ->join('tuition_students','tuition_id', '=','forum.forumable_id')
                ->join('course_students','course_id','=','forum.forumable_id')
                ->whereRaw('tuition_students.student_id = ? OR course_students.student_id = ?',[$owner,$owner])
                ->get() )->random();
            $comments = DB::table('comment')
                ->joinSub('SELECT `post`.id WHERE `post.postable_id` = ' . $forum->id,'post_id',function($join){
                    $join->on('comment.commentable_id','=','post_id.id')
                })
                ->whereIn('comment.commentable_type',['parent','answer']);


           return [
               'commentable_id' => ,
               'commentable_type' => 'reply',
           ] 
        });
    }
}
