require('../component/forum/questionCard')

const forum_contents_showers = [
    document.getElementById('questions'),
    document.getElementById('students'),
    document.getElementById('about'),
]
const forum_contents = [
    document.querySelector('.questions-wrapper'),
    document.querySelector('.students-wrapper'),
    document.querySelector('.about'),
]

forum_contents_showers?.forEach(element => {
    element?.addEventListener('click', (e) => {
        forum_contents.forEach(element => {
            if (element.classList.contains(e.target.getAttribute('data-event-target'))) {
                element.classList.remove('hide')
                return
            }
            element.classList.add('hide')
        })
    })
})