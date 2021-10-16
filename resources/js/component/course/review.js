import  Dayjs  from "dayjs"
let reletiveTime = require('dayjs/plugin/relativeTime')
Dayjs.extend(reletiveTime)
const reply_forms = document.querySelectorAll('.reply-create') //reply creator forms
const more_replies_btn = document.querySelectorAll('.more-replies') //load more replies buttons
const reply_creator_btn = document.querySelectorAll('.reply-creator-show') //reply creator shower button


//reply creater form shower on reply_creator_btn click
reply_creator_btn.forEach(el => {
    el.addEventListener('click', e => {
        const form = document.getElementById(
            `reply-create-${e.target.getAttribute('data-review-id')}`)
        form.style.display = 'block'
    })
})


//reply creaator form submit handling
reply_forms.forEach(el => {
    el.addEventListener('submit', e => {
        e.preventDefault()
        const form = e.target
        const comment = form.querySelector('[name="content"]').value
        const commentable_id = form.getAttribute('data-review-id')
        const parent = form.parentElement.classList.contains('reply') ? form.parentElement : form.querySelector('.replies')
        add_review_element(parent, 'hello')
        return true
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
                // console.log(fo)
            })
            .catch(res => console.log(res))
    })
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
                'Accept' : 'application/json',
            },
        })
        .then(res => res.ok? res.json() : Promise.reject(res))
        .then(res => {
            const {next_page, data} = res.data
            next_page ? el.setAttribute('data-url-next_page', paginated_data.next_page) : el.style.display = 'none'
            data.forEach(review => {
                add_review_element(replies_box, review)
            })

        })
        .catch(res => console.log(res))
    })
})


function add_review_element(parent, review) {
    const reply_elm = document.createElement('div')
    reply_elm.classList.add('reply')

    const review_tamplate = document.getElementById('review-template').cloneNode(true)
    reply_elm.appendChild(review_tamplate)

    console.log(parent)
    console.log(review)
    console.log(reply_elm)
}


