@extends('../Layout/Layout')

@section('title', 'Login')

@section('body')
    <form action="{{ route('login') }}" method="post">
        @csrf
        <label for="email">email</label>
        <input type="text" name="email" id="email" placeholder="type your email" required><br>
        @error('email')
            <p>{{ $message }}</p>
        @enderror
        <label for="password">password</label>
        <input type="password" name="password" id="password" placeholder="type your password" required><br>
        @error('limit')
            <p>{{ $message }}</p>
        @enderror
        <input type="submit" value="Login">
    </form>
    @if (session('status'))
        {{session('status')}}
    @endif
    <a href={{ route('forgot.password.form') }}> forgot password </a>
@endsection
