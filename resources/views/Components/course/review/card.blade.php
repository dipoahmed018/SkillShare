{{-- componetent data --}}

<div class="{{ $attributes['class'] }}" id="review-{{ $reviewData->id }}">
    <div class="review-content">
        <a class="owner-details" href="/profile/{{ $reviewData->ownerDetails->id }}">
            @if ($reviewData->ownerDetails->profilePicture)
                <img class="profile-image image" src="{{ $reviewData->ownerDetails->profile_picture }}"
                    alt="avatar">
            @else
                <div class="profile-text"><span>{{ substr($reviewData->ownerDetails->name, 0, 1) }}</span></div>
            @endif
            <span class="owner-name">{{ $reviewData->ownerDetails->name }}</span>
        </a>
        <p class="content">{{ $reviewData->content }}</p>
        @if ($canModify())
            <x-course.review.create class="review-edit" cancelable="true" data-reviewable-id="{{ $reviewData->id }}"
                data-review-type="{{ $reviewData->reviewable_type }}">
                @if ($isReview)
                    <x-course.rating />
                @endif
            </x-course.review.create>
        @endif

    </div>
    <div class="review-control">
        @if ($canModify())
            <span class="review-delete" style="cursor: pointer" data-review-id="{{ $reviewData->id }}">Delete</span>
            <span class="review-editor-btn" style="cursor: pointer"
                data-reivew-id="{{ $reviewData->id }}">Edit</span>
        @endif
        @if ($canReply())
            <span class="reply-creator-show" data-review-id="{{ $reviewData->id }}"
                style="cursor: pointer">reply</span>
        @endif
        <span class="created-at">{{ $reviewData->created_at->diffForHumans() }}</span>
        @if (!$parent)
            <x-rating :rating="$reviewData->stars"></x-rating>
        @endif
    </div>
    {{-- add replies here form javascript --}}
    <x-course.review.create class="reply-create" cancelable="true" data-reviewable-id="{{ $reviewData->id }}"
        data-review-type="review_reply" />
    <div class="replies">
    </div>
    {{-- <form class="reply-create" data-reviewable-id="{{ $reviewData->id }}" data-review-type='review_reply'>
        <input type="text" name="content" required>
        @csrf
        <button type="submit"></button>
    </form> --}}
    @if ($reviewData->repliesCount > 0)
        <span class="more-replies" data-review-id="{{ $reviewData->id }}">
            <i class="bi bi-reply-all"></i> more replies
        </span>
    @endif
</div>
