@extends('../Layout/Layout')

@section('title', 'course')

@section('body')
    @error('auth')
        {{ $message }}
    @enderror
    @if (!$course)
        <h1>the course you are searching for is not available</h1>
    @endif
    @if ($course)
        <div class="container-fluid">
            <div class="details row">
                <div class="title col col-md-10">
                    <h3>{{ $course->title }}</h3>
                </div>
                <div class="description col col-md-10">
                    <p>{{ $course->description }}</p>
                </div>
            </div>
            <div class="owner-details row ">

                <p><b>Published By: </b> {{ $course->owner->name }}</p>
            </div>
            <div class="introduction row justify-content-center">
                <div class="introduction-video col col-12 col-md-6 ">
                    @if ($course->introduction)
                        <video width="100%" src="{{ $course->introduction }}"></video>
                    @endif
                </div>
                <div class="d-lock"></div>
                <div class="customize-box col-7 col-md-4">
                    @if ($course->owner->id == Auth::user()->id)
                        <form action={{ route('update.course.introduction', [$course->id]) }} method="post"
                            enctype="multipart/form-data">

                            @csrf
                            <input accept=".mp4" class="form-control" type="file" name="introduction" id="introduction"
                                required><br>
                            <input class="form-control" type="submit"
                                value={{ $course->introduction ? 'change intruduction' : 'add intruduction' }}>
                        </form>
                    @endif
                </div>
            </div>
            <div class="tutorial row justify-content-center">

                @if ($course->owner->id === Auth::user()->id)
                    <div class="upload-tutorial col col-md-2 mt-2">
                        <input required accept=".mp4" type="file" name="tutorial" class="add-video" id="tutorial">
                        <label class="add-button btn btn-primary" for="tutorial">Add Tutorial</label>
                        <div id="error-box" class="error-box" id="error-tutorial">

                        </div>
                    </div>
                @endif
                <div class="tutorial-videos col col-md-12">
                    @if (count($course->tutorials) < 1)
                        <h1> course does not have any tutorial</h1>
                    @endif
                    @foreach ($course->tutorials as $tutorial)
                        <div class="tutorial--box row">
                            <div class="tutorial-details col ">
                                <h1>{{ $tutorial->title }}</h1>
                            </div>
                            @if ($course->owner->id === Auth::user()->id)
                                <div class="tutorial-control col col-md-2">
                                    <i>hello</i>
                                    <i>hello</i>
                                    <i>delete</i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    @endsection
    
    
    @section('scripts')
    <script>
        
        var csrf = document.head.querySelector("meta[name='_token']").content;
        var course = @json($course)
    </script>
    <script src={{ asset('js/course-show.js') }}></script>
@endsection
