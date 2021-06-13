require('./bootstrap');

window.csrf =  document.head.querySelector("meta[name='_token']").content;