import Dayjs from "dayjs"
let reletiveTime = require('dayjs/plugin/relativeTime')
Dayjs.extend(reletiveTime)


//question and answer edit
const answer_edit_box = document.getElementById('answer-edit-box')
const question_edit_box = document.getElementById('question-edit-box')
const answer_create_box = document.getElementById('answer-create-box')

let answer_editor,
    question_editor,
    answer_create_editor;

if (answer_edit_box) {
    ClassicEditor.create(answer_edit_box, {
        toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
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
if (question_edit_box) {
    ClassicEditor.create(question_edit_box, {
        toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
        simpleUpload: {
            uploadUrl: `/save/image`,
            withCredentials: true,
            headers: {
                'X-CSRF-TOKEN': window.csrf,
            }
        }

    }).then(ckeditor => question_editor = ckeditor)
        .catch(error => console.log(error))
}
if (answer_create_box) {
    ClassicEditor.create(answer_create_box, {
        toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
        simpleUpload: {
            uploadUrl: `/save/image`,
            withCredentials: true,
            headers: {
                'X-CSRF-TOKEN': window.csrf,
            }
        }

    }).then(ckeditor => answer_create_editor = ckeditor)
        .catch(error => console.log(error))
}

const answer_create_form = document.getElementById('create-answer')
answer_create_form.addEventListener('submit', createAnswer)

function createAnswer(e) {

    const data = answer_editor.getData()
    console.log(data)
    const images = Image_picker(data, 4)
    const filter = Filter_length(data)
    const image_add = Inject_images(images, 'images', e.target);

    if (images instanceof Error) { e.preventDefault(); return popup.addPopup(images.message) }
    if (filter instanceof Error) { e.preventDefault(); return popup.addPopup(filter.message) }
    if (image_add instanceof Error) { e.preventDefault(); return popup.addPopup(image_add.message) }
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
const comment_creator_forms = [...document.getElementsByClassName('comment-create')]

//reply creator
reply_form_show_btns?.forEach((element) => {
    element.addEventListener('click', scrollToCommentCreateForm)
})
function scrollToCommentCreateForm(e) {
    const post_id = e.target.getAttribute('data-commentable-id')
    const reference_id = e.target.getAttribute('data-reference-id')
    const reference_name = e.target.getAttribute('data-reference-name')
    const comment_creator_form = document.getElementById(`comment-create-${post_id}`),
        comment_input = comment_creator_form.querySelector('[name="content"]')

    comment_creator_form.setAttribute('data-references', JSON.stringify([reference_id]));
    comment_input.placeholder = `Reply to ${reference_name}`
    comment_creator_form.scrollIntoView()
    comment_input.focus()
}

//comment creator
const comment_creator_btns = [...document.getElementsByClassName('comment-create-btn')]
comment_creator_btns.forEach(element => {
    element.addEventListener('click', (e) => {
        const post_id = e.target.getAttribute('data-post-id')
        const comment_creator_form = document.getElementById(`comment-create-${post_id}`)
        const comment_input = comment_creator_form.querySelector('[name="content"]')

        comment_creator_form.setAttribute('data-references', null)
        comment_input.placeholder = 'Type your comment here'
        comment_input.focus()
    })
})
comment_creator_forms.forEach(element => {
    element.addEventListener('submit', createComment)
})

function createComment(e) {
    e.preventDefault()
    const comment_input = e.target.querySelector('[name="content"')
    const references = JSON.parse(e.target.getAttribute('data-references'))
    const post_id = e.target.getAttribute('data-commentable-id')
    const post_card = document.querySelector(`.post-${post_id}`)
    const comments_box = post_card.querySelector(`.comments`)
    let form_data = {
        'commentable': question?.id,
        'type': 'parent',
        'content': comment_input.value,
    }
    if (references) { form_data['references'] = references }

    fetch('/comment/create', {
        method: 'post',
        body: JSON.stringify(form_data),
        headers: {
            'X-CSRF-TOKEN': window.csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    }).then(res => res.ok ? res.json() : Promise.reject(res.json()))
        .then(comment => makeCommentHTML(comment, comments_box))
        .catch(err => console.log(err));
}


//load more comment

const load_more_btn = [...document.getElementsByClassName('load-more')]

load_more_btn.forEach((element) => {
    element.addEventListener('click', (e) => {
        const post_id = e.target.getAttribute('data-post-id')
        const post_element = document.querySelector(`.post-${post_id}`)
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
                    makeCommentHTML(comment, post_element.querySelector('.comments'))
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

//save comment editor form data to server
const comment_editor_forms = [...document.getElementsByClassName('comment-edit')]
comment_editor_forms?.forEach(element => {
    element.addEventListener('submit', updateComment);
})

function updateComment(e) {
    e.preventDefault()
    const edit_form = e.target;
    const comment_id = e.target.getAttribute('data-comment-id')
    const references = JSON.parse(e.target.getAttribute('data-references'))
    const comment_input = edit_form.querySelector('[name="content"]')

    let form_data = {
        'content': comment_input.value,
    }

    if (references) { form_data['references'] = references }

    fetch(`/${comment_id}/comment/update`, {
        method: 'PUT',
        body: JSON.stringify(form_data),
        headers: {
            "Accept": 'application/json',
            "Content-Type": 'application/json',
            'X-CSRF-TOKEN': window.csrf,
        },
    })
        .then(res => res.ok ? res.json() : Promise.reject(res.json()))
        .then(comment => {
            //handel references
            const [content_wrapper, reference_box, content] = document.getElementById(`comment-${comment_id}`).querySelectorAll(`.content-wrapper, .references, .content`)
            let references_element = '';
            let reference_ids = [];
            if (comment?.reference_users) {
                comment.reference_users?.forEach(user => {
                    reference_ids.push(user.id)
                    references_element = `<a href="${user.id}/profile">@${user.name}</a>` + references_element
                })
            }
            console.log(reference_ids)
            edit_form.setAttribute('data-references', reference_ids.length > 0 ? JSON.stringify(reference_ids) : null);
            reference_box.innerHTML = references_element

            //handel content
            content.innerText = comment.content

            //toggle form and content visibility
            content_wrapper.classList.remove('hide')
            edit_form.classList.add('hide')
        })
        .catch(err => console.log(err))
}


//delete comment
const comment_delete_modal = document.getElementById('delete-comment-confirmation'),
    yes_button = comment_delete_modal.querySelector('.yes');

comment_delete_modal.addEventListener('show.bs.modal', e => {
    const comment_id = e.relatedTarget.getAttribute('data-comment-id')
    yes_button.setAttribute('data-comment-id', comment_id)
})
yes_button.addEventListener('click', e => {
    console.log('hello')
    const comment_id = e.target.getAttribute('data-comment-id')
    fetch(`/${comment_id}/comment/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': window.csrf,
            'Accept': 'application/json',
        }
    })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
            document.getElementById(`comment-${comment_id}`)?.remove()
        })
        .catch(err => console.log(err))
})

//templates 
function makeCommentHTML(comment, append_to = null) {
    let references = ''
    let profile = ''
    let comment_editor = ''
    let comment_editor_btn = ''
    let comment_deleter_btn = ''
    let references_ids = [];
    //making refernce useres html
    comment.reference_users?.forEach(user => {
        references_ids.push(user.id)
        references = `<a href="${user.id}/profile">@${user.name}</a>` + references
    })
    references_ids = references_ids.length < 1 ? null : references_ids;
    //add owner profile box
    if (comment.owner_details.profile_picture) {
        profile = `<img class="profile-image image" src="${comment.owner_details.profile_picture.file_link} alt="avatar">`

    } else {
        profile = `<div class="profile-text">${comment.owner_details.name.charAt(0)}</div>`
    }

    //add comment editor and deleter
    if (user.id == comment.owner_details.id) {
        //editor
        comment_editor =
            `<form class="comment-form comment-edit hide"
         id="comment-edit-${comment.id}" data-comment-id=${comment.id} data-references=${JSON.stringify(references_ids)}
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
                    <span class="reply-creator-show" data-comment-id="${comment.id}" data-commentable-id="${comment.commentable_id}"
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
            comment_editor_form = comment_dom_card.querySelector(`.comment-edit`),
            comment_form_cancel = comment_dom_card.querySelector('.comment-form-cancel');

        comment_reply_shower?.addEventListener('click', scrollToCommentCreateForm)
        commnet_editor_shower?.addEventListener('click', showCommentEditForm)
        comment_form_cancel?.addEventListener('click', hideCommentEditForm)
        comment_editor_form?.addEventListener('submit', updateComment)

        return true;
    }
    return comment_card
}