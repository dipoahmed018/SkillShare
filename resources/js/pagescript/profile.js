
const control_btns = [...document.getElementsByClassName('control-buttons')]

control_btns.forEach(element => {

    element.addEventListener('click', e => {
        const target_class = e.target.getAttribute('data-toggle-target')
      
        document.querySelector('.resources .resource-box:not(.hide)').classList.add('hide')
        document.querySelector(`${target_class}`).classList.remove('hide')
    })
})