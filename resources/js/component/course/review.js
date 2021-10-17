import Dayjs from "dayjs"
let reletiveTime = require('dayjs/plugin/relativeTime')
Dayjs.extend(reletiveTime)
const reply_forms = document.querySelectorAll('.reply-create') //reply creator forms
const more_replies_btn = document.querySelectorAll('.more-replies') //load more replies buttons
const reply_creator_btn = document.querySelectorAll('.reply-creator-show') //reply creator shower button


//reply creater form shower on reply_creator_btn click
reply_creator_btn.forEach(el => {
    el.addEventListener('click', show_reply_form)
})


//reply creaator form submit handling
reply_forms.forEach(el => {
    el.addEventListener('submit', create_reply)
})

//more replies on click send request fo more replies 
more_replies_btn.forEach(el => {

    el.addEventListener('click', (e) => {
        const replies_box = el.parentElement.querySelector('.replies')
        let review_id = el.getAttribute('data-review-id')

        //next_page attribute will be set after the fetch of more replies is done based on pagination dynamically
        let next_page = el.getAttribute('data-url-next_page')

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
                next_page ? el.setAttribute('data-url-next_page', paginated_data.next_page) : el.style.display = 'none'
                data.forEach(review => {
                    add_review_element(replies_box, review, 'reply')
                })

            })
            .catch(res => console.log(res))
    })
})

//show reply_creator form 
export function show_reply_form(e) {
    console.log(e.target)
    const reply_creator_forms = e.target.parentElement.parentElement.querySelectorAll('.reply-create')
    reply_creator_forms[reply_creator_forms.length - 1].style.display = 'block'
}

//request to create a review on server

export function create_reply(e) {
    e.preventDefault()
    const form = e.target
    const comment = form.querySelector('[name="content"]').value
    const commentable_id = form.getAttribute('data-review-id')
    const reply_box = form.parentElement.classList.contains('.reply') ? form.parentElement.parentElement : form.parentElement.querySelector('.replies')

    fetch('/create/review', {
        method: 'post',
        body: JSON.stringify({
            'reviewable_id': commentable_id,
            'reviewable_type': 'review_reply',
            'content': comment
        }),
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.csrf,
        }
    })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(res => {
            res.success ? add_review_element(reply_box, res.data, 'reply') : console.log(res.data)
        })
        .catch(res => console.log(res))
}

//add review element to dom

export function add_review_element(parent, review, type = 'review') {

    //create wrapper based on type
    const reply_elm = document.createElement('div')
    reply_elm.classList.add(type)

    const review_tamplate = document.getElementById('review-template').content.cloneNode(true)

    // adding review content in the review tamplate
    review_tamplate.querySelector('.content').innerText = review.content
    review_tamplate.querySelector('.rate-image').style.width = `${review.stars * 10}%`
    review_tamplate.querySelector('.created-at').innerText = Dayjs(review.created_at).fromNow()
    review_tamplate.querySelector('.owner-name').innerText = review.owner_details.name
    review_tamplate.querySelector('.owner-details').href = `/user/${review.owner_details.id}/profile`

    const reply_create_form = review_tamplate.querySelector('.reply-create')
    const profile_text = review_tamplate.querySelector('.profile-text')
    const profile_image = review_tamplate.querySelector('.profile-image')
    if (review.owner_details?.profile_picture) {
        profile_text.revome()
        profile_image.src = review.owner_details.profile_picture.file_link
    } else {
        profile_image.remove()
        profile_text.firstElementChild.innerText = review.owner_details?.name.substr(0, 1)
    }

    //controling the behavior of button clicks and event listener of review template
    review_tamplate.querySelector('.reply-creator-show').addEventListener('click', show_reply_form)
    //reply creator form handeler 
    reply_create_form.setAttribute('data-review-id', review.id)
    reply_create_form.addEventListener('submit', create_reply)

    reply_elm.appendChild(review_tamplate)

    parent.appendChild(reply_elm)
}


