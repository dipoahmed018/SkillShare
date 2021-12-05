@props(['cancelable' => true, 'placeholder' => 'Type your comment here'])
    <form {{ $attributes->merge(['class' => 'comment-form']) }}>
        <input type="hidden" name="stars" value="1">
        <input type="text" name="content" placeholder="{{ $placeholder }}">
        <button class="comment-submit" type="submit" title="save changes"></button>
        @if ($cancelable)
            <i class="bi bi-x-lg comment-form-cancel"></i>
        @endif
    </form>
