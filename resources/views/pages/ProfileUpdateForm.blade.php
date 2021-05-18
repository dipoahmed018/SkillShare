@extends('../Layout/Layout')

@section('title', 'Profile Update')

@section('body')
    {{-- <div class="preview_box" id="image_crop_preview">
        <img id="image_crop" src="" alt="">
        <button id="cancel_upload_image">cancel</button>
        <button id="upload_image">upload</button>
    </div> --}}
    <form id="user_update_form_image" action={{ route('user.update') }} method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="put">
        <fieldset>

            <legend>
                profie picture
            </legend>
            <img width="150px" src={{ $profile_picture }} alt="">
            <input accept=".jpeg, .jpg, .png" type="file" name="profile_picture" id="profile_picture">
            @error('profile_picture')
                <p id="profile_picture_phperror">{{ $message }}</p>
            @enderror

            <p id="profile_picture_scripterror"></p>
            <input type="submit" value="submit">
        </fieldset>
    </form>

    <form id="user_update_form_name" action="POST" method="post">
        <input type="hidden" name="_method" value="put">

        <fieldset>
            <legend>name</legend>
            <h1>{{ $user->name }}</h1>
            <input type="text" name="name" id="name" placeholder="type your name">
            @error('name')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change name">
        </fieldset>
    </form>
@endsection

@section('scripts')
    {{-- <script src={{ asset('js/profile_update.js') }}></script> --}}
@endsection
