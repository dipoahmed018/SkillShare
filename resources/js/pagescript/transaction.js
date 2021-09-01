import { loadStripe } from "@stripe/stripe-js"

let stripe;
let elements;
let card;
let bank;
const bank_box = document.querySelector('.bank-pay-box')
const card_box = document.querySelector('.card-pay-box')
const error_box = document.querySelector('.error-box')
const checkout_btn = document.querySelector('.checkout')

//initialize stripe

loadStripe(window.stripe_publish_key).then(res => {
    initialize_elements(res)
})

//toogle card payment bank payment
document.querySelector('.card-payment').addEventListener('click', () => {
    card_box.classList.remove('hide')
    bank_box.classList.add('hide')
})
document.querySelector('.bank-payment').addEventListener('click', () => {
    card_box.classList.add('hide')
    bank_box.classList.remove('hide')
})


const initialize_elements = (stripe_init) => {
    stripe = stripe_init
    elements = stripe.elements()
    card = elements?.create('card');
    if (card) {
        card.mount(card_box)
        card.on('change', (e) => {
            checkout_btn.disabled = e.empty
            error_box.textContent = e.error ? e.error.message : "";
        })
    }
}

checkout_btn.addEventListener('click', () => {
    if (bank_box.classList.contains('hide')) {
        stripe.confirmCardPayment(client_sc, {
            payment_method: {
                card
            }
        }).then(res => {
            if (res.error) {
                error_box.textContent = res.error.message
            } else {
                fetch('/purchase/course/confirm', {
                    method: 'post',
                    body: JSON.stringify({ "client_sc" : client_sc }),
                    headers: {
                        'X-CSRF-TOKEN': window.csrf,
                        'Content-Type': 'Application/json',
                    }
                }).then(res => {
                    alert('payment_success')
                }).catch(res => {
                    console.log(res)
                })
            }
        })
    } else {

    }
});
