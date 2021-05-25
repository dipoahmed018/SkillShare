@extends('../Layout/Layout')

@section('title', 'create-course')

@section('body')
    <div>
        @if (session('status'))
            <form class="form-control create-course" action={{ route('create.course') }} method="post">
                @csrf
                <label class="form-label" for="Title">Title</label><br>
                <input class="form-text" type="text" name="title" id="title"><br>
                @error('title')
                    <p>{{ $message }}</p>
                @enderror
                <label class="form-label" for="Description">Description</label><br>
                <input class="form-text width" type="text" name="description" id="description"><br>
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
                <label class="form-label" for="price">Price</label><br>
                <input class="form-text" type="text" name="price" id="price"><br>
                @error('price')
                    <p>{{ $message }}</p>
                @enderror
                <input class="btn btn-success mt-3" type="submit" value="create course">
            </form>
        @endif

        @if (!session('status') == 'created')
            <form class="form-control" action={{route('update.course.thumblin',[5])}} method="post" enctype="multipart/form-data">
                @csrf
                @error('auth')
                    <p>{{$message}}</p>
                @enderror
                <input accept=".jpg, .jpeg, .png" type="file" name="thumblin" id="thumblin" required><br>
                @error('thumblin')
                    <p>{{$message}}</p>
                @enderror
                <input type="submit" value="Set Thumblin">
            </form>
        @endif
    </div>
@endsection
