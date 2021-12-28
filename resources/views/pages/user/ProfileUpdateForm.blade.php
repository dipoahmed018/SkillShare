
    <form id="name-change" action={{ route('user.update') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf
            <label for="name">Name: {{$user->name}}</label>
            <input type="text" name="name" id="name" placeholder="type your name" maxlength="50" >
            <div class="error-box"> </div>
            <input type="submit" value="change name">
    </form>

    <form id="user_update_form_name" action={{ route('user.update') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>
            <legend>gender</legend>
            <h1>I am a {{ $user->gender }}</h1>
            <label for="gender">male</label>
            <input type="radio" name="gender" id="male" value="male"><br>
            <label for="gender">female</label>
            <input type="radio" name="gender" id="female" value="female"><br>
            @error('gender')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change gender">
        </fieldset>
    </form>

    <form id="user_update_form_name" action={{ route('user.update') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>
            <legend>birthdate</legend>
            <h1>my birthdate is {{ $user->birthdate }}</h1>
            <input type="date" name="birthdate" id="birthdate">
            @error('birthdate')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change birthdate">
        </fieldset>
    </form>

    <form id="password_change" action={{ route('user.update.password') }} method="post">
        <input type="hidden" name="_method" value="put">
        @csrf

        <fieldset>
            <legend>change password</legend>
            <h1>change password</h1>
            <label for="ole_password">old password</label>
            <input type="password" name="old_password" id="old_password"><br>
            @error('old_password')
                <p>{{ $message }}</p>
            @enderror
            <label for="password">new password</label>
            <input type="password" name="password" id="password" required><br>
            <label for="password_confirmation" required>confirm password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required><br>
            @error('password')
                <p>{{ $message }}</p>
            @enderror
            <input type="submit" value="change password">
        </fieldset>
    </form>
    <fieldset>
        <legend>change email</legend>
            <form action={{ route('user.update.email') }} method="post">
                @csrf
                <input type="hidden" name="_method" value="put">
                <input type="text" name="email" id="email">
                <input type="submit" value="change email">
            </form>
    </fieldset>
