
//show comment editor form
const comment_editor_btns = [...document.getElementsByClassName('comment-editor-show')]

comment_editor_btns.forEach(element => {
    element.addEventListener('click', showCommentForm)
})

//hide comment form btns
const comment_hider_btns = [...document.getElementsByClassName('comment-form-cancel')]

comment_hider_btns?.forEach(element => element.addEventListener('click', hideCommentForm))


//show comment
function showCommentForm(e) {
    const comment_id = e.target.getAttribute("data-comment-id")
    const form = document.getElementById(`comment-edit-${comment_id}`)
    form.classList.remove('hide')
}
//hide form
function hideCommentForm(e) {
    const form = e.target.parentElement
    form.target.classList.add('hide')
}
