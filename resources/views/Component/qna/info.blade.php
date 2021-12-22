@props(['title' => null, 'createdAt', 'user'])

<div class="info">
    @if ($title)
        <h6 class="title">{{ $title }}</h6>
    @endif
    <p class="additional-info">Created at {{ $createdAt }} by {{ $user->name }}</p>
</div>
