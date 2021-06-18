<div class="hide reply-review-form" id="review-edit-{{ $review->id }}">
    <div class="row header">
        <div class="col header-title col-10">
            <h5 style="width: 80%" class="modal-title">Edit reply</h5>
        </div>
        <div class="col header-close col-2">
            <button style="width: 20%" id="close-edit-{{ $review->id }}" type="button" class="btn-close"></button>
        </div>
    </div>
    <div class="row form">
        @can('reply', $review)
            <form class="form-group row justify-content-center mb-3"
                action="{{ route('update.review', ['review' => $review->id]) }}" method="post">
                @csrf
                @method('put')
                <div class="col col-12">
                    <label class="form-label" for="content">review</label><br>
                    <input required class="form-control" type="text" name="content" id="review">
                    @error('content')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror
                    @if ($review->reviewable_type !== 'review_reply')
                    <label class="form-label" for="stars">stars</label><br>
                    <input required class="from-control" type="number" name="stars" id="stars" min="1" max="10">
                    @error('stars')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror
                    @endif
                </div>
                <div class="d-block mb-3"></div>
                <div class="col col-12">
                    <input class="form-control" class="btn btn-success" type="submit" value="Save">
                </div>

            </form>
        @endcan
    </div>
</div>
<div class="hide reply-review-form" id="reply-create-{{ $review->id }}">
    <div class="row header">
        <div class="col header-title col-10">
            <h5 style="width: 80%" class="modal-title">create reply</h5>
        </div>
        <div class="col header-close col-2">
            <button style="width: 20%" id="close-create-{{ $review->id }}" type="button" class="btn-close"></button>
        </div>
    </div>
    <div class="row form">
        @can('reply', $review)
            <form class="form-group row justify-content-center mb-3"
                action="{{ route('create.review', ['name' => 'review_reply', 'id' => $review->id]) }}" method="post">
                @csrf

                <div class="col col-12">
                    <label class="form-label" for="content">review</label><br>
                    <input required class="form-control" type="text" name="content" id="review">
                    @error('content')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror
                    <input type="hidden" name="stars" value="1">
                </div>
                <div class="d-block mb-3"></div>
                <div class="col col-12">
                    <input class="form-control" class="btn btn-success" type="submit" value="review">
                </div>

            </form>
        @endcan
    </div>
</div>

<div class="row justify-content-center review-box">
    <div class="col col-8 review-items">

        <div class="card border border-dark mb-3  review-item">
            <div class="card-body row">
                <p class="card-title col-12 bg-light">{{ $review->content }}</p>
                <div class="col col-12 details">
                    <a style="text-decoration:none" class="link-secondary"
                        href="/{{ $review->owner_details->getProfileLocation() }}">{{ $review->owner_details->name }}</a>
                    <div class="stars">
                        @if (is_float((int) $review->stars / 2))
                            @for ($i = 0; $i < floor($review->stars / 2); $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                            <i class="bi bi-star-half"></i>
                        @else
                            @for ($i = 0; $i < $review->stars / 2; $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        @endif
                    </div>
                </div>
                <div class="edit-create col-12">
                    @can('update', $review)
                        <form action="{{ route('delete.review', ['review' => $review->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <input class="btn btn-danger" type="submit" value="Delete">
                        </form>
                        <button review={{ $review->id }} class="btn btn-warning edit-reply">Edit</button>
                    @endcan
                    @can('reply', $review)
                        <button id="create-reply-{{ $review->id }}" type="button"
                            class="create-reply btn btn-primary">Reply</button>
                    @endcan
                </div>
                <div class="row replies">
                    @foreach ($review->review_replys as $reply)
                        @if ($reply)
                            <x-review :review="$reply" :reviewable="$reviewable"></x-review>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    if (document.getElementById('create-reply-' + @json($review->id))) {
        document.getElementById('create-reply-' + @json($review->id)).addEventListener('click', (e) => {
            document.getElementById('reply-create-' + @json($review->id)).classList.remove('hide')
        })
        document.getElementById('close-create-' + @json($review->id)).addEventListener('click', (e) => {
            document.getElementById('reply-create-' + @json($review->id)).classList.add('hide')
        })
    }
    window.onload = () => {
        document.querySelectorAll('.edit-reply').forEach(element => {
            if (element) {
                element.addEventListener('click', (e) => {
                    document.getElementById('review-edit-' + element.getAttribute('review'))
                        .classList.remove('hide')
                })
            }
            document.getElementById('close-edit-' + element.getAttribute('review')).addEventListener(
                'click', (e) => {
                    document.getElementById('review-edit-' + element.getAttribute('review')).classList
                        .add('hide')
                })
        })
    }

</script>
