<div class="post">
    <h3 class="caption">{{ $post->title }}</h3>
    <div class="gallery row">
        {{$post->id}}
        {{ $post->content }}
    </div>
    <div class="interect d-flex ">
        <div id="like-{{ $post->id }}"
            data-method={{ $post->voted(Auth::user()->id) ? 'decrement' : 'increment' }}>
            <i class="bi bi-hand-thumbs-up"></i>
            <span>{{ $post->allVote->count() }}</span>
        </div>
        <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#create-comment-modal" data-comment-type="parent" data-commentable-id={{$post->id}}>comment</button>
    </div>
    <div class="comment-box">
        @foreach ($post->comments as $comment)
            <x-comment :comment="$comment"></x-comment>
        @endforeach
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
                console.log(res)
                if (type == 'increment') {
                    target.setAttribute('data-method', 'decrement')
                    target.lastElementChild.innerText = res.data
                    target.firstElementChild.classList.remove('bi-hand-thumbs-up')
                    target.firstElementChild.classList.add('bi-hand-thumbs-up-fill')
                }
                if (type == 'decrement') {
                    target.setAttribute('data-method', 'increment')
                    target.lastElementChild.innerText = res.data
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
