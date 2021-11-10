@extends('../Layout/Layout')

@section('title', 'Login')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('body')
    <form class="login-form" action="{{ route('login') }}" method="post">
        @csrf
        <label for="email">Email</label>
        <input type="text" name="email" id="email" placeholder="Type your email" required>
        @error('email')
            <p>{{ $message }}</p>
        @enderror
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Type your password" required>
        <a href={{ route('forgot.password.form') }}> Forgot password </a>
        @error('limit')
            <p>{{ $message }}</p>
        @enderror
        <input type="submit" value="Login">
    </form>
    @if (session('status'))
        {{session('status')}}
    @endif
@endsection
