@extends('../Layout/Layout')

@section('title','Dashboard')   

@section('body')
    <div id="popup_box">
        @if (session('sent'))
            <div>{{session('sent')}}</div>
        @endif
    </div>
    @if (Auth::user()->email_verified_at == null)
        <div>
            <span>please verify your email</span>
            <form id="sendmail" action={{route('sendmail.verify')}} method="get">
                <input type="submit" value="resend mail">
            </form>
            <button id="change_email">change email</button>
            <form id="change_email_form" class="hide" action={{route('user.update.email')}} method="put">
                <input type="email" name="email" id="email">
                <input type="submit" value="send mail">
            </form>
        </div>
    @endif
    <h1>Dashboard</h1>
    @auth
        <h1>hello</h1>
    @endauth
@endsection

@section('scripts')
    <script src={{ asset('js/dashboard.js') }}></script>
@endsection