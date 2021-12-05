@props(['post', 'user', 'editModalTarget' => 'edit-post-modal'])
@php
$votes = $post->incrementVotes - $post->decrementVotes;
@endphp

<div class={{ $post->post_type }} id={{ "$post->post_type - $post->id" }}>
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
                <x-comment.card :comment="$comment" />
                <x-comment.card :comment="$comment" />
                <x-comment.card :comment="$comment" />
                <x-comment.card :comment="$comment" />
                <x-comment.card :comment="$comment" />
            @endforeach
        </div>
        <div class="create-comment">
            <x-comment.form placeholder="type your comment here" :cancelable="false"/>
        </div>
        <button>Load more</button>
    </div>
</div>
