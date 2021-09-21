@extends('../Layout/Layout')

@section('title', 'Dashboard')

@section('body')
    @include('Layout.Header')
    @auth
    @dump(Auth::user())
    <h1>authenticated</h1>
    @if (Auth::user()->email_verified_at == null)

            <span>please verify your email</span>
            <div>
                <button id="change_email">change email</button>
                <form id="sendmail" action={{ route('sendmail.verify') }} method="get">
                    <input type="submit" value="resend mail">
                </form>
                <form id="change_email_form" class="hide" action={{ route('user.update.email') }} method="put">
                    <input type="email" name="email" id="email">
                    <input type="submit" value="send mail">
                </form>
            </div>
            <button id="send" class=" btn btn-primary"> send request </button>
            @endif
        @endauth
    {{-- <h1>Dashboard</h1> --}}
    {{-- <div class="error-box">

    </div> --}}
@endsection

@section('scripts')
@stack('header')
<script src={{ asset('js/dashboard.js') }}></script>
@endsection
