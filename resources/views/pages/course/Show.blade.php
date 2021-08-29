@extends('../Layout/Layout')

@section('title', 'course')

@section('body')

    <div class="modal fade" id="tutorial-video" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">watch tutorial</h5>
                    <button id="close-modal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row video-control-box">
                            <div class="col video-box col-12">
                                <video controls id="video-frame" width="100%"></video>
                            </div>
                            <div class="col control-box">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i></button>
                                <button class="btn btn-primary"><i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="popup_box">

    </div>

    @if (!$course)
        <h1>the course you are searching for is not available</h1>
    @endif
    @if ($course)
        <div class="container-fluid">
            <div class="details row">
                @if ($course->thumblin)
                <div class="col thumblin">
                    <img src="{{$course->thumblin->file_link}}" alt="course thumblin">
                </div>
                    
                @endif
                <div class="title col col-10">
                    <h3>{{ $course->title }}</h3>
                </div>
                <div class="description col col-md-10">
                    <p>{{ $course->description }}</p>
                </div>
            </div>
            <div class="owner-details row ">
                <p class="col col-4"><b>Published By: </b> {{ $course->owner_details->name }}</p>
                <p class="col col-2"><b>Price</b> {{ $course->price }}</p>
            </div>
            <div class="introduction row justify-content-center">
                <div class="introduction-video col col-12 col-md-6 ">
                    @if ($course->introduction)
                        <video id="introduction-video" width="100%" src="{{ $course->introduction }}"></video>
                    @endif
                </div>
                <div class="d-block"></div>
                @can('update', $course)
                    <div class="edit col col-2">
                        <a class="btn btn-warning" href='/update/course/{{ $course->id }}'>Edit</a>
                    </div>
                @endcan
                @can('delete', $course)
                    <div class="edit col col-2">
                        <form action="{{ route('delete.course', ['course' => $course->id]) }}" method="post">
                            @method('delete')
                            @csrf
                            <input class="btn btn-danger" type="submit" value="Delete">
                        </form>
                    </div>
                @endcan
                @can('purchase', $course)
                    <div class="purchase col col-2">
                        <a class="btn btn-success" href={{route('purchase.product', ['product' => $course->id])}}> purchase </a>
                    </div>
                @endcan
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
                    <div draggable="true" class="tutorial-card row">
                        <div class="details col-sm-10">
                            <h3 id="title">{{ $tutorial->title }}</h3>
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
                                <a class="btn btn-warning"
                                    href="/course/{{ $course->id }}/tutorial/{{ $tutorial->id }}">Edit</a>
                            @endcan
                            @canany(['update', 'tutorial'], $course)
                                <div class="watch">
                                    <button tutorial={{ $tutorial->id }} class="btn btn-primary watch-tutorial"
                                        id='open-tutorial'>Watch</button>
                                </div>
                            @endcanany
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="reviews-box">
                @can('review', $course)
                    <form class="form-group row justify-content-center mb-3"
                        action="{{ route('create.review', ['name' => $course->getTable(), 'id' => $course->id]) }}"
                        method="post">
                        @csrf
                        <div class="col col-8">
                            <label class="form-label" for="content">review</label><br>
                            <input required class="form-control" type="text" name="content" id="review">
                            @error('content')
                                <div class="error-box">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label class="form-label" for="stars">stars</label><br>
                            <input required class="from-control" type="number" name="stars" id="stars" min="1" max="10">
                            @error('stars')
                                <div class="error-box">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="d-block mb-3"></div>
                        <div class="col col-8">
                            <input class="form-control" class="btn btn-success" type="submit" value="review">
                        </div>

                    </form>
                @endcan
                @foreach ($course->review as $item)
                    <x-review :review="$item" :reviewable="$course"></x-review>
                @endforeach
            </div>

        </div>
    @endif
@endsection
@section('scripts')

    {{-- variable injection --}}
    @if ($course)
        <script>
            let csrf = document.head.querySelector("meta[name='_token']").content;
            let user = @json(Auth::user());
            let course = @json($course);
        </script>
    @endif
    <script src={{ asset('js/course_show.js') }}></script>
@endsection
