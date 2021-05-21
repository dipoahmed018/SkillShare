@extends('../Layout/Layout')

@section('title', 'change email')

@section('body')
    @if (session('expired'))
        <p>Your link has expired would like to request a new link ?</p>
        <form action={{ route('user.update.email') }} method="post">
            @csrf
            <input type="hidden" name="_method" value='put'>
            <input type="submit" value="Request new link">
        </form>
    @else
        <form action={{ route('user.update.email') }} method="post">
            @csrf
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="code" value={{ $code }}>
            <label for="email">email</label>
            <input type="email" name="email" id="email" required>
            <input type="submit" value="change email">
        </form>
    @endif
@endsection
