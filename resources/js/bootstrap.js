window._ = require('lodash');
window.axios = require('axios');
window.csrf = document.head.querySelector('meta[name="_token"]').content

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector("meta[name='_token']").content;
