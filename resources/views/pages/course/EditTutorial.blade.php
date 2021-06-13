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
                <input class="form-control" value="{{$tutorial->title}}" type="text" name="title" id="title"><br>
                @error('title')
                    <div class="error-box">
                        <p>
                            {{ $message }}
                        </p>
                    </div>
                @enderror
                <label for="change position">Change Postion</label>
                <select class="form-control" name="position" id="position">
                    <option value="{{ $tutorial->order }}">position</option>
                    @foreach ($course->tutorials as $item)
                        @continue($item->id == $tutorial->id)
                        <option value={{ $loop->iteration}}>{{ $loop->iteration}}</option>
                    @endforeach
                </select><br>
                @error('position')
                    <div class="error-box">
                        <p>
                            {{ $message }}
                        </p>
                    </div>
                @enderror
                <input type="submit" value="save" class="btn btn-primary">
                @error('invalid')
                    {{ $message }}
                @enderror
            </form>

            {{-- @dump($errors) --}}

        @endif
    </div>
@endsection
