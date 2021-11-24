@props(['student'])

<a class="student-card slider-item" href="#">
    @if ($student->profilePicture)
        <img class="avatar" src="{{ $student->profilePicture->file_link }}" alt="avatar">
    @else
        <p class="avatar">{{ substr($student->name, 0, 1) }}</p>
    @endif
    <h6>{{ $student->name }}</h6>
</a>