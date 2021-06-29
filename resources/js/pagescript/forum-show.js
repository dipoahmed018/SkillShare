import { Modal } from "bootstrap";
import CkEditor from '@ckeditor/ckeditor5-build-classic'

const modal_element = document.getElementById('create')
const edit_box = document.getElementById('edit-box')
const form = document.getElementById('create-post-question')
const close_modal = document.getElementById('close-modal')
const create_post_button = document.getElementById('create-post')
const create_question_button = document.getElementById('create-question')
const modal = new Modal(modal_element)
let editor;
create_post_button.addEventListener('click', () => {
    form.action = `/${forum.id}/post/create`
    CkEditor.create(edit_box).then(ckeditor => editor = ckeditor)
    modal.show()
})

create_question_button.addEventListener('click', (e) => {
    form.action = `/${forum.id}/question/create`
    CkEditor.create(edit_box).then(ckeditor => editor = ckeditor)
    modal.show()
})
close_modal.addEventListener('click',() => {
    editor.destroy()
    modal.hide()
})
