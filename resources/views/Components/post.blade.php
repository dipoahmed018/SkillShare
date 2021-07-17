<div class="post">
    @dump($post)
    <h3 class="caption">{{ $post->title }}</h3>
    <div class="gallery row">
        {{$post->id}}
        {{ $post->content }}
    </div>
    <div class="interect d-flex">
        <div id="like-{{ $post->id }}"
            data-method={{ $post->voted(Auth::user()->id) ? 'decrement' : 'increment' }}>
            <i class="bi bi-hand-thumbs-up"></i>
            <span>{{ $post->allVote->count() }}</span>
        </div>
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
        let target = document.getElementById('like-' + @json($post->id))
        let type = target.getAttribute('data-method')
        window.axios({
            url: `/` + @json($post->id) + `/post/update/vote`,
            method: 'post',
            data: {
                method: type,
            }
        }).then(
            (res) => {
                if (type == 'increment') {
                    target.lastElementChild.innerText = parseInt(target.lastElementChild.innerText) + 1
                    target.setAttribute('data-method', 'decrement')
                    target.firstElementChild.classList.remove('bi-hand-thumbs-up')
                    target.firstElementChild.classList.add('bi-hand-thumbs-up-fill')
                }
                if (type == 'decrement') {
                    target.lastElementChild.innerText = parseInt(target.lastElementChild.innerText) - 1
                    target.setAttribute('data-method', 'increment')
                    target.firstElementChild.classList.remove('bi-hand-thumbs-up-fill')
                    target.firstElementChild.classList.add('bi-hand-thumbs-up')
                }
            },
            (err) => {
                console.log(err)
            }
        )
    })
</script>
