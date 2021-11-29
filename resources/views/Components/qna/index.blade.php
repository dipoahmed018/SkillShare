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
        <x-qna.vote :votes="$votes" :voted="$post->voted" />
        <div class="content">
            {!! $post->content !!}
        </div>
    </div>

    <div class="comments">
        @foreach ($post->comments as $comments)
            
        @endforeach
    </div>
</div>
