import axios from "axios";
import { Modal } from "bootstrap";
import Popup from '../asset/PopupHandler'
const csrf = document.head.querySelector("meta[name='_token']").content;
const modal_element = document.getElementById('create')
const close_modal = document.getElementById('close-modal')
const create_post_button = document.getElementById('create-post-button')
const post_image = document.getElementById('post-image')
const create_question_button = document.getElementById('create-question-button')
const show_questions = document.getElementById('show-questions')
const show_posts = document.getElementById('show-posts')
const posts_box = document.querySelector('.posts-box')
const questions_box = document.querySelector('.questions-box')
const popup = new Popup()
const modal = new Modal(modal_element)
let images = {};
let editor;
create_post_button.addEventListener('click', () => {
    modal.show()
    document.getElementById('create-post').classList.remove('hide')
})

create_question_button.addEventListener('click', (e) => {
    const edit_box = document.getElementById('content')
    document.getElementById('create-question').classList.remove('hide')
    if (!editor) {
        ClassicEditor.create(edit_box, {
            toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
            simpleUpload: {
                uploadUrl: `https://skillshare.com/${forum.id}/save/image`,
                withCredentials: true,
                headers: {
                    'X-CSRF-TOKEN': window.csrf,
                }
            }
        }).then(ckeditor => { editor = ckeditor })
            .catch(error => console.log(error))
    }
    modal.show()
})
post_image.addEventListener('change', (e) => {
    if (e.target.files.length > 3) {
        popup.addPopup('You can not selecet more then 3 files')
        e.target.value = '';
        return false;
    }
    [...e.target.files].forEach(image => {
        if (image.size > 1024 * 1027 * 2) {
            popup.addPopup(`This '${image.name}' file is larger than 2mb.`)
            return;
        }
        document.querySelector('.image-u-box').classList.add('hide')
        document.querySelector('.image-u-progress').classList.remove('hide')

        let form_data = new FormData()
        form_data.append('upload', image)
        axios({
            url: `/${forum.id}/save/image`,
            method: 'post',
            data: form_data,
        })
            .then(
                res => {
                    images[Object.keys(images).length + 1] = res.data.url
                    e.target.value = ''
                },
                err => {
                    popup.addPopup('something went wrong please try again')
                },
            )
            .finally(() => {
                document.querySelector('.image-u-box').classList.remove('hide')
                document.querySelector('.image-u-progress').classList.add('hide')
            })
    })
})
close_modal.addEventListener('click', () => {
    //clear up
    document.getElementById('create-question').classList.add('hide')
    document.getElementById('create-post').classList.add('hide')
    modal.hide()
})
document.getElementById('create-question').addEventListener('submit', (e) => {
    // e.preventDefault()
    const parser = new DOMParser().parseFromString(editor.getData(), 'text/html')
    const images = parser.querySelectorAll('img')
    if (images.length > 3) {
        e.preventDefault()
        popup.addPopup('You can not upload more then 3 image')
        return false;
    }
    if (editor.getData().length > 1500) {
        e.preventDefault()
        popup.addPopup('Question must be completed under 1000 charecters ')
        return false;
    }
    if (images.length > 0) {
        let srclist = document.createElement('input')
        let sources = {}
        srclist.type = 'hidden'
        srclist.name = 'images'
        images.forEach(element => {
            const { src } = element
            sources[Object.keys(sources).length + 1] = src
        })
        sources = JSON.stringify(sources)
        srclist.value = sources
        e.target.prepend(srclist)
    }
    console.log(images)
})

document.getElementById('create-post').addEventListener('submit', () => {
    // e.preventDefault()
    if (Object.keys(images).length > 0) {
        let srclist = document.createElement('input')
        srclist.type = 'hidden'
        srclist.name = 'images'
        srclist.value = JSON.stringify(images)
        document.getElementById('create-post').append(srclist)
    }
    console.log(images)
})

//show 

show_posts.addEventListener('click', (e) => {
    posts_box.classList.remove('hide')
    questions_box.classList.add('hide')
})

show_questions.addEventListener('click', (e) => {
    posts_box.classList.add('hide')
    questions_box.classList.remove('hide')

})

