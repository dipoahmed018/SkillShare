<div>
    <div class="post">
        <h3 class="caption">{{ $post->title }}</h3>
        <div class="gallery row">
            {{ $post->content }}
        </div>
        <div class="interect">
            <i>like</i>
            <i>comments</i>
        </div>
        <div class="comment-box">
            @foreach ($post->comments as $comment)
                <x-comment :comment="$comment"></x-comment>
            @endforeach
            <div class="comment_create">
                <form action="" method="post">
                    @csrf
                    <input type="text" name="content" id="comment_content">
                    <input type="button" value="comment">
                </form>
            </div>
        </div>
    </div>
</div>
