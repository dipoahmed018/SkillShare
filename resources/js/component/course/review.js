import Dayjs from "dayjs"
let reletiveTime = require('dayjs/plugin/relativeTime')
Dayjs.extend(reletiveTime)
const reply_forms = document.querySelectorAll('.reply-create') //reply creator forms
const review_form = document.querySelector('.review-create')
const more_replies_btn = document.querySelectorAll('.more-replies') //load more replies buttons
const reply_creator_btn = document.querySelectorAll('.reply-creator-show') //reply creator shower button

//reply creater form shower on reply_creator_btn click
reply_creator_btn?.forEach(element => {
    element.addEventListener('click', show_reply_create_form)
})


//reply creaator form submit handling
reply_forms?.forEach(element => {
    element.addEventListener('submit', create_reply)
})

review_form?.addEventListener('submit', create_reply)

//rating input handel
const rating_input_box = document.querySelector('.rating')
const rating_stars = rating_input_box?.childNodes
const active_star = "bi bi-star-fill"
const inactive_star = "bi bi-star"

const active_on_hover = () => {
    rating_stars?.forEach(element => {
        if (element instanceof HTMLElement) {
            element.className = active_star
        }
    })
}
const inactive_on_hover = () => {
    rating_stars?.forEach(element => {
        if (element instanceof HTMLElement) {
            if (element.getAttribute('data-style-active') == 'false') {
                element.className = inactive_star
            }
        }
    })
}
const star_hover_effect = (element) => {
    if (element instanceof HTMLElement) {
        element.addEventListener('mouseover', e => {
            e.stopPropagation()
            element.className = active_star
            let next_child = element.nextElementSibling
            while (next_child) {
                next_child.className = inactive_star
                next_child = next_child.nextElementSibling
            }
            let prev_child = element.previousElementSibling
            while (prev_child) {
                prev_child.className = active_star
                prev_child = prev_child.previousElementSibling
            }
        })
        element.addEventListener('click', () => {
            element.parentElement.parentElement.querySelector('[name="stars"]').value = element.getAttribute('data-input-value')
            element.setAttribute('data-style-active', 'true')

            let prev_child = element.previousElementSibling
            while (prev_child) {
                prev_child.setAttribute('data-style-active', 'true')
                prev_child = prev_child.previousElementSibling
            }
            let next_child = element.nextElementSibling
            while (next_child) {
                next_child.setAttribute('data-style-active', 'false')
                next_child = next_child.nextElementSibling
            }
        })
    }
}
rating_input_box?.addEventListener('mouseover', active_on_hover, { once: true })

rating_input_box?.addEventListener('mouseleave', e => {
    inactive_on_hover()
    rating_input_box?.addEventListener('mouseover', active_on_hover, { once: true })
})

rating_stars?.forEach(star_hover_effect)

