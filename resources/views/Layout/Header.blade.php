<header class="header">
    <div class="header-items link">Home</div>
    <div class="header-items link">Course</div>
    <div class="header-items filter">
        <button></button>
        <div class="filters">
            <form action="" method="get">
                <input type="range" name="price" id="price">
            </form>
        </div>
    </div>
    <form action="" method="get" class="header-items search">
        <input type="text" name="search" id="search">
        <button type="submit" id="search_submit"></button>
        <div class="suggestion-box hide" id="suggestion-box">
            <p>hello world new course</p>
            <p>hello world new course</p>
            <p>hello world new course</p>
        </div>
    </form>
    <div class="header-items profile">d</div>
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
                // fetch('/courses?suggestion=true ', {
                //         method: 'get',
                //         headers: {
                //             'X-CSRF-TOKEN': window.csrf,
                //         }
                //     }).then(res => {
                //         console.log(res);
                //     })
                //     .catch(err => {
                //         console.log(err)
                //     })

                //suggestion box hider listener
                const hideSuggestion = (e) => {
                    if (!suggestion_box.contains(e.target)) {
                        document.removeEventListener('click', hideSuggestion)
                        suggestion_box.classList.add('hide');
                    }
                }

                //adding event listerner to document to hide the suggestion box onclick
                suggestion_box.classList.contains('hide') ? document.addEventListener('click', hideSuggestion) : null;
                suggestion_box.classList.remove('hide')
                return true;
            }
            suggestion_box.classList.add('hide')
        })
    </script>
@endpush
