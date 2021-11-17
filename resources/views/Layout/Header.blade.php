<header class="header">
    <nav>
        <a class="header-items home link" href="/dashboard">Home </a>
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

                    <x-check-box id="catagory" name="catagory" :options="$catagories" label="Select catagories" />

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
            <div class="header-items profile user-info">
                <div class="profile-icon">d</div>
                <div class="profile-overlay"></div>
                <div class="profile-links">
                    <a href="/profile/{{$user->id}}">My profile</a>
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

@push('menu')
    <script>
        //serach box
        const search_input = document.getElementById('search');
        const suggestion_box = document.getElementById('suggestion-box')

        search_input.addEventListener('input', (e) => {
            const input = e.target.value
            if (input.length >= 4) {
                fetch(`/courses?suggestion=true&search=${input}`, {
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': window.csrf,
                        }
                    })
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(res => {
                        //slice the suggestions to 9
                        const data = res.data?.slice(1, 10)
                        //clear suggestion box 
                        while (child = suggestion_box.firstChild) {
                            suggestion_box.removeChild(child)
                        }
                        data?.forEach(element => {
                            const wrapper = document.createElement('a');
                            wrapper.href = `/show/course/${element.id}`
                            wrapper.innerText = element.title.length > 30 ? element.title.slice(1, 30) +
                                '...' : element.title
                            wrapper.classList.add('searched-item')
                            suggestion_box.appendChild(wrapper);
                        });
                    })
                    .catch(err => {
                        console.log(err)
                    })

                //suggestion box hider listener
                const hideSuggestion = (e) => {
                    if (!suggestion_box.contains(e.target)) {
                        document.removeEventListener('click', hideSuggestion)
                        suggestion_box.classList.add('hide');
                    }
                }

                //adding event listerner to document to hide the suggestion box onclick
                suggestion_box.classList.contains('hide') ? document.addEventListener('click', hideSuggestion) :
                    null;
                suggestion_box.classList.remove('hide')
                return true;
            }
            suggestion_box.classList.add('hide')
        })

        //filter box
        const filter_box = document.querySelector('.filters')
        const filter_button = document.querySelector('.filter-button')
        const filter_form = document.getElementById('filter-form')

        //toogle filter box
        filter_button.addEventListener('click', () => {
            if (filter_box.classList.contains('filters-hidden')) {
                filter_button.classList.add('close-filter')
                filter_box.classList.remove('filters-hidden')
                filter_box.classList.add('filters-show')
            } else {
                filter_button.classList.remove('close-filter')
                filter_box.classList.remove('filters-show')
                filter_box.classList.add('filters-hidden')
            }
        })

        //dual slicer price range
        const lowerSlider = document.querySelector('#lower')
        const upperSlider = document.querySelector('#upper')
        const min_price = document.getElementById('min_price')
        const max_price = document.getElementById('max_price')


        const range_input_listener = (e) => {
            let lowerVal = parseInt(lowerSlider.value);
            let upperVal = parseInt(upperSlider.value);

            if (upperVal < lowerVal + 15) {
                upperSlider.value = lowerVal + 15
            }
            if (lowerVal > upperVal - 15) {
                lowerSlider.value = upperVal - 15
            }
            //set max min price input 
            min_price.value = lowerSlider.value * 100
            max_price.value = upperSlider.value * 100
        }
        upperSlider.addEventListener('input', range_input_listener)
        lowerSlider.addEventListener('input', range_input_listener)

        //review input
        const review_inputs = document.querySelectorAll('.review-stars');
        review_inputs.forEach(elm => {
            elm.addEventListener('click', (e) => {
                const target = e.target.getAttribute('data-stars') ? e.target : e.target.parentElement;
                Array.prototype.find.call(review_inputs, (elm) => elm.classList.contains('selected'))
                    .classList.remove('selected')
                target.classList.add('selected');
                document.querySelector('[name="review"').value = target.getAttribute('data-stars');
            })
            document.querySelector('[name="review"').value = elm.classList.contains('selected') ? elm.getAttribute(
                'data-stars') : 10;
        })


        //mobile sider bar toogle
        const close_btn = document.querySelector('.sidebar-cls-icn')
        const open_btn = document.querySelector('.sidebar-opn-icn')
        const side_bar = document.querySelector('.sidebar')
        const toggleSideBar = (e) => {
            if (side_bar.classList.contains('sidebar-hidden')) {
                side_bar.classList.remove('sidebar-hidden')
                side_bar.classList.add('sidebar-show')
            } else {
                side_bar.classList.add('sidebar-hidden')
                side_bar.classList.remove('sidebar-show')
            }
        }
        open_btn.addEventListener('click', toggleSideBar)
        close_btn.addEventListener('click', toggleSideBar)
    </script>
@endpush
