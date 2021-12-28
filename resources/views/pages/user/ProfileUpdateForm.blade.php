<form class="profile-update-form" id="change-name" action={{ route('user.update') }} method="post">
    <input type="hidden" name="_method" value="put">
    @csrf
    <label for="name">Name: {{ $user->name }}</label>
    <input type="text" name="name" id="name" placeholder="type your name" maxlength="50">
    <div class="error-box"> </div>
    <input type="submit" value="change name">
</form>

<form class="profile-update-form" id="change-gender" action={{ route('user.update') }} method="post">
    <input type="hidden" name="_method" value="put">
    @csrf
    <h6>Change gender</h6>
    <div class="genders">
        <label for="gender">male</label>
        <input type="radio" name="gender" id="male" value="male">
        <label for="gender">female</label>
        <input type="radio" name="gender" id="female" value="female"><br>
    </div>
    <div class="error-box"> </div>
    <input type="submit" value="change gender">
</form>

<form class="profile-update-form" id="user_update_form_name" action={{ route('user.update') }} method="post">
    <input type="hidden" name="_method" value="put">
    @csrf
    <h6>Changer your birthdate</h6>
    <input type="date" name="birthdate" id="birthdate">
    @error('birthdate')
        <p>{{ $message }}</p>
    @enderror
    <input type="submit" value="change birthdate">
</form>

<form class="profile-update-form" id="password_change" action={{ route('user.update.password') }} method="post">
    <input type="hidden" name="_method" value="put">
    @csrf
    <h6>Changer your Password</h6>

    <label for="ole_password">old password</label>
    <input type="password" name="old_password" id="old_password">
    <div class="error-box old-password-error"></div>

    <label for="password">new password</label>
    <input type="password" name="password" id="password" required>

    <label for="password_confirmation" required>confirm password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" required>
    <div class="new-password-error error-box"></div>
    @error('password')
        <p>{{ $message }}</p>
    @enderror
    <input type="submit" value="change password">
</form>

<form class="profile-update-form" id="change-email" action={{ route('user.update.email') }} method="post">
    <h6>Changer your email address</h6>
    @csrf
    <input type="hidden" name="_method" value="put">
    <input type="text" name="email" id="email">
    <input type="submit" value="change email">
</form>
