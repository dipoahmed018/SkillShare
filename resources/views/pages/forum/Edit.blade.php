@extends('../Layout/Layout')

@section('title', 'edit-forum')
@section('headers')
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
@endsection
@section('body')
    <form id="forum-edit" action="{{ route('update.forum', ['forum' => $forum->id]) }}" method="post">
        @method('put')
        @csrf
        <div class="cover-wrapper">
            <label for="cover">
                <img src={{ $forum->cover?->file_link ?? asset('images/default_cover.jpg') }} alt="Forum cover">
                <div class="overlay">
                    <i class="bi bi-image-alt"></i>
                    <p>Tap here to update your cover</p>
                </div>
            </label>
            <input accept=".png, .jpg, .jpeg" type="file" name="cover" id="cover">
        </div>
        <div class="title-wrapper">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{$forum->name}}">
            @error('name')
                <div class="error-box">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="description-wrapper">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="10">{!! $forum->description !!}</textarea>
            @error('description')
                {{ $message }}
            @enderror
        </div>
        <button type="submit">Save</button>
    </form>
@endsection
@section('scripts')
    <script>
        let forum_details = @json($forum);
    </script>
    <script src="{{ asset('./js/ckeditor5/build/ckeditor.js') }}"></script>
    <script src={{ asset('js/forum_edit.js') }}></script>
@endsection
