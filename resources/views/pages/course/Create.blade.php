@extends('../Layout/Layout')

@section('title', 'create-course')

@section('body')
    <div>
        @if (!session('status'))
            <div class="container">

                <form id='create-course' class="create-course row justify-content-center"
                    action={{ route('create.course') }} method="post">
                    @csrf
                    <div class="col col-md-5">
                        <legend>Course</legend>
                        <label class="form-label" for="Title">Title</label><br>
                        <input required class="form-control form-text" type="text" name="title" id="title"><br>
                        @error('title')
                            <p>{{ $message }}</p>
                        @enderror
                        <label class="form-label" for="Description">Description</label><br>
                        <input required class="form-control form-text" type="text" name="description" id="description"><br>
                        @error('description')
                            <p>{{ $message }}</p>
                        @enderror
                        <label class="form-label" for="price">Price</label><br>
                        <input required class="form-text" type="text" name="price" id="price"><br>
                        @error('price')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col col-md-5">
                        <legend>Forum</legend>
                        <label class="form-label" for="name">Name</label><br>
                        <input class="form-control" required type="text" name="forum_name" id="forum_name"><br>
                        @error('forum_name')
                            <p>{{ $message }}</p>
                        @enderror
                        <label class="form-label" for="forum_description">Forum Description</label><br>
                        <input class="form-control" required type="text" name="forum_description"
                            id="forum_description"><br>
                        @error('forum_description')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>
                    <input class=" align-self-end btn btn-success mt-3" type="submit" value="create course">
                </form>
            </div>
        @endif

        @if (session('status') == 'created' && !session('thumblin'))
            <form class="form-control" action={{ route('update.course.thumblin', [session('course')->id]) }} method="post"
                enctype="multipart/form-data">
                @csrf
                @error('auth')
                    <p>{{ $message }}</p>
                @enderror
                <input accept=".jpg, .jpeg, .png" type="file" name="thumblin" id="thumblin" required><br>
                @error('thumblin')
                    <p>{{ $message }}</p>
                @enderror
                <input type="submit" value="Set Thumblin">
            </form>
        @endif

    </div>
@endsection

@endsection
