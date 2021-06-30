import { Modal } from "bootstrap";
const modal_element = document.getElementById('create')
const form = document.getElementById('create-post-question')
const close_modal = document.getElementById('close-modal')
const create_post_button = document.getElementById('create-post')
const create_question_button = document.getElementById('create-question')
const modal = new Modal(modal_element)
let editor;
create_post_button.addEventListener('click', () => {
    form.action = `/${forum.id}/post/create`
    modal.show()
})
ClassicEditor.builtinPlugins.map(plugin => console.log(plugin.pluginName));
create_question_button.addEventListener('click', (e) => {
    form.action = `/${forum.id}/question/create`
    const edit_box = document.createElement('textarea')
    edit_box.id = 'q_editor'
    document.querySelector('.textarea').appendChild(edit_box)

    ClassicEditor.create(edit_box, {
        toolbar : ['undo','redo','|','heading','bold','italic','bulletedList','numberedList','blockQuote','|','ImageUpload'],
        // Image : {},
        simpleUpload: {
            uploadUrl: `http://skillshare.com/forum/post/image`,
            withCredentials: true,
            headers: {
                'X-CSRF-TOKEN': window.csrf,
            }
        }
    }).then(ckeditor => editor = ckeditor)
        .catch(error => console.log(error))
    modal.show()
})
close_modal.addEventListener('click', () => {
    //clear up
    const edit_box = document.getElementById('q_editor')
    edit_box ? edit_box.remove() : null
    editor ? editor.destroy() : null
    modal.hide()
})
form.addEventListener('submit', (e) => {
    if (editor !== null) {
        document.querySelector('[name="contents"]').value = editor.getData()
    }
})