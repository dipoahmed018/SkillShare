@component('mail::message')
    You have made a request to change your mail <br> If you want to change your mail then pleas click the button below and
    provide us with your new email
    @component('mail::button', ['url' => $url])
        Change Email
    @endcomponent
    {{config('app.name')}}
@endcomponent
