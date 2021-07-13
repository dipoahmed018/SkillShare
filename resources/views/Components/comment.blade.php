<div>
    <div class="comment row">
        <div class="col col-1">
            owner
        </div>
        <div class="content col col-11">
            {{ $comment->content }}
        </div>
        <div class="control d-flex col-12 ">
            <div id="like-{{ $comment->id }}"
                data-method={{ $comment->voted(Auth::user()->id) ? 'decrement' : 'increment' }}>
                <i class="bi bi-hand-thumbs-up"></i>
                <span>{{ $comment->allVote->count() }}</span>
            </div>
            <button>reply</button>
        </div>
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
