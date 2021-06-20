@extends('../Layout/Layout')

@section('title', 'forum')
@section('body')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col col-md-6 col-sm-12  thumblins">
                <img src={{ $forum->forum_type->thumblin ? $forum->forum_type->thumblin->file_link : asset('default/Default.jpg') }}
                    alt="Forum Thumblin" style="max-width:100%">
            </div>
            <div class="d-block"></div>
            <div class="col m-2 col-sm-2 forum-details">
                <div class="details">
                    <h3 class="name">{{ $forum->name }}</h3>
                    <p class="description">{{$forum->description}}</p>
                </div>
                <div class="edit">
                    <a href={{ route('edit.forum', ["forum" => $forum->id]) }} class="btn btn-warning">Edit</a>
                </div>
            </div>
            <div class="d-block"></div>
            <div class="col posts">
                <div class="post-control d-flex justify-content-center gap-4">
                    <button class="btn btn-primary">create post</button>
                    <button class="btn btn-primary">create question</button>
                </div>
                <div class="col posts">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src={{ asset('js/forum-show.js') }}></script>
@endsection
