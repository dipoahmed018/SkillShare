@props(['votes', 'voted'])

<div class="vote">

    <i class="bi bi-arrow-up-square{{ $voted?->vote_type == 'increment' ? '-fill' : ' increment' }} vote-control"></i>

    <div class="votes">
        <span>{{ $votes }}</span><br>
        <span>votes</span>
    </div>

    <i class="bi bi-arrow-down-square{{ $voted?->vote_type == 'decrement' ? '-fill' : ' decrement' }} vote-control"></i>

</div>
