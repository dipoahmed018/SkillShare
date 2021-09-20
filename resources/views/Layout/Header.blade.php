<header class="header">
    <nav>
        <a class="header-items home link" href="/dashbord">Home </a>
        <a class="header-items link" href="/courses">Course</a>
        <i class="bi bi-list sidebar-opn-icn"></i>
    </nav>
    <div class="sidebar">
        <i class="bi bi-x-lg sidebar-cls-icn"></i>
        <div class="header-items filter">
            <button class="filter-button"></button>
            <div class="filters">
                <form action="" method="get" id="filter-form">
                    <input type="hidden" name="review" id="review">
                    <select name="catagory" id="catagory" class="catagories">
                        <option value="default">select catagory</option>
                        <option value="hellowor">hellowo</option>
                    </select>
                    <div class="price-range">
                        <span>price:</span>
                        <div class="multi-range">
                            <input type="range" min="0" max="100" value="0" id="lower">
                            <input type="range" min="0" max="100" value="100" id="upper">
                        </div>
                    </div>
                    <div class="price-input">
                        <label for="min_price">min:</label>
                        <input type="text" name="min" id="min_price" type="number" value="10">
                        <label for="max_price">max:</label>
                        <input type="text" name="max" id="max_price" type="number" value="1000">
                    </div>
                    <div class="review">
                        <p>review:</p>
                        <div data-stars="5" class="review-stars selected">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div data-stars="4" class="review-stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star"></i>
                        </div>
                        <div data-stars="3" class="review-stars">
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
        <div class="header-items profile">
            <div class="profile-icon">d</div>
            <div class="profile-overlay"></div>
            <div class="profile-links">
                <a href="/profile">my profile</a>
                <a href="/user/logout">logout</a>
            </div>
        </div>
    </div>
    {{-- <button class="header-items">Login</button>
    <button class="header-items">Register</button> --}}
</header>

@push('scripts')
    <script>
        //serach box
        const search_input = document.getElementById('search');
        const suggestion_box = document.getElementById('suggestion-box')

        search_input.addEventListener('input', (e) => {
            const input = e.target.value
            if (input.length >= 4) {
                fetch(`/courses?suggestion=true&search_query=${input}`, {
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
            if (filter_box.style.display !== 'block') {
                filter_button.classList.add('close-filter')
                filter_box.style.display = 'block'
            } else {
                filter_button.classList.remove('close-filter')
                filter_box.style.display = 'none'
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

            if (upperVal < lowerVal + 20) {
                upperSlider.value = lowerVal + 20
            }
            if (lowerVal > upperVal - 20) {
                lowerSlider.value = upperVal - 20
            }
            //set max min price input 
            min_price.value = lowerSlider.value * 10
            max_price.value = upperSlider.value * 10
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
                document.getElementById('review').value = target.getAttribute('data-stars');
            })
        })


        //mobile sider bar toogle
        const close_btn = document.querySelector('.sidebar-cls-icn')
        const open_btn = document.querySelector('.sidebar-opn-icn')
        const side_bar = document.querySelector('.sidebar')
        open_btn.addEventListener('click', (e) => side_bar.style.display = 'flex')
        close_btn.addEventListener('click', (e) => side_bar.style.display = 'none')
        //form submit 
        filter_form.addEventListener('submit', (e) => {
            e.preventDefault()

            console.log(upperSlider.value);
        })
    </script>
@endpush
