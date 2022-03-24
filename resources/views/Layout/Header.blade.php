<header class="header h-16 bg-white text-gray-500">
    <nav>
        <a class="header-items home link" href="/dashboard">Home</a>
        <a class="header-items link" href="/courses">Course</a>
        <i class="bi bi-list sidebar-opn-icn"></i>
    </nav>
    <div class="sidebar sidebar-hidden">
        <i class="bi bi-x-lg sidebar-cls-icn"></i>
        <div class="header-items filter">

            <button class="filter-button"></button>

            <div class="filters filters-hidden">
                <form id="filter-form" method="GET" action="/courses">

                    <input type="hidden" name="review">
                    {{-- name changed to components hel --}}
                    <x-checkbox id="catagory" name="catagory" :options="$catagories" label="Select catagories" />

                    <div class="price-range">
                        <span>price:</span>
                        <div class="multi-range">
                            <input type="range" min="0" max="100" value="0" id="lower">
                            <input type="range" min="0" max="100" value="100" id="upper">
                        </div>
                    </div>
                    <div class="price-input">
                        <label for="min_price">min:</label>
                        <input type="text" name="min_price" id="min_price" type="number" value="100">
                        <label for="max_price">max:</label>
                        <input type="text" name="max_price" id="max_price" type="number" value="10000">
                    </div>

                    <div class="review">
                        <p>review:</p>
                        <div data-stars="10" class="review-stars selected">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div data-stars="8" class="review-stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>
                        </div>
                        <div data-stars="6" class="review-stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>
                            <i class="bi bi-star"></i>
                        </div>
                    </div>

                    <button class="submit-filter" type="submit">filter</button>
                </form>
            </div>
        </div>

        <form action="" method="get" class="header-items search">
            <input type="text" name="search" id="search">
            <button type="submit" id="search_submit"></button>
            <div class="suggestion-box hide" id="suggestion-box">
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
                <a href="#">hello world new course</a>
            </div>
        </form>

        @if ($user)
            <div class="header-items notification-box">
                <button class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <i class="bi bi-x hide"></i>
                </button>
                <div class="notifications">
                    <div class="notification">
                        <div class="from-icon">
                            <span>
                                h
                            </span>
                        </div>
                        <p class="notification-card">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    </div>
                </div>
            </div>
            <div class="header-items profile user-info">
                <div class="profile-icon">h</div>
                <div class="profile-overlay"></div>
                <div class="profile-links">
                    <a href="/profile/{{ $user->id }}">My profile</a>
                    <a href="/user/logout">Logout</a>
                </div>
            </div>
        @else
            <div class="authenticate user-info">
                <a class="login" href="/login">Log in</a>
                <a class="register" href="/register">Sign up</a>
            </div>
        @endif
    </div>
</header>
<div class="header-spacing"></div>
