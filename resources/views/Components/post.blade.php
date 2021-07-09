<div class="post">
    <h3 class="caption">{{ $post->title }}</h3>
    <div class="gallery row">
        {{ $post->content }}
    </div>
    <div class="interect">
        <i class="like-button" id="like-{{ $post->id }}" data-method="increment">like</i>
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

<script>
    document.getElementById('like-' + @json($post->id)).addEventListener('click', (e) => {
        window.axios({
            url: `/` + @json($post->id) + `/update/vote`,
            method: 'post',
            data: {
                method: e.target.getAttribute('data-method'),
            }
        }).then(
            (res) => {
                console.log(res)
            },
            (err) => {
                console.log(err)
            }
        )
    })
</script>
