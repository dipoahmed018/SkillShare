// require('../component/comment/edi')


const answer_input_box = document.getElementById('answer-input')


let answer_editor;
if (answer_input_box) {
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

}

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

function vote_control(vote_box, vote_type, post_id) {

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


//overriding the default befavior of comment creation from comment component
const reply_form_show_btns = [...document.getElementsByClassName('reply-creator-show')]
const comment_creator_form = document.getElementById(`comment-create-${question.id}`),
    comment_input = comment_creator_form.querySelector('[name="content"]');

//reply creator
reply_form_show_btns?.forEach((element) => {
    element.addEventListener('click', (e) => {
        let reference_id = e.target.getAttribute('data-reference-id')
        let reference_name = e.target.getAttribute('data-reference-name')
        comment_creator_form.setAttribute('data-references', JSON.stringify([reference_id]));
        console.log(comment_input)
        comment_input.placeholder = `Reply to ${reference_name}`
        comment_creator_form.scrollIntoView()
        comment_input.focus()
    })
})

//comment creator
const comment_creator_btn = document.querySelector('.comment-create-btn')
comment_creator_btn.addEventListener('click', () => {
    comment_creator_form.setAttribute('data-references', null)
    comment_input.placeholder = 'Type your comment here'
})

comment_creator_form.addEventListener('submit', e => {
    e.preventDefault()
    let references = e.target.getAttribute('data-references');

    let form_data = {
        'commentable': question?.id,
        'type': 'parent',
        'content': comment_input.value,
    }
    if (references) { form_data['references'] = JSON.parse(references) }


    fetch('/comment/create', {
        method: 'post',
        body: JSON.stringify(form_data),
        headers: {
            'X-CSRF-TOKEN': window.csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    }).then(res => res.ok ? res.json() : Promise.reject(res.json()))
        .then(comment => console.log(comment))
        .catch(err => console.log(err));
})
