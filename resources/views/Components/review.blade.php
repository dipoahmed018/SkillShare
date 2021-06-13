<div class="container">

    <div class="row justify-content-center box-name">
        <div class="col col-4">
            {{ $name }}
        </div>
    </div>
    <div class="row justify-content-center review-box">
        <div class="col col-8 review-items">
            @foreach ($reviews as $review)
                <div class="card border border-dark mb-3  review-item">
                    <div class="card-body">
                        <p class="card-title">{{ $review->content }}</p>
                        <div class="details">
                            <a style="text-decoration:none" class="link-secondary"
                                href="/{{ $review->owner_details->getProfileLocation() }}">{{ $review->owner_details->name }}</a>
                            <span>{{ $review->stars }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @dump($reviews)
    </div>
</div>
