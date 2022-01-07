@extends('../Layout/Layout')

@section('title', 'Profile Update')

@section('headers')
    <link rel="stylesheet" href={{ asset('css/profile.css') }}>
@endsection

@section('body')
    <section class="user-profile">
        <div class="profile-picture-wrapper">
            <img src="{{ $profileUser->profilePhoto }}" alt="no picture">
        </div>
        <div class="details">
            <h3>{{ $profileUser->name }}</h3>
            <p>Gender: {{ $profileUser->gender }}</p>
            <p>Member since: {{ $profileUser->created_at }}</p>
            <p>Email: {{ $profileUser->email }}</p>
        </div>
    </section>
    <section class="controls">
        @if ($profileUser->id == $user?->id)
            <button><a href="/create/course" style="text-decoration: none; color:white;">Create course</a></button>
        @endif
        <button class="control-buttons" data-toggle-target=".my-courses">{{ $user ? 'My' : 'Users' }} courses</button>
        <button class="control-buttons" data-toggle-target=".bought-courses">Bought courses</button>
        <button class="control-buttons" data-toggle-target=".activity-logs">Activity logs</button>
        @isset($user)
            <button class="control-buttons" data-toggle-target=".profile-update">Profile update</button>
        @endisset
        <button class="control-buttons" data-toggle-target=".profile-details">About</button>

    </section>
    <section class="resources">
        <div class="bought-courses resource-box">
            <h5>Bought courses</h5>
            <div class="courses">
                @foreach ($profileUser->boughtCourses as $course)
                    <div class="course-card">
                        <x-course.card :course="$course" />
                    </div>
                @endforeach
            </div>
        </div>
        <div class="my-courses resource-box hide">
            <h5>My courses</h5>
            <div class="courses">
                @foreach ($profileUser->myCourses as $course)
                <div class="course-card">
                    <x-course.card :course="$course" />
                </div>
                @endforeach
            </div>
        </div>
        <div class="activity-logs resource-box hide">
            <h5>Activity logs</h5>
            <div class="activities">

            </div>
        </div>
        @isset($user)
            <div class="profile-update resource-box hide">
                @include('pages.user.ProfileUpdateForm')
            </div>
        @endisset
        <div class="profile-details resource-box hide">
            <h5>About</h5>

        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const profileUser = @json($profileUser);
    </script>
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
