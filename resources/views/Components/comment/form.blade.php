@props(['cancelable' => true, 'placeholder' => 'Type your comment here', 'value' => ''])
    <form {{ $attributes->merge(['class' => 'comment-form']) }} action="/comment/create" method="POST">
        <input type="text" name="content" placeholder="{{ $placeholder }}" value="{{$value}}" minlength="5" maxlength="2000" required>
        <button class="comment-submit" type="submit" title="save changes"></button>
        @if ($cancelable)
            <i class="bi bi-x-lg comment-form-cancel"></i>
        @endif
    </form>
