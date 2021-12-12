@extends('../Layout/Layout')

@section('title', 'Profile Update')

@section('headers')
    <link rel="stylesheet" href={{ asset('css/profile.css') }}>
@endsection

@section('body')
    <section class="user-profile">
        <div class="profile-picture-wrapper">
            <img src="{{$user->profile_picture}}" alt="no picture">
        </div>
        <div class="details">
            <h3>{{$user->name}}</h3>
            <p>Gender: {{$user->gender}}</p>
            <p>Member since: {{$user->created_at}}</p>
            <p>Email: {{$user->email}}</p>
        </div>
    </section>
    <section class="controls">
            <button data-toggle-target=".my-courses">My courses</button>
            <button data-toggle-target=".bought-courses">Bought courses</button>
            <button data-toggle-target=".activity-logs">Activity logs</button>
            <button data-toggle-target=".profile-update">Profile update</button>
            <button data-toggle-target=".profile-details">About</button>

    </section>
    <section class="resources">
        <div class="my-courses hide">
            <h5>My courses</h5>
            <div class="courses">

            </div>
        </div>
        <div class="bought-courses">
            <h5>Bought-courses</h5>
            <div class="courses">
                
            </div>
        </div>
        <div class="activity-logs hide">
            <h5>Activity logs</h5>
            <div class="activities">
                
            </div>
        </div>
        <div class="profile-update hide">
            <h5>Profile update</h5>
        </div>
        <div class="profile-details hide">
            <h5>About</h5>

        </div>
    </section>
@endsection

@section('scripts')

@endsection
