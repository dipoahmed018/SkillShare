@extends('../Layout/Layout')

@section('title', 'course')

@section('body')
    <div id="popup_box">

    </div>

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

                <p><b>Published By: </b> {{ $course->owner_details->name }}</p>
            </div>
            <div class="introduction row justify-content-center">
                <div class="introduction-video col col-12 col-md-6 ">
                    @if ($course->introduction)
                        <video id="introduction-video" width="100%" src="{{ $course->introduction }}"></video>
                    @endif
                </div>
                <div class="d-block"></div>
                <div id="introduction-upload-box" class="col col-md-6">
                    @can('update', $course)
                        <input accept=".mp4" required class="add-introduction one-click-upload" type="file" name="introduction"
                            id="introduction">
                        <label for="introduction"
                            class="add-button btn btn-primary mb-4">{{ $course->introduction ? 'Change Introduction' : 'Add Introduction' }}</label>
                        <div id="introduction-error"></div>
                    @endcan
                </div>
                <div id="introduction-progress-box" class="hide col col-md-5">
                    <div class="progress">
                        <div id="introduction-progress-bar" class="progress-bar bg-success" role="progressbar"
                            style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <button id="introduction-up-cancel" class="btn btn-danger">cancel</button>
                    <button class="pause" id="introduction-up-pause" class="btn btn-primary">pause</button>
                </div>
            </div>
            <div class="tutorial-upload row justify-content-center mb-2">

                @can('update', $course)
                    <div id="tutorial-upload-box" class="upload-tutorial col col-md-2 mt-2">
                        <input required accept=".mp4" type="file" name="tutorial" class="add-vide one-click-upload"
                            id="tutorial">
                        <label class="add-button btn btn-primary" for="tutorial">Add Tutorial</label>
                        <div id="tutorial-error"></div>
                    </div>
                    <div id="tutorial-progress-box" class="hide col col-md-5">
                        <div class="progress">
                            <div id="tutorial-progress-bar" class="progress-bar bg-success" role="progressbar"
                                style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <button id="tutorial-up-cancel" class="btn btn-danger">cancel</button>
                        <button class="pause" id="tutorial-up-pause" class="btn btn-danger">pause</button>
                    </div>
                @endcan
            </div>
            <div class="tutorial-videos container col-md-12">
                @if (count($course->tutorials) < 1)
                    <h1> course does not have any tutorial</h1>
                @endif
                @foreach ($course->tutorials as $tutorial)
                    <div id="{{ $tutorial->id }}" draggable="true" class="tutorial-card row">
                        <div class="details col-sm-10">
                            <h3 id="title" class="title">{{ $tutorial->title }}</h3>
                            <span>created_at</span>
                        </div>

                        <div class="edit col">
                            @can('delete', $course)
                                <form
                                    action={{ route('delete.course.tutorial', ['course' => $course->id, 'tutorial' => $tutorial->id]) }}
                                    method="post">
                                    @method('delete')
                                    @csrf
                                    <input class="btn btn-danger" type="submit" value="Delete">
                                </form>
                            @endcan
                            @can('update', $course)
                                <a class="btn btn-warning" href="/course/{{ $course->id }}/tutorial/{{ $tutorial->id }}">Edit</a>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@section('scripts')

    {{-- variable injection --}}
    @if ($course)
        <script>
            var csrf = document.head.querySelector("meta[name='_token']").content;
            var course = @json($course)

        </script>
    @endif

    <script src={{ asset('js/course-show.js') }}></script>
@endsection
