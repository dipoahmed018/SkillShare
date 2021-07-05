<div class="question-wrapper row justify-content-center">
    @if ($question->owner_details->id == Auth::user()->id)
    <div class="col col-1 d-flex flex-column">
        <button class="btn m-2 btn-warning">Edit</button>
        <button class="btn m-2 btn-danger">delete</button>
    </div>
    @else
    <div class="col align-self-center col-1 control" style="font-size: 20px">
        <i class="bi bi-arrow-up-square"></i><br>
            {{$question->vote}}<br>
        <i class="bi bi-arrow-down-square"></i>
    </div>
    @endif
    <div class="col col-7 title">
        <h3><a href="/show/question/{{ $question->id }}">{{ $question->title }}</a></h3>
    </div>
    <div class="col col-9 question">
        {!! $question->content !!}
    </div>
</div>