
const answer_input_box = document.getElementById('answer-input')
console.log(answer_input_box)
let answer_editor;
ClassicEditor.create(answer_input_box, {
    toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'image'],
    simpleUpload: {
        uploadUrl: `/save/image`,
        withCredentials: true,
        headers: {
            'X-CSRF-TOKEN': window.csrf,
        }
    }

}).then(ckeditor => answer_editor = ckeditor)
    .catch(error => console.log(error))


// vote control

const vote_buttons = [...document.getElementsByClassName('vote-control')]
const arrow_up = "bi bi-arrow-up-square",
    arrow_down = "bi bi-arrow-down-square",
    arrow_up_fill = "bi bi-arrow-up-square-fill",
    arrow_down_fill = "bi bi-arrow-down-square-fill";

vote_buttons.forEach(element => {
    element.addEventListener('click', (e) => {
        let vote_type = e.target.getAttribute('data-vote-type')
        let post_id = e.target.getAttribute('data-post-id')

        vote_control(e.target.parentElement, vote_type, post_id)
    })
})

async function vote_control(vote_box, vote_type, post_id) {

    const [increment_btn, decrement_btn] = [...vote_box.getElementsByClassName('vote-control')]
    const vote_count_box = vote_box.querySelector('.vote-count')
    const vote_count = parseInt(vote_count_box.innerText)

    //update vote
    fetch(`/${post_id}/post/vote`, {
        method: 'put',
        body: JSON.stringify({ 'type': vote_type }),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrf,
        }
    })
        .then(res => res.ok ? res.json() : Promise.reject(res.json()))
        .then(data => {
            switch (data?.vote?.vote_type) {
                case 'increment':
                    increment_btn.className = increment_btn.className.replace(arrow_up, arrow_up_fill)
                    decrement_btn.className = decrement_btn.className.replace(arrow_down_fill, arrow_down)
                    break;

                case 'decrement':
                    decrement_btn.className = decrement_btn.className.replace(arrow_down, arrow_down_fill)
                    increment_btn.className = increment_btn.className.replace(arrow_up_fill, arrow_up)
                    break;

                case undefined:
                    increment_btn.className = increment_btn.className.replace(arrow_up_fill, arrow_up)
                    decrement_btn.className = decrement_btn.className.replace(arrow_down_fill, arrow_down)
                    break;

                default:
                    break;
            }
            vote_count_box.innerText = data?.vote_count
        })
        .catch(err => console.log(err));

}


