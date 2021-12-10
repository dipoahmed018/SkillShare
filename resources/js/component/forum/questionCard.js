

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