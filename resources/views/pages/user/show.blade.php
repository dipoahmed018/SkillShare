@extends('../Layout/Layout')

@section('title', 'Profile Update')

@section('headers')
    <link rel="stylesheet" href={{ asset('css/profile.css') }}>
@endsection

@section('body')
    <section class="user-profile">
        <div class="profile-picture-wrapper">
            <img src="{{$user->profilePhoto}}" alt="no picture">
        </div>
        <div class="details">
            <h3>{{$profileUser->name}}</h3>
            <p>Gender: {{$profileUser->gender}}</p>
            <p>Member since: {{$profileUser->created_at}}</p>
            <p>Email: {{$profileUser->email}}</p>
        </div>
    </section>
    <section class="controls">
            <button class="control-buttons" data-toggle-target=".my-courses">{{$profileUser->id == Auth::user()?->id ? 'My' : 'Users'}} courses</button>
            <button class="control-buttons" data-toggle-target=".bought-courses">Bought courses</button>
            <button class="control-buttons" data-toggle-target=".activity-logs">Activity logs</button>
            <button class="control-buttons" data-toggle-target=".profile-update">Profile update</button>
            <button class="control-buttons" data-toggle-target=".profile-details">About</button>

    </section>
    <section class="resources">
        <div class="bought-courses resource-box">
            <h5>Bought-courses</h5>
            <div class="courses">
                @foreach ($profileUser->myCourses as $course)
                <div class="course-card">
                    <x-course.card :course="$course"/>
                </div>
                @endforeach
            </div>
        </div>
        <div class="my-courses resource-box hide">
            <h5>My courses</h5>
            <div class="courses">
            </div>
        </div>
        <div class="activity-logs resource-box hide">
            <h5>Activity logs</h5>
            <div class="activities">
                
            </div>
        </div>
        <div class="profile-update resource-box hide">
            <h5>Profile update</h5>

        </div>
        <div class="profile-details resource-box hide">
            <h5>About</h5>

        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const profileUser = @json($profileUser);
    </script>
    <script src="{{asset('js/profile.js')}}"></script>
@endsection
