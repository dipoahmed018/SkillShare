{{-- componetent data --}}
@props(['canModify' => false, 'comment'])
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
        <p class="content">{!! $comment->content !!}</p>
        @if (!$canModify)
            <x-comment.form id="comment-edit-{{$comment->id}}" class="comment-edit hide" :cancelable="true"
                data-comment-id="{{ $comment->id }}" data-reference-id="{{ $comment->owner }}"/>
        @endif

    </div>
    <div class="comment-control">
        @if (!$canModify)
            <span class="comment-delete" style="cursor: pointer" data-comment-id="{{ $comment->id }}">Delete</span>
            <span class="comment-editor-show" data-comment-id="{{$comment->id}}" style="cursor: pointer"">Edit</span>
        @endif
        @if (!$canModify)
            <span class="reply-creator-show" data-comment-id="reply-create-{{$comment->id}}" style="cursor: pointer">reply</span>
        @endif
        <span class="created-at">{{ $comment->created_at->diffForHumans() }}</span>
    </div>
    <x-comment.form id="reply-create-{{$comment->id}}" data-commentable-id="{{$comment->id}}" class="reply-create hide" palcehodler="Type your reply here"/>
</div>
