@extends('../Layout/Layout')
@section('title', 'forgot-password')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('body')
<div class="forgot-password">
    <h6>Enter you email and we will send you a password reset link</h6>
    <form class="forgot-pass-form" action={{ route('forgot.password') }} method="post">
        @csrf
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Type your email">
        @error('email')
            {{ $message }}
        @enderror
        <input type="submit" value="send link">
    </form>
</div>
@endsection
