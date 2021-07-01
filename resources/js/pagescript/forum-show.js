import { Modal } from "bootstrap";
import Popup from '../asset/PopupHandler'
const modal_element = document.getElementById('create')
const close_modal = document.getElementById('close-modal')
const create_post_button = document.getElementById('create-post-button')
const create_question_button = document.getElementById('create-question-button')
const popup = new Popup()
const modal = new Modal(modal_element)
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
            // Image : {},
            simpleUpload: {
                uploadUrl: `http://skillshare.com/${forum.id}/save/image`,
                withCredentials: true,
                headers: {
                    'X-CSRF-TOKEN': window.csrf,
                }
            }
        }).then(ckeditor => {editor = ckeditor})
            .catch(error => console.log(error))
    }
    modal.show()
})
close_modal.addEventListener('click', () => {
    //clear up
    document.getElementById('create-question').classList.add('hide')
    document.getElementById('create-post').classList.add('hide')
    modal.hide()
})
document.getElementById('create-question').addEventListener('submit', (e) => {
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
            const {src} = element
            sources[Object.keys(sources).length + 1] = src
        })
        sources = JSON.stringify(sources)
        srclist.value = sources
        e.target.prepend(srclist)
    }
})