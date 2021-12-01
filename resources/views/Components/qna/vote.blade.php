@props(['votes', 'voted', 'post'])

<div class="vote">

    <i class="bi bi-arrow-up-square{{ $voted?->vote_type == 'increment' ? '-fill' : '' }} vote-control"
        data-vote-type="increment" data-post-id={{ $post->id }}></i>

    <div class="votes">
        <span class="vote-count">{{ $votes }}</span><br>
        <span>votes</span>
    </div>

    <i class="bi bi-arrow-down-square{{ $voted?->vote_type == 'decrement' ? '-fill' : '' }} vote-control"
        data-vote-type="decrement" data-post-id="{{ $post->id }}"></i>

</div>
