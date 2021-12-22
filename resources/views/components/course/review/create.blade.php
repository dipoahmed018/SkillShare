@props(['cancelable' => false, 'display' => 'none'])
<div class="review-create-box" style="display: {{$display}}">
    {{$slot}}
    <form {{$attributes}}>
        <input type="hidden" name="stars" value="1">
        <input type="text" name="content" placeholder="Give a review to this course">
        <button type="submit"></button>
        @if ($cancelable)
        <i class="bi bi-x-lg review-cancel"></i>
        @endif
    </form>
</div>
