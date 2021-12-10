import { Image_picker, Filter_length, Inject_images } from "../asset/CKEditorHelper"
import Popup from '../asset/PopupHandler'
require('../component/forum/questionCard')
const { Modal } = require("bootstrap")
const popup = new Popup();

const forum_contents_showers = [
    document.getElementById('questions'),
    document.getElementById('students'),
    document.getElementById('about'),
]
const forum_contents = [
    document.querySelector('.questions-wrapper'),
    document.querySelector('.students-wrapper'),
    document.querySelector('.about'),
]

forum_contents_showers?.forEach(element => {
    element?.addEventListener('click', (e) => {
        forum_contents.forEach(element => {
            if (element.classList.contains(e.target.getAttribute('data-event-target'))) {
                element.classList.remove('hide')
                return
            }
            element.classList.add('hide')
        })
    })
})


//question create

let question_editor;
const question_creator_forum = document.getElementById('create-question')
const question_textarea = document.getElementById('question-input');
// const question_create = question_creator_forum.getElementsByTagName('button')

if (question_textarea) {
    ClassicEditor.create(question_textarea, {
        toolbar: {
            items: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
        },
        simpleUpload: {
            uploadUrl: `/save/image`,
            withCredentials: true,
            headers: {
                'X-CSRF-TOKEN': window.csrf,
            },
        },
    })
        .then(CKeditor => {
            question_editor = CKeditor
        })
        .catch(err => console.log(err))
}

question_creator_forum.addEventListener('submit', createQuestion)

function createQuestion(e) {

    const data = question_editor.getData()
    const images = Image_picker(data, 4)
    const filter = Filter_length(data)
    const image_add = Inject_images(images, 'images', e.target);

    if (images instanceof Error) { e.preventDefault(); return popup.addPopup(images.message) }
    if (filter instanceof Error) { e.preventDefault(); return popup.addPopup(filter.message) }
    if (image_add instanceof Error) { e.preventDefault(); return popup.addPopup(image_add.message) }
}