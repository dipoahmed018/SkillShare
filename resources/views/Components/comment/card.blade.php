{{-- componetent data --}}
@php
$references = $comment->referenceUsers->pluck('id')->flatten();
@endphp
@props(['canModify' => false, 'replyForm' => true, 'comment'])
<div class="comment-card" id="comment-{{ $comment->id }}">
    <div class="comment-content">
        <a class="owner-details" href="/user/{{ $comment->ownerDetails->id }}/profile">
            @if ($comment->ownerDetails->profilePicture)
                <img class="profile-image image" src="{{ $comment->ownerDetails->profilePicture->file_link }}"
                    alt="avatar">
            @else
                <div class="profile-text">{{ substr($comment->ownerDetails->name, 0, 1) }}</div>
            @endif
        </a>
        <div class="content-wrapper">
            <div class="references">
                @foreach ($comment->referenceUsers as $user)
                    <a href="{{ $user->id }}/profile">{{ "@$user->name" }}</a>
                @endforeach
            </div>
            <div class="content">
                {{ $comment->content }}
            </div>
        </div>
        @if ($canModify)
            <x-comment.form id="comment-edit-{{ $comment->id }}" class="comment-edit hide" :cancelable="true"
                data-comment-id="{{ $comment->id }}" :value="$comment->content"
                data-references="{{ $references }}" />
        @endif

    </div>
    <div class="comment-control">
        @if ($canModify)
            <button class="comment-delete" style="cursor: pointer" data-comment-id="{{ $comment->id }}"
                data-bs-target="#delete-comment-confirmation" data-bs-toggle="modal">Delete</button>
            <button class="comment-editor-show" data-comment-id="{{ $comment->id }}" style="cursor: pointer"">Edit</button>
         @endif
                <button class="reply-creator-show" data-comment-id="{{ $comment->id }}"
                    data-commentable-id="{{ $comment->commentable_id }}" data-reference-id={{ $comment->owner }}
                    data-reference-name="{{ $comment->ownerDetails->name }}" style="cursor: pointer">reply</button>

                <span class="created-at">{{ $comment->created_at->diffForHumans() }}</span>
    </div>
    @if ($replyForm)
        <x-comment.form id="reply-create-{{ $comment->id }}" data-commentable-id="{{ $comment->id }}"
            class="reply-create hide" palcehodler="Type your reply here" />
    @endif
</div>
