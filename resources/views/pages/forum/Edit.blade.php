@extends('../Layout/Layout')

@section('title', 'edit-forum')


@section('body')
    <div class="container-fluid row justify-content-center">
        <form class="form-group col col-md-6" action="{{ route('update.forum', ['forum' => $forum->id]) }}" method="post">
            @method('put')
            @csrf
            <label class="form-label" for="name">Forum Name</label>
            <input class="form-control" type="text" name="name" id="name">
            <div class="row description edit-box">
                <div class="col col-12">
                    <button class="btn btn-primary">block</button>
                    <button class="btn btn-primary">heighlit</button>
                    <button class="btn btn-primary">php</button>
                    <button class="btn btn-primary">list</button>
                </div>
                <div class="col">
                    <textarea name="description" id="description" cols="30" rows="10" required>{{$forum->description}}</textarea>
                </div>
            </div>
        </form>
    </div>
@endsection
