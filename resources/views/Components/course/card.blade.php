<a href="/show/course/{{ $course->id }}" style="text-decoration: none" class="course-card">
    <div class="thumbnail">
        @if ($course->thumblin)
            <img src="{{ asset($course->thumblin->file_link) }}" alt="no image">
        @endif
    </div>
    <div class="title">
        <h4>{{ $course->title }}</h4>
    </div>
    <div class="details">
        <span class="created_at">created at:
            {{ $course->created_at->diffForHumans() }}</span>

        {{-- on javscript there will be a event on click for owner-link which will send usert to it's profile --}}
        <span class="owner-link owner-name" data-user-id="{{ $course->owner_details->id }}">
            Author:{{ $course->owner_details->name }}
        </span>
        <span class="price">Price: {{ $course->price }} usd</span>

        <span class="rate">
            <span>Rate:</span>
            <div class="rate-overlay">
                <div class="rate-image" style="width:{{ $course->avg_rate * 10 }}%"></div>
            </div>
        </span>
    </div>
</a>