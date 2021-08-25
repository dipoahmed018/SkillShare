import { Filter_length, Inject_images, Image_picker } from '../asset/CkEditorHelper'
import { Modal } from 'bootstrap'

let question_editor;
let answer_editor;
const question_forum = document.getElementById('edit-question')
const answer_forum = document.getElementById('edit-answer')
const edit_button = document.getElementById('edit-question-button')
const preview_button = document.getElementById('preview-question-button')
const question_box = document.getElementById('question-box')
const question_content = document.getElementById('question-content')
const question_title = document.getElementById('question-title')
const question_edit_box = document.querySelector('#question-content')
const answer_create_box = document.querySelector('#answer-content')
const post_editor_button = document.getElementsByClassName('post-editor')

//initialize modal form answer create of editing
const modal  = new Modal('#answer-editor')
// answer_create_button.addEventListener('click', (e) => {
//     const type = e.target.getAttribute('data-bs-type') ?? 'create answer'
//     modal.show()
// })
console.log(post_editor_button)


//initialize ck question_editor
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
    }).then(ckeditor => {
        ckeditor.setData(question.content)
        question_editor = ckeditor
    })
        .catch(error => console.log(error))
}

//initialize ck answer_creator
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
    }).then(ckeditor => {
        answer_editor = ckeditor
    })
        .catch(error => console.log(error))
}



//toggole edit box and question shower
edit_button.addEventListener('click', (e) => {
    if (question_forum.classList.contains('hide')) {
        question_forum.classList.remove('hide')
        question_box.classList.add('hide')
    }
})

preview_button.addEventListener('click', (e) => {
    e.preventDefault()
    const content = question_editor.getData()
    const title = document.querySelector('[name="title"]').value
    question_title.innerText = title
    question_content.innerHTML = content
    question_forum.classList.add('hide')
    question_box.classList.remove('hide')
})

//submit fourm 
answer_forum.addEventListener('submit', (e) => ck_submit_handler(e, answer_editor))
question_forum.addEventListener('submit', (e) => ck_submit_handler(e, question_editor))

const ck_submit_handler = (e, editor) => {
    //parse the content of text question_editor to html for filtering and picking the image urls
    const data = editor.getData()
    const images = Image_picker(data)
    const filter = Filter_length(data)
    if (typeof images === 'object' && images) {
        Inject_images(images, 'images', e.target)
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
}

