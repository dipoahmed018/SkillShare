const { Modal } = require("bootstrap")

const questions_delete_btns = [...document.getElementsByClassName('delete-question')]
const post_deleter = document.getElementById('post-delete-modal')
const yes_button = post_deleter.querySelector('.yes')

const question_delete_modal = new Modal(post_deleter)

questions_delete_btns.forEach(element => {
    element.addEventListener('click', (e) => {
        const question_id = e.target.getAttribute('data-post-id')
        // yes_button.setAttribute('data-post-id', question_id)
        question_delete_modal.show()
    })
})
yes_button?.addEventListener('click', (e) => {
    const question_id = e.target.getAttribute('data-post-id')
    // delete_post(question_id)
    console.log(question_id)
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
        })
        .catch(err => {
            console.log(err)
        })
}