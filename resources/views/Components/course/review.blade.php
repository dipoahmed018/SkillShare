<div class="review">
    <div class="review-content">
        <div class="owner-details">
            @if ($review->owner_details->profilePicture)
                
            @endif
            <span>{{$review->owner_details->name}}</span>
        </div>
        <p class="content">{{$review->content}}</p>
    </div>
    <div class="review-control">
        <span>reply</span>
        <span>{{$review->created_at->diffForHumans()}}</span>
    </div>
    <div class="replies">

    </div>
</div>