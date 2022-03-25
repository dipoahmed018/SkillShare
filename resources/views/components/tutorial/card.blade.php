<div class="tutorial-card" id="tutorial-{{ $tutorial->id }}" draggable="true"
    data-tutorial-id="{{ $tutorial->id }}">
    <div class="thumbnail {{ $isStreamable() }}" data-tutorial-id="{{ $tutorial->id }}">
        @if ($isStreamable == '')
            <i class="bi bi-lock-fill"></i>

        @else
            <i class="bi bi-play-fill"></i>
        @endif
    </div>
    <div class="details">
        <p class="title">{{ $tutorial->title }}</p>
        <input value="{{ $tutorial->title }}" type="text" name="title" id="title">
    </div>
    @if ($canModify())
        <div class="control">
            <i class="bi bi-x-circle-fill tutorial-deleter" data-bs-toggle="modal"
                data-bs-target="#tutorial-delete-modal" data-tutorial-id="{{ $tutorial->id }}"></i>
            <i class="bi bi-pencil-square tutorial-title-editor" data-tutorial-id="{{ $tutorial->id }}"></i>
            <i class="bi bi-check2 tutorial-title-save" style="color: #0FA958; display:none"></i>
        </div>
    @endif
</div>
