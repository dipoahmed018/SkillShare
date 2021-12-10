@props(['post', 'user', 'editModalTarget' => 'edit-post-modal'])
@php
$votes = $post->incrementVotes - $post->decrementVotes;
$commentsAvailable = $post->comments?->count()
@endphp

<div class="{{ $post->post_type }} post-{{$post->id}}" id={{ "$post->post_type - $post->id" }}>
    <div class="details">

        @if ($post->post_type == 'question')
            <x-qna.info :title="$post->title" :created-at="$post->created_at->diffForHumans()"
                :user="$post->ownerDetails" />
        @else
            <x-qna.info :created-at="$post->created_at->diffForHumans()" :user="$post->ownerDetails" />
        @endif


        {{-- @if ($user?->id == $post->ownerDetails->id) --}}
        <x-qna.control :id="$post->id" :modal-target="$editModalTarget" />
        {{-- @endif --}}

    </div>
    <div class="content-wrapper">
        <x-qna.vote :votes="$votes" :voted="$post->voted" :post="$post" />
        <div class="content">
            {!! $post->content !!}
        </div>
    </div>

    <div class="comments-wrapper">
        <div class="comments">
            @foreach ($post->comments as $comment)
                <x-comment.card :can-modify="true" :comment="$comment" :reply-form="false"/>
            @endforeach
        </div>
            <x-comment.form class="comment-create" id="comment-create-{{ $post->id }}" data-commentable-id="{{ $post->id }}"
                placeholder="type your comment here" :cancelable="false" />

        <button class="comment-create-btn" data-post-id="{{$post->id}}">Comment</button>
        @if ($post->comments_count > 5)
            <button class="load-more" data-offset="{{$commentsAvailable}}" data-post-id="{{$post->id}}">Load more</button>
        @endif
    </div>
</div>
