
//show comment editor form
const comment_editor_btns = [...document.getElementsByClassName('comment-editor-show')]

comment_editor_btns.forEach(element => {
    element.addEventListener('click',e => {
        const comment_id = e.target.getAttribute("data-comment-id")
        const form = document.getElementById(`comment-edit-${comment_id}`)
        form.classList.remove('hide')
    })
})

//hide comment form btns
const comment_hider_btns = [...document.getElementsByClassName('comment-form-cancel')]

comment_hider_btns?.forEach(element => element.addEventListener('click', hideCommentForm))

//hide form
function hideCommentForm(e) {
    const form = e.target.parentElement
    form.target.classList.add('hide')
}
