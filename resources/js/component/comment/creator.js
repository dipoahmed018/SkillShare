//show reply creator form
const reply_creator_btns = [...document.getElementsByClassName('reply-creator-show')]

reply_creator_btns?.forEach((element) => element.addEventListener('click', (e) => {
    const comment_id = e.target.getAttribute("data-comment-id")
    const comment_box = document.getElementById(`comment-${comment_id}`)
    const form = document.getElementById(`comment-edit-${comment_id}`)

    comment_box.querySelector('.cotnet').classList.add('hide')
    form.classList.remove('hide')
}))
