import axios from "axios";
import { Modal } from "bootstrap";
import Popup from '../asset/PopupHandler'
import { Image_picker, Filter_length, Inject_images } from '../asset/CkEditorHelper'
require('../asset/CommentCreate')

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

//this images variable holds the images for post creation
let images = {};

//show the editor on button click and editor is the holder for ck editor instance
let editor;
create_post_button.addEventListener('click', () => {
    modal.show()
    document.getElementById('create-post').classList.remove('hide')
})

//create question for forum
create_question_button.addEventListener('click', (e) => {
    const edit_box = document.getElementById('content')
    document.getElementById('create-question').classList.remove('hide')
    if (!editor) {
        //make an instance of ck editor and assign it to edit_box inside dom and editor variable
        ClassicEditor.create(edit_box, {
            toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
            simpleUpload: {
                uploadUrl: `/save/image`,
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

//upload the images of post on change of file input
post_image.addEventListener('change', (e) => {
    //filter the data of image input
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
            url: `/save/image`,
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
    //clear up the modal
    document.getElementById('create-question').classList.add('hide')
    document.getElementById('create-post').classList.add('hide')
    modal.hide()
})
document.getElementById('create-question').addEventListener('submit', (e) => {
    //parse the content of text editor to html for filtering and picking the image urls
    const data = editor.getData()
    const images = Image_picker(data)
    const filter = Filter_length(data)
    if (typeof images == 'object') {
        Inject_images(images,'images', e.target)
    }
    if (typeof images == 'string') {
        e.preventDefault()
        popup.addPopup(images)
        return false
    }
    if (typeof filter == 'string') {
        e.preventDefault()
        popup.addPopup(filter)
        return false
    }
})

document.getElementById('create-post').addEventListener('submit', () => {
    // on submit create a input for injecting the images urls to upload
    if (Object.keys(images).length > 0) {
        let srclist = document.createElement('input')
        srclist.type = 'hidden'
        srclist.name = 'images'
        srclist.value = JSON.stringify(images)
        document.getElementById('create-post').append(srclist)
        images = {}
    }
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

