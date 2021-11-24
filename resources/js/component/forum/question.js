const { Modal } = require("bootstrap")

//question create

let question_editor;
const question_creator_forum = document.getElementById('create-question')
const question_textarea = question_creator_forum.querySelector('[name="question"]')
const question_create = question_creator_forum.getElementsByTagName('button')

ClassicEditor.create(question_textarea, {
    toolbar: {
        items: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote'],
    }
})
    .then(CKeditor => {
        question_editor = CKeditor
    })
    .catch(err => console.log(err))
    
//question delete
const questions_delete_btns = [...document.getElementsByClassName('delete-question')]
const question_deleter = document.getElementById('question-delete-modal')
const question_delete_confirmed = question_deleter.querySelector('.yes')

const question_delete_modal = new Modal(question_deleter)

questions_delete_btns?.forEach(element => {
    element.addEventListener('click', (e) => {
        const question_id = e.target.getAttribute('data-question-id')
        question_delete_confirmed?.setAttribute('data-question-id', question_id)
        question_delete_modal.show()
    })
})
question_delete_confirmed?.addEventListener('click', (e) => {
    const question_id = e.target.getAttribute('data-question-id')
    delete_post(question_id)
})

function delete_post(question_id) {
    fetch(`/${question_id}/post/delete`, {
        method: 'delete',
        headers: {
            'accept': 'application/json',
            'X-CSRF-TOKEN': window.csrf,
        }
    })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(res => {
            console.log(res)
            document.getElementById(`question-${question_id}`).remove();
            question_delete_modal.hide()
        })
        .catch(err => {
            console.log(err)
        })
}