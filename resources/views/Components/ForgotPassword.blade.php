@extends('../Layout/Layout')
@section('title', 'forgot-password')

@section('body')
    <form action={{ route('forgot.password') }} method="post">
        @csrf
        <fieldset>
            <legend>send password reset link</legend>
            <label for="email">email</label>
            <input type="email" name="email" id="email"><br>
            @error('email')
                {{$message}}
            @enderror
            <input type="submit" value="send link">
        </fieldset>
        @if (session('email'))
            @dump(session('email'))
        @endif
    </form>
@endsection
