<div class="{{ $post->post_type }}-wrapper row justify-content-center">
    @if ($post->owner == Auth::user()->id)
        <div class="col col-2">
            <form action="{{ route('post.delete', ['post' => $post->id]) }}" method="post">
                @method('delete')
                @csrf
                <input style="width: 100px" type="submit" value="delete" class="m-2 btn btn-danger">
            </form>
            <button style="width: 100px" class="btn m-2 btn-warning {{ $post->post_type }}-edit"
                data-bs-type="edit-answer" data-bs-id="{{ $post->id }}">Edit</button><br>
            @if ($post->post_type == 'answer' && $post->parent->owner == Auth::user()->id)
                <i style="font-size: 30px"
                    class="accept-answer bi bi-check2{{ $post->answerdByMe() ? ' text-success answered' : '' }}"
                    data-post-id="{{ $post->id }}"></i>
            @endif
        </div>
    @else
        <div class="col align-self-center col-1 control" style="font-size: 20px">
            <i class="vote increment bi bi-arrow-up-square{{ $post->voted ? ($post->voted->vote_type == 'increment' ? '-fill' : '') : '' }}  arrow-hover"
                data-vote-type="increment" data-post-id="{{ $post->id }}"></i><br>

            <p class="vote-counter" style="display: inline">{{ $post->voteCount() }}</p><br>

            <i class="vote dicrement bi bi-arrow-down-square{{ $post->voted ? ($post->voted->vote_type == 'decrement' ? '-fill' : '') : '' }}  arrow-hover"
                data-vote-type="decrement" data-post-id="{{ $post->id }}"></i><br>

            @if ($post->post_type == 'answer' && $post->parent->owner == Auth::user()->id)
                <i style="font-size: 30px"
                    class="accept-anwser bi bi-check2{{ $post->answerdByMe() ? ' text-success answered' : '' }}"
                    data-post-id="{{ $post->id }}"></i>
            @endif

        </div>
    @endif
    @if ($post->title)
        <div class="col col-7">
            <h3 class="{{ $post->post_type }}-title">{{ $post->title }}</h3>
        </div>
    @endif
    <div class="col col-9 {{ $post->post_type }}-content">
        {!! $post->content !!}
    </div>
</div>
