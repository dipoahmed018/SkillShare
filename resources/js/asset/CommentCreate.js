import { Modal } from "bootstrap";

const modal_box = document.getElementById('create-comment-modal')
const comment_edit_box = document.getElementById('comment-content')
const error = document.querySelector('.comment-error');
const comment_form = document.getElementById('create-comment-form')
const edit_buttons = document.querySelectorAll('.edit-comment')
const create_comment = document.querySelectorAll('.create-comment')
const close_modal = document.querySelectorAll('.close-comment-modal')
//unique identifier 
let comment_editor;
let url;
let commentable_id;
let comment_type;
let action;
let comment_id;
//button to open the modal button need some data attribute about the post or comment can be opened form any component
const comment_modal = new Modal(modal_box)
edit_buttons.forEach(element => {
    element.addEventListener('click', (e) => modal_opener(e))
})
create_comment.forEach(element => {
    element.addEventListener('click', (e) => modal_opener(e))
})
close_modal.forEach(element => {
    element.addEventListener('click', () => comment_modal.hide())
})
const modal_opener = (e) => {
    commentable_id = e.target.getAttribute('data-commentable-id')
    comment_id = e.target.getAttribute('data-comment-id')
    comment_type = e.target.getAttribute('data-comment-type')
    action = e.target.getAttribute('data-bs-action')
    if (action == 'create') {
        comment_editor.setData('');
        url = `/comment/create`
    } else {
        url = `/${comment_id}/comment/update`
        content = document.getElementById(`${comment_id}-comment-wrapper`).querySelector('.content').innerHTML
        comment_editor.setData(content)
    }
    comment_modal.show()
}
comment_form.addEventListener('submit', (e) => {
    e.preventDefault()
    //sources is the object for storing the image urls
    let sources = {}
    if (comment_editor) {
        //fiter and pick the images from the content of comment_editor
        const data = comment_editor.getData()
        if (data.length < 1) {
            error.innerHTML = 'content must not be empty'
            return;
        }

        const parser = new DOMParser().parseFromString(data, 'text/html')
        const images = parser.querySelectorAll('img')
        if (images.length > 3) {
            e.preventDefault()
            error.innerHTML = 'you can not use more then 3 image'
        }
        if (comment_editor.getData().length > 1000) {
            e.preventDefault()
            error.innerHTML = 'too much content'
            return false;
        }
        if (images.length > 0) {
            images.forEach(element => {
                const { src } = element
                sources[Object.keys(sources).length + 1] = src
            })
            sources = JSON.stringify(sources)
        } else {
            sources = null
        }
    }
    //form_data is the object that is sent to server
    let form_data = {}

    //change details of form_data based on action type
    if (action == 'create') {
        form_data['type'] = comment_type
        sources ? form_data['images'] = sources : null
        form_data['commentable'] = commentable_id
        form_data['content'] = comment_editor.getData()

    } else {
        sources ? form_data['images'] = sources : null
        form_data['content'] = comment_editor.getData()
    }
    form_data = JSON.stringify(form_data)
    //make the request to server
    fetch(url, {
        method: action == 'create' ? 'POST' : 'PUT',
        body: form_data,
        headers: {
            'X-CSRF-TOKEN': window.csrf,
            'content-type': 'application/json'
        }
    }).then(res => res.json())
        .then(comment => {
            action == 'create' ? attach(comment) : update_comment(comment)
        })
        .catch(err => console.log(err))

    //hide the modal 
    comment_modal.hide()
})

//update commentbox inside dom
const update_comment = (comment) => {
    const box = document.getElementById(`${comment.id}-comment-wrapper`)
    box.querySelector('.content').innerHTML = comment.content
}

//comment tamplate creator and attaching it to dom
const attach = (comment) => {
    const box = document.getElementById(`${comment.commentable_type == 'reply' ? comment.commentable_id : commentable_id}-comment-box`)
    const wrapper = document.querySelector('.tamplate-comment').cloneNode(true)
    const content = wrapper.querySelector('.content')
    const owner = wrapper.querySelector('.comment-owner')
    const like = wrapper.querySelector('.like-comment')
    const create_button = wrapper.querySelector('.show-comment-creator')
    const reply_box = wrapper.querySelector('.reply-box')
    const delete_comment = wrapper.querySelector('.delete-comment')
    const edit_comment = wrapper.querySelector('.edit-comment')
    wrapper.classList.remove('hide')
    wrapper.id = `${comment.id}-comment-wrapper`
    owner.innerHTML = comment.owner
    content.innerHTML = comment.content
    create_button.setAttribute('data-commentable-id', comment.id)
    like.setAttribute('data-commentable-id', comment.id)
    delete_comment.setAttribute('data-comment-id', comment.id)
    edit_comment.setAttribute('data-comment-id', comment.id)
    reply_box.id = `${comment.id}-comment-box`
    box.append(wrapper)

    //listener 
    delete_comment.addEventListener('click', (e) => comment_deleter(e))
    edit_comment.addEventListener('click', (e) => modal_opener(e))
}

//set event listener to all the delete buttons for deleting a comment
document.querySelectorAll('.delete-comment').forEach((element) => {
    element.addEventListener('click', (e) => comment_deleter(e))
})

//delete a comment
const comment_deleter = (e) => {
    const id = e.target.getAttribute('data-comment-id')
    fetch(`/${id}/comment/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': window.csrf
        }
    }).then(res => document.getElementById(`${id}-comment-wrapper`).remove())
    .catch(err => {
        console.log(err)
    })
}

//initializeing the editor box and setting it to a veriable so it can be accessabele throughout the script
document.addEventListener('DOMContentLoaded', () => {
    ClassicEditor.create(comment_edit_box, {
        toolbar: ['undo', 'redo', '|', 'bold', 'italic',
            'blockQuote', '|', 'ImageUpload'
        ],
        simpleUpload: {
            uploadUrl: `/save/comment/image`,
            withCredentials: true,
            headers: {
                'X-CSRF-TOKEN': window.csrf,
            }
        }
    }).then(ckeditor => {
        comment_editor = ckeditor
    })
        .catch(error => console.log(error))
})