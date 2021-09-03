<header>
    <div class="home">home</div>
    <div class="serach">
        <form action="" method="get">
            <input type="text" name="search" id="search">
            <button type="submit" id="search_submit">search</button>
        </form>
    </div>
    <ul>
        <li>courses</li>
        <li>profile</li>
    </ul>
</header>

@push('scripts')    
<script>
    //serach box
    const search_input = document.getElementById('search');

    search_input.addEventListener('input', (e) => {
        const input = e.target.value
        if(input.length >= 4) {
            fetch('/course/index', {
                method: 'get',
                headers : {
                    'X-CSRF-TOKEN' : window.csrf,
                }
            }).then(res => {
                console.log(res);
            })
            .catch(err => {
                console.log(err)
            })
        }
    })
    
</script>
@endpush