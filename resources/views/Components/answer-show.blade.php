<div class="answer row m-2">
    @if ($answer->owner_details->id == Auth::user()->id)
        <div class="col col-1 d-flex flex-column">
            <button class="btn m-2 btn-warning">Edit</button>
            <button class="btn m-2 btn-danger">delete</button>
        </div>
        @else
        <div class="col d-flex align-content-center flex-column col-1 control" style="font-size: 20px">
            <i class="bi bi-arrow-up-square arrow-hover"></i><br>
            {{ $answer->vote }}<br>
            <i class="bi bi-arrow-down-square arrow-hover"></i>
            @if($question->owner_details->id == Auth::user()->id)
            <i class="bi">s</i>
            @endif
        </div>
    @endif
    <div class="col col-10 answer content-box">
        {!! $answer->content !!}
    </div>
</div>
