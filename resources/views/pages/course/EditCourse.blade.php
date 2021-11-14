@extends('../Layout/Layout')

@section('title', 'edit course')

@section('headers')
    <link rel="stylesheet" href="{{ asset('css/course_edit.css') }}">
@endsection

@section('body')
    <form class="course-edit-form" action={{ route('update.course', ['course' => $course->id]) }}>
        <div class="title-wrapper">
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
        </div>
        <div class="price-wrapper">
            <label for="price">Price</label>
            <input type="number" name="price" id="price">
        </div>
        <div class="catagories-wrapper">
            <x-check-box id="catagories" name="catagories" label="Select catagories" :options="$catagories" />
        </div>
        <div class="description-wrapper">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="10"></textarea>
        </div>

        <input type="submit" value="Save changes">
    </form>
@endsection
@section('scripts')
    <script src="{{ asset('./js/ckeditor5/build/ckeditor.js') }}"></script>
    <script>
        const course = @json($course);
    </script>
    <script src="{{ asset('js/course_edit.js') }}"></script>
@endsection
