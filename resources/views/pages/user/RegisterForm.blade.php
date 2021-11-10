@extends('../Layout/Layout')

@section('title', 'register-form')
@section('headers')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('body')

    <form class="register-form" action="{{ route('register') }}" method="post">
        @csrf
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="dipo ahmed" required>
        @error('name')
            {{ $message }}
        @enderror
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="dipoahmed018@gmail.com" required>
        @error('email')
            {{ $message }}
        @enderror
        <label for="password">Password</label>
        <input type="password" value="password" name="password" id="password" placeholder="password" required>
        <label for="password_confirma">Password confirmation</label>
        <input type="password-confirmation" value="password" name="password_confirmation" id="password-confirmation"
            placeholder="confirm password" required>
        @error('password')
            {{ $message }}
        @enderror
        <select name="gender" id="gender" title="Gender" required>
            <option value="default" selected disabled>Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        @error('gender')
            <span>{{ $message }}</span>
        @enderror
        <label for="birthdae">Birthdate</label>
        <input type="date" name="birthdate" id="birthdate">
        @error('birthdate')
            <span>{{ $message }}</span>
        @enderror
        <input type="submit" value="Register">
    </form>

@endsection
