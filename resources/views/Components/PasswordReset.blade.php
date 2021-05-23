@extends('../Layout/Layout')

@section('title', 'password-reset')

@section('body')
    <form action={{ route('password.update') }} method="post">
        <input type="hidden" name="token" value={{ $token }}>
        <input type="hidden" name="email" value={{ $email }}>
        @csrf
        <label for="password">new password</label>
        <input type="password" name="password" id="password" placeholder="provide your password"><br>
        <label for="password_confirmation">new password_confirmation</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="provide your password_confirmation"><br>
        <input type="submit" value="reset password">
        @error('email')
            {{ $message }}
        @enderror
        @error('password')
            {{ $message }}
        @enderror
    </form>
@endsection