//more replies on click send request for more replies 
more_replies_btn?.forEach(element => {

    element.addEventListener('click', (e) => {
        const replies_box = element.parentElement.querySelector('.replies')
        let review_id = element.getAttribute('data-review-id')

        //next_page attribute will be set after the fetch of more replies is done based on pagination dynamically
        let next_page = element.getAttribute('data-url-next_page')

        let url = next_page ? next_page : `/review/${review_id}/replies`
        fetch(url, {
            method: 'get',
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(res => {
                const { next_page, data } = res.data
                next_page ? element.setAttribute('data-url-next_page', paginated_data.next_page) : element.style.display = 'none'
                data.forEach(review => {
                    add_review_element(replies_box, review, 'reply')
                })

            })
            .catch(res => console.log(res))
    })
})
//show reply_creator form 
function show_reply_create_form(e) {
    //selectign the repy creator form and making it visible
    const reply_creator_forms = e.target.parentElement.parentElement.querySelector('.reply-create').parentElement
    reply_creator_forms.style.display = 'flex'
}
// show review_editor form
function show_review_edit_form(e) {
    const reply_creator_forms = e.target.parentElement.parentElement.querySelector('.review-edit').parentElement
    reply_creator_forms.style.display = 'flex'
}
function cancel_reply_form(e) {
    e.target.parentElement.parentElement.style.display = 'none'
}


//toogel review creation box visibility
const review_cancel = [...document.getElementsByClassName('review-cancel')]
//giving initial 
review_cancel?.forEach(element => {
    element.addEventListener('click', (e) => {
        let review_form_box = e.target.parentElement.parentElement
        review_form_box.style.display = 'none'
    })
})

//request to create a review on server

export function create_reply(e) {
    e.preventDefault()
    const form = e.target
    const comment = form.querySelector('[name="content"]')?.value
    const stars = form.querySelector('[name="stars"]')?.value
    const commentable_id = form.getAttribute('data-reviewable-id')
    const comment_type = form.getAttribute('data-review-type')
    const reply_box = form.parentElement.parentElement.classList.contains('reply') ? form.parentElement?.parentElement.parentElement : form.parentElement.parentElement?.querySelector('.replies')
    const review_box = document.querySelector('.reviews')
    const parent = comment_type == 'review_reply' ? reply_box : review_box
    if (comment.length < 5) {
        return false;
    }
    fetch('/create/review', {
        method: 'post',
        body: JSON.stringify({
            'reviewable_id': commentable_id,
            'reviewable_type': comment_type,
            'content': comment,
            'stars': stars || 1,
        }),
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.csrf,
        }
    })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(res => {
            res.success ? add_review_element(parent, res.data) : console.log(res.data)
        })
        .catch(res => console.log(res))
}

//add review element to dom

export function add_review_element(parent, review) {

    //create wrapper based on type
    const review_elm = document.createElement('div')
    review_elm.classList.add(review.reviewable_type == 'course' ? 'review' : 'reply')
    review_elm.id = `review-${review.id}`

    const review_tamplate = document.getElementById('review-template').content.cloneNode(true)

    const review_creator_btn = review_tamplate.querySelector('.reply-creator-show')
    const reply_create_form = review_tamplate.querySelector('.reply-create')
    const input_cancel_btn = review_tamplate.querySelectorAll('.review-cancel')
    const review_editor_form = review_tamplate.querySelector('.review-edit')
    const review_delete_btn = review_tamplate.querySelector('.review-delete')
    const review_edit_btn = review_tamplate.querySelector('.review-editor-btn')
    const profile_text = review_tamplate.querySelector('.profile-text')
    const profile_image = review_tamplate.querySelector('.profile-image')

    // adding review content in the review tamplate
    review_tamplate.querySelector('.content').innerText = review.content
    review_tamplate.querySelector('.created-at').innerText = Dayjs(review.created_at).fromNow()
    review_tamplate.querySelector('.owner-name').innerText = review.owner_details.name
    review_tamplate.querySelector('.owner-details').href = `/user/${review.owner_details.id}/profile`
    input_cancel_btn.forEach(element => element.addEventListener('click', cancel_reply_form))
    if (review.owner_details?.profile_picture) {
        profile_text.revome()
        profile_image.src = review.owner_details.profile_picture.file_link
    } else {
        profile_image.remove()
        profile_text.firstElementChild.innerText = review.owner_details?.name.substr(0, 1)
    }

    //remove or add reply creator based on user permission
    if (user?.id == review.owner_details.id || user?.id !== course.owner_details.id) {
        const review_create_box = reply_create_form.parentElement
        review_create_box.remove()
        review_creator_btn.remove()
    }
    if (user?.id == review.owner_details.id) {
        review_delete_btn.setAttribute('data-review-id', review.id)
        review_edit_btn.setAttribute('data-review-id', review.id)
        review_delete_btn.addEventListener('click', delete_review)
        review_edit_btn.addEventListener('click', show_review_edit_form)
        review_editor_form.setAttribute('data-reviewable-id', review.id)
        review_editor_form.setAttribute('data-review-type', review.reviewable_type)
        review_editor_form.addEventListener('submit', edit_review)

    } else {
        review_delete_btn.remove()
        review_edit_btn.remove()
        //controling the behavior of button clicks and event listener of review template
        review_creator_btn.addEventListener('click', show_reply_create_form)
        //reply creator form handeler 
        reply_create_form.setAttribute('data-reviewable-id', review.id)
        reply_create_form.setAttribute('data-review-type', 'review_reply')
        reply_create_form.addEventListener('submit', create_reply)
    }

    if (review.reviewable_type == 'review_reply') {
        review_tamplate.querySelector('.rate')?.remove()
        review_tamplate.querySelector('.rating')?.remove()
        review_elm.appendChild(review_tamplate)
        parent.appendChild(review_elm)
    }
    if (review.reviewable_type == 'course') {
        //rating editor event listener star
        const rating_selector = review_tamplate.querySelector('.rating')
        const rating_editor_stars = rating_selector.childNodes
        rating_selector?.addEventListener('mouseover', active_on_hover, { once: true })
        rating_selector?.addEventListener('mouseleave', e => {
            inactive_on_hover()
            rating_selector?.addEventListener('mouseover', active_on_hover, { once: true })
        })
        rating_editor_stars?.forEach(star_hover_effect)
        //rating editor event listener finished

        review_tamplate.querySelector('.rate-image').style.width = `${review.stars * 10}%`
        review_elm.appendChild(review_tamplate)
        parent.prepend(review_elm)
        document.querySelector('.review-create-box').style.display = 'none'
    }
}

//delete review
const delete_review = (e) => {
    const review_id = e.target.getAttribute("data-review-id")
    fetch(`/delete/review/${review_id}`, {
        method: 'delete',
        headers: {
            "Accept": 'application/json',
            "X-CSRF-TOKEN": window.csrf
        }
    })
        .then(res => res.ok ? res.json() : Promise.target(res))
        .then(res => {
            res.success ? document.getElementById(`review-${res.data.id}`)?.remove() : null
            document.querySelector('.review-create-box').style.display = 'flex'
        })
        .catch(res => console.log(res))
}

[...document.getElementsByClassName('review-delete')].forEach(element => {
    element.addEventListener('click', delete_review)
})

//edit review
const review_editor_btn = [...document.getElementsByClassName('review-editor-btn')]
review_editor_btn.forEach(element => {
    element.addEventListener('click', show_review_edit_form)
})

const edit_review = (e) => {
    e.preventDefault()
    const review_element = e.target.parentElement.parentElement.parentElement
    const comment = e.target.querySelector('[name="content"]').value
    const stars = e.target.querySelector('[name="stars"]').value
    const comment_id = e.target.getAttribute('data-reviewable-id')
    const rating = review_element.querySelector('.rate-image')
    fetch(`/update/review/${comment_id}`, {
        method: 'put',
        body: JSON.stringify({
            'content': comment,
            'stars': stars
        }),
        headers: {
            "Accept": 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrf,
        }
    })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(res => {
            review_element.querySelector('.review-create-box').style.display = 'none'
            review_element.querySelector('.content').innerText = comment
            console.log(stars * 10)
            rating.style.width = `${stars * 10}%`
        })
        .catch(res => console.log(res, 'error'))
}

const review_editor_form = [...document.getElementsByClassName('review-edit')]
review_editor_form?.forEach(element => {
    element.addEventListener('submit', edit_review)
})

