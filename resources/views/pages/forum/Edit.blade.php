@extends('../Layout/Layout')

@section('title', 'edit-forum')
@section('headers')
@endsection
@section('body')
    @can('update', $forum)
        <div class="container-fluid row justify-content-center">
            <form id="forum" class="form-group col col-md-6" action="{{ route('update.forum', ['forum' => $forum->id]) }}"
                method="post">
                @method('put')
                @csrf
                <label class="form-label" for="name">Forum Name</label>
                <input class="form-control" type="text" name="name" value="{{$forum->name}}" id="name">
                @error('name')
                    <div class="error-box">
                        {{ $message }}
                    </div>
                @enderror
                <input type="hidden" name="description" value="">
                <div class="col description edit-box">
                    <p>description</p>
                    <div id="editorjs">

                    </div>
                    @error('description')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <input type="submit" value="save">
            </form>
        </div>
    @endcan
    @cannot('update', $forum)
        <div>
            <h1>
                you are not allowed to edit this forum
            </h1>
        </div>
    @endcannot
@endsection
@section('scripts')
    <script>
        let forum_details = @json($forum);
    </script>
    <script src={{ asset('./js/forum_edit.js') }}></script>
@endsection
