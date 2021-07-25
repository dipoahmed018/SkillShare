<div id="{{ $comment->id }}-comment-wrapper" class="row">
    <div class="col col-1 comment-owner">
        owner
    </div>
    <div class="content col col-11">
        {!! $comment->content !!}
    </div>
    <div class="control d-flex col-12 ">
        <div id="like-{{ $comment->id }}"
            data-method={{ $comment->voted(Auth::user()->id) ? 'decrement' : 'increment' }}>
            <i class="bi bi-hand-thumbs-up"></i>
            <span>{{ $comment->allVote->count() }}</span>
        </div>
        <button class="create-comment" data-bs-action="create" data-commentable-id="{{ $comment->id }}" data-comment-type="reply">reply</button>
        <button class="show-replies">show replies</button>
        @can('update', $comment)
            <button class="btn btn-warning edit-comment" data-bs-action="update" data-comment-id={{ $comment->id }}>edit</button>
            <button class="btn btn-danger delete-comment" data-comment-id={{ $comment->id }}>delete</button>
        @endcan
    </div>
    <div id="{{ $comment->id }}-comment-box" class="reply-box m-5">
        @foreach ($comment->reply as $reply)
            <x-comment :comment="$reply"></x-comment>
        @endforeach
    </div>
</div>

<script>
    document.getElementById('like-' + @json($comment->id)).addEventListener('click', (e) => {
        let target = document.getElementById('like-' + @json($comment->id))
        let type = target.getAttribute('data-method')
        window.axios({
            url: `/` + @json($comment->id) + `/comment/update/vote`,
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
