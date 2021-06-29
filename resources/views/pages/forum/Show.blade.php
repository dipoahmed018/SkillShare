@extends('../Layout/Layout')

@section('title', 'forum')
@section('body')
    <div class="modal fade" id="create" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="moda-type" class="modal-title">create</h5>
                    <button id="close-modal" type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('forum.post.create', ['forum' => $forum->id]) }}" id="create-post-question" method="post">
                        @csrf
                        <div id="edit-box">

                        </div>
                        <input type="submit" value="create">
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                    <div>
                        @foreach ($forum->description['blocks'] as $block)
                            @if ($block['type'] == 'paragraph')
                                <p>{!! $block['data']['text'] !!}</p>
                            @endif
                        @endforeach
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
                    <button id="create-post" class="btn btn-primary">create post</button>
                    <button id="create-question" class="btn btn-primary">create question</button>
                </div>
                <div class="col posts">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const forum = @json($forum);
    </script>
    <script src={{ asset('js/forum-show.js') }}></script>
@endsection
