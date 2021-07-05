require('./bootstrap');

window.onload = () => {
    window.csrf =  document.head.querySelector("meta[name='_token']").content;
}