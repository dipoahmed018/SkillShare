@props(['question'])

<div class="question-card" id="question-{{ $question->id }}">

    <div class="status">
        <div class="votes-count">
            <span>{{ $question->allVotes->count() }}</span>
            <span>votes</span>
        </div>
        <div class="answers-count">
            <span>0</span>
            <span>answers</span>
        </div>
    </div>
    <div class="contents">
        <h6 class="title">
            <a href="/show/question/{{ $question->id }}">
                {{ $question->title }}
            </a>
        </h6>
    </div>
    <div class="info">
        <span class="created-at">Created at {{ $question->created_at->diffForHumans() }}</span>
        <span class="owner">by {{ $question->ownerDetails->name }}</span>
        @if ($question->editable)
            <div class="question-control">
                <button class="delete-question" data-question-id="{{ $question->id }}">Delete</button>
                <button class="edit">Edit<a href="#"></a></button>
            </div>
        @endif
    </div>
</div>
