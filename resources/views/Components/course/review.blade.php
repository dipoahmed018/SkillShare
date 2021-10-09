<div class="{{ $attributes['class'] }}">
    <div class="review-content">
        <a class="owner-details" href="/user/{{ $reviewData->ownerDetails->id }}/profile">
            @if ($reviewData->ownerDetails->profilePicture)
                <img class="profile-image" src="{{ $reviewData->ownerDetails->profilePicture->file_link }}" alt="avatar">
            @else
                <div class="profile-image"><span>{{ substr($reviewData->ownerDetails->name, 0, 1) }}</span></div>
            @endif
            <span class="owner-name">{{ $reviewData->ownerDetails->name }}</span>
        </a>
        <p class="content">{{ $reviewData->content }}</p>
    </div>
    <div class="review-control">
            <span class="create-reply">reply</span>
        <span>{{ $reviewData->created_at->diffForHumans() }}</span>
        <x-rating :rating="$reviewData->stars"></x-rating>
    </div>
    @if ($reviewData->reviewable_type == 'course' && $reviewData->reviewReplies->count() > 0)
        <div class="replies">
            @foreach ($reviewData->reviewReplies as $reply)
                <x-course.review :review-data="$reply" :course="$course" class="reply" />
            @endforeach
        </div>
    @endif
</div>
