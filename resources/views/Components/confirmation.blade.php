@props(['title' => 'Are you sure'])

<div class="yes-no-confirm">
    <p>{{$title}}</p>
    <div class="buttons">
        <button class="yes" {{{$attributes['yes']}}}>Yes</button>
        <button class="no" {{$attributes['no']}}>No</button>
    </div>
</div>