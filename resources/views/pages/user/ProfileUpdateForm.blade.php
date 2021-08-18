@extends('../Layout/Layout')

@section('title', 'Profile Update')
@section('headers')
<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('body')

    <div id="popup">
        @if (session('sent'))
            {{ session('sent') }}
        @endif
    </div>

    <form id="user_update_form_image" action={{ route('user.update') }} method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>

            <legend>
                profie picture
            </legend>
            <img width="150px" src={{ $profile_picture }} alt="">
            <input accept=".jpeg, .jpg, .png" type="file" name="profile_picture" id="profile_picture">
            @error('profile_picture')
                <p id="profile_picture_phperror">{{ $message }}</p>
            @enderror

            <p id="profile_picture_scripterror"></p>
            <input type="submit" value="submit">
        </fieldset>
    </form>

    <form id="user_update_form_name" action={{ route('user.update') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>
            <legend>name</legend>
            <h1>{{ $user->name }}</h1>
            <input type="text" name="name" id="name" placeholder="type your name">
            @error('name')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change name">
        </fieldset>
    </form>

    <form id="user_update_form_name" action={{ route('user.update') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>
            <legend>gender</legend>
            <h1>I am a {{ $user->gender }}</h1>
            <label for="gender">male</label>
            <input type="radio" name="gender" id="male" value="male"><br>
            <label for="gender">female</label>
            <input type="radio" name="gender" id="female" value="female"><br>
            @error('gender')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change gender">
        </fieldset>
    </form>

    <form id="user_update_form_name" action={{ route('user.update') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>
            <legend>birthdate</legend>
            <h1>my birthdate is {{ $user->birthdate }}</h1>
            <input type="date" name="birthdate" id="birthdate">
            @error('birthdate')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change birthdate">
        </fieldset>
    </form>

    <form id="password_change" action={{ route('user.update.password') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>
            <legend>change password</legend>
            <h1>change password</h1>
            <label for="ole_password">old password</label>
            <input type="password" name="old_password" id="old_password"><br>
            @error('old_password')
                <p>{{ $message }}</p>
            @enderror
            <label for="password">new password</label>
            <input type="password" name="password" id="password" required><br>
            <label for="password_confirmation" required>confirm password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required><br>
            @error('password')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change password">
        </fieldset>
    </form>
    <fieldset>
        <legend>change email</legend>
        @if (Auth::user()->email_verified_at)
            <form action={{ route('user.update.email') }} method="post">
                <input type="hidden" name="_method" value="put">
                @csrf
                <input type="submit" value="change email">
            </form>
        @else
            <form action={{ route('user.update.email') }} method="post">
                <input type="hidden" name="_method" value="put">
                @csrf
                <input type="email" name="email" id="email">
                <input type="submit" value="change email">
            </form>
        @endif
    </fieldset>
    <form action="{{ route('purchase.course', ['course' => 2]) }}" method="post">
        @csrf
        <input type="submit" value="set">
    </form>

@endsection

@section('scripts')
    <script src={{ asset('js/profile_update.js') }}></script>
@endsection
