@extends('../Layout/Layout')

@section('title', 'Edit Tutorial')

@section('body')
    <div class="container-fluid">
        @if ($tutorial)
            <form class="form-group"
                action={{ route('tutorial.title.edit', ['course' => $course->id, 'tutorial' => $tutorial->id]) }}
                method="post">
                @csrf
                @method('put')
                <label for="tutorial_title">Change Title</label>
                <input class="form-control" type="text" name="tutorial_title" id="tutorial_title"><br>
                <label for="change position">Change Postion</label>
                <select class="form-control" name="position" id="position">
                    <option value="position">position</option>
                    @foreach ($course->tutorials as $item)
                       @continue($item->id == $tutorial->id)
                       <option value={{$loop->iteration}}>{{$loop->iteration}}</option>
                    @endforeach
                </select><br>
                <input type="submit" value="save" class="btn btn-primary">
            </form>
        @endif
    </div>
@endsection
