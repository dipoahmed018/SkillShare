@props(['title' => 'Are you sure'])

<div class="yes-no-confirm">
    <p>{{$title}}</p>
    <div class="buttons">
        <button class="yes" {{$attributes['yes']}} data-bs-dismiss="modal">Yes</button>
        <button class="no" {{$attributes['no']}} data-bs-dismiss="modal">No</button>
    </div>
</div>