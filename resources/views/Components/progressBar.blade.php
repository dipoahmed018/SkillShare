@props(['pauseable' => false, 'cancelable' => true])

<div class="upload-control">
    <div class="progress">
        <div class="progress-bar"></div>
        <span class="progress-value">0%</span>
    </div>
    <div class="controls">
        @if ($pauseable)
        <button class="resume-upload">resume</button>
        <button class="pause-upload">pause</button>
        @endif
        @if ($cancelable)
        <button class="cancel-upload">cancel</button>
        @endif
    </div>
</div>