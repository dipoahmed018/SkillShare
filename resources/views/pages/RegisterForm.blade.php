@extends('../Layout/Layout')

@section('title', 'register-form')

@section('body')

    <form action="{{ route('register') }}" method="post">
        @csrf
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="dipo ahmed" required><br>
        @error('name')
            {{ $message }}
        @enderror
        <label for="email">email</label>
        <input type="text" name="email" id="email" value="dipoahmed018@gmail.com" required><br>
        @error('email')
            {{ $message }}
        @enderror
        <label for="password">password</label>
        <input type="password" value="password" name="password" id="password" placeholder="password" required>
        <input type="password" value="password" name="password_confirmation" id="confirm_password"
            placeholder="confirm password" required>
        @error('password')
            {{ $message }}
        @enderror
        <fieldset style="width: 100px;">
            <legend>gender</legend>
            <label for="gender">male</label>
            <input type="radio" name="gender" id="male" value="male"><br>
            <label for="gender">female</label>
            <input type="radio" name="gender" id="female" value="female"><br>
        </fieldset>
        @error('gender')
            <span>{{ $message }}</span>
        @enderror
        <input type="date" name="birthdate" id="birthdate">
        @error('birthdate')
            <span>{{ $message }}</span>
        @enderror
        <input type="submit" value="Register">
    </form>

@endsection
