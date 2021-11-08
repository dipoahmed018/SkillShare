@extends('../Layout/Layout')

@section('title', 'course')
@section('headers')
    <link rel="stylesheet" href={{ asset('css/course.css') }}>
@endsection

@section('tamplates')
    {{-- componetent data --}}

    <template id="review-template">
        <div class="review-content">
            <a class="owner-details" href="/user/template/profile">
                <img class="profile-image">
                <div class="profile-text"><span></span></div>
                <span class="owner-name"></span>
            </a>
            <p class="content">content
            <p>
                <x-course.create class="review-edit" data-reviewable-id="0" data-review-type="review_reply"
                    cancelable="true">
                    <x-course.rating />
                </x-course.create>
        </div>
        <div class="review-control">
            <span class="review-delete" style="cursor: pointer">Delete</span>
            <span class="review-editor-btn" style="cursor: pointer">Edit</span>
            <span class="reply-creator-show" style="cursor: pointer">reply</span>
            <span class="created-at">required</span>
            <x-rating :rating="0"></x-rating>
        </div>

        {{-- reply creator --}}
        <x-course.create class="reply-create" data-reviewable-id="0" data-review-type="review_reply" cancelable="true">
        </x-course.create>

        {{-- add replies here form javascript --}}
        <div class="replies">
        </div>
    </template>

@endsection

@section('body')
    {{-- modals stars --}}

    {{-- tutorial video streamer modal --}}
    <div class="modal fade" id="tutorial-video-modal" tabindex="-1" aria-hidden="true">
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
    <x-course.modal.thumbnail-updt :course="$course" />
    <x-course.modal.delete :course="$course" />
    <x-course.modal.introduction-updt :course="$course" />
    <x-tutorial.tutorial-delete />
    {{-- modals end --}}

    {{-- course-introduction is for the title created at and other deitals --}}
    <div class="course-introduction">
        <div class="course-details">
            <h5>{{ $course->title }}</h5>
            <div class="details">
                <span>
                    Create By:
                    <a href="/user/{{ $course->ownerDetails->id }}/profile">
                        {{ $course->ownerDetails->name }}
                    </a>
                </span>
                <span>Create at: {{ $course->created_at->diffForHumans() }}</span>
                <x-rating :rating="$course->avg_rate"> </x-rating>
            </div>
            @can('update', $course)
                <div class="details-update tools">
                    <a class="tool" href="/update/course/{{ $course->id }}" data-bs-hover="tooltip"
                        title="Edit Course">
                        <i class="bi bi-pencil-square tool tool-icon"></i>
                    </a>
                    <div class=" tool" data-bs-hover="tooltip" title="Update Introduction">
                        <i class="bi bi-file-earmark-play-fill tool-icon" id="introduction-updater-btn"></i>
                    </div>
                    <div class="tool" data-bs-hover="tooltip" title="Update Thumbnail">
                        <i class="bi bi-card-image tool-icon" id="thumbnail-updater-btn" data-bs-toogle="modal"
                            data-bs-target="#thumbnail-update-modal"></i>
                    </div>
                    {{-- @can('delete', $course) --}}

                    <div class="tool" data-bs-hover="tooltip" title="Delete Course">
                        <i class="bi bi-trash tool-icon" style="color: black" id="course-deleter-btn" data-bs-toogle="modal"
                            data-bs-target="#course-delete-modal"></i>
                    </div>
                    {{-- @endcan --}}
                </div>
            @endcan
        </div>

        {{-- thumbnail is for the introduction video or course thumbnail --}}
        <div class="thumbnail-video">
            @if ($course->introduction)
                <video id="introduction-video" width="100%" src="{{ $course->introduction->file_link }}"
                    poster={{ $course->thumbnail ? $course->thumbnail->file_link : '' }} controls>
                </video>
            @elseif($course->thumbnail)
                <img src="{{ $course->thumbnail ? $course->thumbnail->file_link : '' }}" alt="thumbnail">
            @endif
        </div>
    </div>
    <div class="description-box">
        <div class="description" style="display: block">
                {{ Str::limit($course->description, 60, '......') }}
        </div>
        @if (strlen($course->description) > 60)
            <button class="deep-green-btn">See more</button>
        @endif
    </div>
    {{-- @can('purchase', $course)
            <div class="purchase col col-2">
                <a class="btn btn-success" href={{ route('purchase.product', ['product' => $course->id]) }}> purchase </a>
            </div>
        @endcan --}}
    {{-- <div class="tutorial-upload row justify-content-center mb-2">

        @can('update', $course)
            <div id="tutorial-upload-box" class="upload-tutorial col col-md-2 mt-2">
                <input required accept=".mp4" type="file" name="tutorial" class="add-vide one-click-upload" id="tutorial">
                <label class="add-button btn btn-primary" for="tutorial">Add Tutorial</label>
                <div id="tutorial-error"></div>
            </div>
            <div id="tutorial-progress-box" class="hide col col-md-5">
                <div class="progress">
                    <div id="tutorial-progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <button id="tutorial-up-cancel" class="btn btn-danger">cancel</button>
                <button class="pause" id="tutorial-up-pause" class="btn btn-danger">pause</button>
            </div>
        @endcan
    </div> --}}
    <div class="tutorial-videos">
        <form class="add-tutorial">
            <x-progress-bar />
            <input type="file" accept=".mp4" name="tutorial" id="tutorial-upload">
            <label for="tutorial-upload">
                    <span>Drop You file or click to select a file</span>
                    <i class="bi bi-file-plus"></i>
                    <span>Add a new tutorial</span>
            </label>
        </form>
        <div class="tutorials">
            @foreach ($course->tutorialDetails as $tutorial)
                <x-tutorial.tutorial-card :tutorial="$tutorial" :course="$course" :user="$user"/>
            @endforeach
        </div>
    </div>
    <div class="reviews-box">
        <h5>Reviews</h5>
        @can('review', $course)
            <x-course.create class="review-create" display="flex" data-reviewable-id="{{ $course->id }}"
                data-review-type="course">
                <x-course.rating></x-course.rating>
            </x-course.create>
        @endcan
        <div class="reviews">
            @foreach ($course->reviews as $item)
                <x-course.review :review-data="$item" :course="$course" :user="$user" class="review" />
            @endforeach
        </div>
        <div class="links-wrapper">
            {{ $course->reviews->links() }}
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    {{-- php data assign to javascript variable --}}
    <script>
        let csrf = document.head.querySelector("meta[name='_token']").content;
        let user = @json($user);
        let course = @json($course);
    </script>

    {{-- pagescripts --}}
    <script src={{ asset('js/course_show.js') }}></script>
    @stack('component-script')
@endsection
