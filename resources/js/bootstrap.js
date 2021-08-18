window._ = require('lodash');
window.axios = require('axios');
window.csrf = document.head.querySelector('meta[name="_token"]').content
window.stripe = Stripe(process.env.MIX_STRIPE_PUBLISH_KEY)

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector("meta[name='_token']").content;
