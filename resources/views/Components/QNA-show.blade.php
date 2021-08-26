<div class="{{$post->post_type}}-wrapper row justify-content-center">
    @if ($post->owner == Auth::user()->id)
    <div class="col col-2">
        <form action="{{route('post.delete', ['post' => $post->id])}}" method="post">
            @method('delete')
            @csrf
            <input style="width: 100px" type="submit" value="delete" class="m-2 btn btn-danger">
        </form>
        <button style="width: 100px" class="btn m-2 btn-warning {{$post->post_type}}-edit" data-bs-type="edit-answer" data-bs-id="{{$post->id}}">Edit</button>
    </div>
    @else
    <div class="col align-self-center col-1 control" style="font-size: 20px">
        <i class="bi bi-arrow-up-square arrow-hover"></i><br>
            {{$post->voteCount()}}<br>
        <i class="bi bi-arrow-down-square arrow-hover"></i><br>
        <i class="bi bi-check2"></i>
    </div>
    @endif
    @if ($post->title)
    <div class="col col-7">
        <h3 class="{{$post->post_type}}-title">{{ $post->title }}</h3>
    </div>
    @endif
    <div class="col col-9 {{$post->post_type}}-content">
        {!! $post->content !!}
    </div>
</div>