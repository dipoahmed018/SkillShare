{{-- componetent data --}}

<div class="{{ $attributes['class'] }}" id="review-{{ $reviewData->id }}">
    <div class="review-content">
        <a class="owner-details" href="/user/{{ $reviewData->ownerDetails->id }}/profile">
            @if ($reviewData->ownerDetails->profilePicture)
                <img class="profile-image" src="{{ $reviewData->ownerDetails->profilePicture->file_link }}"
                    alt="avatar">
            @else
                <div class="profile-image"><span>{{ substr($reviewData->ownerDetails->name, 0, 1) }}</span></div>
            @endif
            <span class="owner-name">{{ $reviewData->ownerDetails->name }}</span>
        </a>
        <p class="content">{{ $reviewData->content }}</p>
    </div>
    <div class="review-control">
        @if ($canReply())
            <span class="reply-creator-show" data-review-id="{{ $reviewData->id }}"
                style="cursor: pointer">reply</span>
        @endif
        <span class="created_at">{{ $reviewData->created_at->diffForHumans() }}</span>
        @if (!$parent)
            <x-rating :rating="$reviewData->stars"></x-rating>
        @endif
    </div>
    {{-- add replies here form javascript --}}
    <div class="replies">
    </div>
    <form class="reply-create" id="reply-create-{{ $reviewData->id }}" data-review-id="{{ $reviewData->id }}">
        <input type="text" name="content">
        @csrf
        <button type="submit"></button>
    </form>
    @if ($reviewData->repliesCount > 0)
        <span class="more-replies" data-review-id="{{ $reviewData->id }}">
            <i class="bi bi-reply-all"></i> more replies
        </span>
    @endif
</div>

