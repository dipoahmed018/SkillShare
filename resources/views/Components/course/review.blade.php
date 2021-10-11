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
        <span>{{ $reviewData->created_at->diffForHumans() }}</span>
        @if (!$parent)
            <x-rating :rating="$reviewData->stars"></x-rating>
        @endif
    </div>
    @if (!$parent && $reviewData->reviewReplies->count() > 0)
        <div class="replies">
            @foreach ($reviewData->reviewReplies as $reply)
                <x-course.review :review-data="$reply" :parent="$reviewData" :course="$course" :user="$user"
                    class="reply" />
            @endforeach
        </div>
    @endif
    <form class="reply-create" id="reply-create-{{ $reviewData->id }}">
        <input type="text">
        <button type="submit"></button>
    </form>
    <span class="more-replies" data-review-id="{{ $reviewData->id }}"> <i class="bi bi-reply-all"></i> more
        replies</span>
</div>


@once
    {{-- component script --}}
    @push('component-script')
        <script>
            const more_replies_btn = document.querySelectorAll('.more-replies') //load more replies buttons
            const reply_creator_btn = document.querySelectorAll('.reply-creator-show') //reply creator shower button
            //reply creater form shower on reply_creator_btn click
            reply_creator_btn.forEach(el => {
                el.addEventListener('click', e => {
                    const form = document.getElementById(`reply-create-${e.target.getAttribute('data-review-id')}`)
                    form.style.display = 'block'
                })
            })
        </script>
    @endpush

@endonce
