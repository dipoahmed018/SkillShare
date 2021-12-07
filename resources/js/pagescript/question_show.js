import Dayjs from "dayjs"
let reletiveTime = require('dayjs/plugin/relativeTime')
Dayjs.extend(reletiveTime)


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
    element.addEventListener('click', scrollToCommentCreateForm)
})
function scrollToCommentCreateForm(e) {
    let reference_id = e.target.getAttribute('data-reference-id')
        let reference_name = e.target.getAttribute('data-reference-name')
        comment_creator_form.setAttribute('data-references', JSON.stringify([reference_id]));
        console.log(comment_input)
        comment_input.placeholder = `Reply to ${reference_name}`
        comment_creator_form.scrollIntoView()
        comment_input.focus()
}

//comment creator
const comment_creator_btn = document.querySelector('.comment-create-btn')
comment_creator_btn.addEventListener('click', () => {
    comment_creator_form.setAttribute('data-references', null)
    comment_input.placeholder = 'Type your comment here'
    comment_input.focus()
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
        .then(comment => makeCommentHTML(comment, document.querySelector('.comments')))
        .catch(err => console.log(err));
})


//load more comment

const load_more_btn = [...document.getElementsByClassName('load-more')]

load_more_btn.forEach((element) => {
    element.addEventListener('click', (e) => {
        const post_id = e.target.getAttribute('data-post-id')
        let offset = e.target.getAttribute('data-offset')

        fetch(`/comments/index/${post_id}?offset=${offset ?? 0}`, {
            method: 'get',
            headers: {
                'X-CSRF-TOKEN': window.csrf,
                'Accept': 'application/json',
            }
        })
            .then(res => res.ok ? res.json() : Promise.reject(res.json()))
            .then(data => {
                if (!data.has_more) {
                    e.target.remove()
                }
                data.comments.forEach(comment => {
                    makeCommentHTML(comment, document.querySelector('.comments'))
                });
                e.target.setAttribute('data-offset', parseInt(offset) + 5)
            })
            .catch(err => console.log(err))
    })
})


//edit comment

//show comment editor
const comment_edit_btns = [...document.getElementsByClassName('comment-editor-show')]

comment_edit_btns.forEach(element => {
    element.addEventListener('click', showCommentEditForm)
})

function showCommentEditForm(e) {
    const comment_id = e.target.getAttribute('data-comment-id')
    const comment_card = document.getElementById(`comment-${comment_id}`)
    const edit_form = document.getElementById(`comment-edit-${comment_id}`)
    edit_form.classList.remove('hide')

    comment_card.querySelector('.content-wrapper').classList.add('hide');
}

//hide comment editor
const comment_edit_hide_btns = [...document.getElementsByClassName('comment-form-cancel')]
comment_edit_hide_btns.forEach(element => {
    element.addEventListener('click', hideCommentEditForm)
})

function hideCommentEditForm(e) {
    const edit_form = e.target.parentElement
    const comment_id = edit_form.getAttribute('data-comment-id')
    const comment_card = document.getElementById(`comment-${comment_id}`)
    edit_form.classList.add('hide');

    comment_card.querySelector('.content-wrapper').classList.remove('hide')
}


//templates 
function makeCommentHTML(comment, append_to = null) {
    let references = ''
    let profile = ''
    let comment_editor = ''
    let comment_editor_btn = ''
    let comment_deleter_btn = ''
    //making refernce useres html
    comment.reference_users?.forEach(user => {
        references = `<a href="${user.id}/profile">@${user.name}</a>` + references
    })

    //add owner profile box
    if (comment.owner_details.profile_picture) {
        profile = `<img class="profile-image image" src="${comment.owner_details.profile_picture.file_link} alt="avatar">`

    } else {
        profile = `<div class="profile-text">${comment.owner_details.name.charAt(0)}</div>`
    }

    //add comment editor and deleter
    if (user.id !== comment.owner_details.id) {
        //editor
        comment_editor =
            `<form class="comment-form comment-edit hide"
         id="comment-edit-${comment.id}" data-comment-id=${comment.id}
          action="/comment/create" method="POST">
            <input type="text" name="content" placeholder="Type your comment here"
             value="${comment.content}" minlength="5" maxlength="2000" required>
            <button class="comment-submit" type="submit" title="save changes"></button>
            <i class="bi bi-x-lg comment-form-cancel"></i>
        </form>`

        comment_editor_btn = `<span class="comment-editor-show" data-comment-id=${comment.id} style="cursor: pointer"">Edit</span>`

        //deteler
        comment_deleter_btn = `<span class="comment-delete" style="cursor: pointer" data-comment-id="${comment.id}">Delete</span>`
    }

    const comment_card =
        `<div class="comment-card" id="comment-${comment.id}">
            <div class="comment-content">
                <a class="owner-details" href="/user/${comment.owner_details.id}/profile">             
                    ${profile}
                </a>
                <div class="content-wrapper">
                    <div class="references">
                        ${references}
                    </div>
                    <div class="content">
                        ${comment.content}
                    </div>
                </div>
                ${comment_editor}
            </div>
            <div class="comment-control">
                    ${comment_deleter_btn}
                    ${comment_editor_btn}
                    <span class="reply-creator-show" data-comment-id="${comment.id}"
                    data-reference-id=${comment.owner} data-reference-name="${comment.owner_details.name}"
                    style="cursor: pointer">reply</span>
                    <span class="created-at">${Dayjs(comment.created_at).fromNow()}</span>
            </div>
        </div>`
    if (append_to) {
        append_to.insertAdjacentHTML('beforeend', comment_card)

        //adding the event listeners
        const comment_dom_card = document.getElementById(`comment-${comment.id}`),
            comment_reply_shower = comment_dom_card.querySelector('.reply-creator-show'),
            commnet_editor_shower = comment_dom_card.querySelector('.comment-editor-show'),
            coment_deleter = comment_dom_card.querySelector('.coment-delete'),
            comment_editor_form = comment_dom_card.querySelector('.comment-edit'),
            comment_form_cancel = comment_dom_card.querySelector('.comment-form-cancel'),
            comment_form_submit = comment_dom_card.querySelector('.comment-submit');

            comment_reply_shower.addEventListener('click', scrollToCommentCreateForm)

        // console.log(comment_html)
        // comment_html.
        return true;
    }
    return comment_card
}