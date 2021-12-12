@props(['student'])

<a class="student-card slider-item" href="#">

    <img class="avatar" src="{{ $student->profile_picture }}" alt="avatar">

    <h6>{{ $student->name }}</h6>
</a>
