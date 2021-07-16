<div class="question-wrapper row justify-content-center">
    @if ($question->owner_details->id == Auth::user()->id)
    <div class="col col-2">
        <form action="{{route('post.delete', ['post' => $question->id])}}" method="post">
            @method('delete')
            @csrf
            <input style="width: 100px" type="submit" value="delete" class="m-2 btn btn-danger">
        </form>
        <button style="width: 100px" id="edit-question" class="btn m-2 btn-warning">Edit</button>
    </div>
    @else
    <div class="col align-self-center col-1 control" style="font-size: 20px">
        <i class="bi bi-arrow-up-square arrow-hover"></i><br>
            {{$question->vote}}<br>
        <i class="bi bi-arrow-down-square arrow-hover"></i>
    </div>
    @endif
    <div class="col col-7 title">
        <h3><a href="/show/question/{{ $question->id }}">{{ $question->title }}</a></h3>
    </div>
    <div class="col col-9 question content-box">
        {!! $question->content !!}
    </div>
</div>