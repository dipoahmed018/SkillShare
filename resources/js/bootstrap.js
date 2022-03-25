window._ = require('lodash')
window.axios = require('axios')
window.csrf = document.head.querySelector('meta[name="_token"]').content
window.stripe_publish_key = process.env.MIX_STRIPE_PUBLISH_KEY
console.log(window.stripe_publish_key)
window.pusher_app_key = process.env.MIX_PUSHER_APP_KEY
window.pusher_app_cluster = process.env.MIX_PUSHER_APP_CLUSTER

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector("meta[name='_token']").content
