@extends('../Layout/Layout')

@section('title', 'password-reset')
@section('headers')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection
@section('body')
    <form class="password-reset-form" action={{ route('password.update') }} method="post">
        <input type="hidden" name="token" value={{ $token }}>
        <input type="hidden" name="email" value={{ $email }}>
        @csrf
        <label for="password">New password</label>
        <input type="password" name="password" id="password" placeholder="Type your new password"><br>
        <label for="password_confirmation">Password confirmation</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Retype your new password"><br>
        <input type="submit" value="Reset password">
        @error('email')
            {{ $message }}
        @enderror
        @error('password')
            {{ $message }}
        @enderror
    </form>
@endsection
