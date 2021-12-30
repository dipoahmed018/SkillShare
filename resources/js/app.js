require('./bootstrap');
require('./component/header/header')

window.onload = () => {
    window.csrf =  document.head.querySelector("meta[name='_token']").content;
}