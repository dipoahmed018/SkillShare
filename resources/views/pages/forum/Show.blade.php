@extends('../Layout/Layout')

@section('title', 'forum')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
@endsection
@section('tamplates')
    {{-- @include('Layout.Tamplate.comment-tamplate') --}}
@endsection

@section('body')
<div id="popup_box" class="hide"></div>
<div class="forum">
        <div class="banner">
            <img src={{ $forum->thumbnail?->file_link  ?? asset('images/default_banner.jpg') }}
            alt="Forum thumbnail" style="max-width:100%">
        </div>
        <div class="control">
            <button>Ask question</button>
            <button>Students</button>
            <button>About</button>
        </div>
        <div class="about">
            
        </div>
        <div class="questions">

        </div>
</div>
    {{-- @include('Components.CommentCreate')
    <div class="modal fade" id="create" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="moda-type" class="modal-title">create</h5>
                    <button id="close-modal" type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="forum-group hide"
                        action="{{ route('post.create', ['postable' => $forum->id, 'type' => 'post']) }}"
                        id="create-post" method="post">
                        @csrf
                        <label class="form-label" for="title">caption</label>
                        <input class="form-control" type="text" name="title" id="title" required min="5">
                        <div class="hide image-u-progress">
                            <p>image uploading plase wait</p>
                        </div>
                        <div class="image-u-box">
                            <label class="btn btn-primary" for="post-image">Upload image</label>
                            <input accept=".png, .jpeg, .jpg" type="file" class="one-click-upload" name="post-image"
                                id="post-image" multiple>
                        </div>
                        <input class="btn btn-success" type="submit" value="create">
                    </form>
                    <form class="form-group hide"
                        action="{{ route('post.create', ['postable' => $forum->id, 'type' => 'question']) }}"
                        id="create-question" method="post">
                        @csrf
                        <label for="title">Title</label>
                        <input class="form-control" type="text" name="title" id="title" required min="10"><br>
                        <textarea name="content" id="content" cols="30" rows="10"></textarea>
                        <input type="submit" value="create">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col col-md-6 col-sm-12  thumbnails">
                <img src={{ $forum->forumable->thumbnail ? $forum->forumable->thumbnail->file_link : asset('default/Default.jpg') }}
                    alt="Forum thumbnail" style="max-width:100%">
            </div>
            <div class="d-block"></div>
            <div class="col m-2 col-sm-2 forum-details">
                <div class="details">
                    <h3 class="name">{{ $forum->name }}</h3>
                    <div>
                        {!! $forum->description !!}
                    </div>
                </div>
                @can('update', $forum)
                    <div class="edit">
                        <a href={{ route('edit.forum', ['forum' => $forum->id]) }} class="btn btn-warning">Edit</a>
                    </div>
                @endcan
            </div>
            <div class="d-block"></div>
            <div class="col posts">
                <div class="post-control d-flex justify-content-center gap-4">
                    <button id="create-post-button" class="btn btn-primary">create post</button>
                    <button id="create-question-button" class="btn btn-primary">create question</button>
                </div>
                <div class="controller">
                    <button id="show-posts" class="btn btn-primary">Posts</button>
                    <button id="show-questions" class="btn btn-primary">Questions</button>
                </div>
                <div class="col questions-box">
                    @foreach ($forum->questions as $question)
                        <div class="question-wrapper row">
                            <div class="col align-self-center col-1 control" style="font-size: 20px">
                                {{ $question->vote }}<br>
                            </div>
                            <div class="col col-11 title">
                                <h3><a href="/show/question/{{ $question->id }}">{{ $question->title }}</a></h3>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-11 posts-box hide">
                    @foreach ($forum->posts as $post)
                        <x-post :post="$post"></x-post>
                    @endforeach
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <script src="{{ asset('js/ckeditor5/build/ckeditor.js') }}"></script>
    <script>
        const forum = @json($forum);
    </script>
    <script src={{ asset('js/forum_show.js') }}></script>
@endsection
