
const vote_buttons = [...document.getElementsByClassName('vote-control')]
const arrow_up = "bi bi-arrow-up-square"
const arrow_down = "bi bi-arrow-down-square"

vote_buttons.forEach(element => {
    element.addEventListener('click', (e) => {
        const vote_type = e.target.classList.contains('increment') ? 'increment' : e.target.classList.contains('decrement') ? 'decrement' : false;
        vote_control(e.target.parentElement, vote_type)
    })
})

async function vote_control(vote_box, vote_type) {
    //vote control
    
    console.log(increment)
    //reset vote controls
}


