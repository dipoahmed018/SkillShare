//initial selection
const add_catagories = document.querySelectorAll('.catagory-item-add');
const remove_catagories = document.querySelectorAll('.catagory-item-remove');
const add_catagory_box = document.getElementById('add-catagory-box');
const remove_catagory_box = document.getElementById('remove-catagory-box');
//add eventlistener
remove_catagories.forEach((node, index) => {
    node.addEventListener('click', (e) => remove_catagory(e), {
        once: true
    })
})
add_catagories.forEach((node, index) => {
    node.addEventListener('click', (e) => add_catagory(e), {

    })
})

const remove_catagory = async (e) => {
    const id = e.target.getAttribute('catagory')
    try {

        const res = await fetch(`/delete/course/${course.id}/catagory`, {
            method: 'DELETE',
            body: JSON.stringify({
                catagory: id
            }),
            headers: {
                'X-CSRF-TOKEN': window.csrf,
                'content-type': 'application/json'
            },
        })
        if (res.status == 200) {
            e.target.parentNode.remove()
        }
    } catch (error) {
        console.log('error', error)
    }
}
const add_catagory = async (e) => {
    const id = e.target.getAttribute('catagory')
    //recheck
    let exists = false;
    document.querySelectorAll('.catagory-item-remove').forEach(element => {
        if (element.getAttribute('catagory') == id) {
            exists = true;
            return false;
        }
    })
    if (exists == true) {
        return false;
    }
    //add catagory
    try {

        const res = await fetch(`/update/course/${course.id}/catagory`, {
            method: 'PUT',
            body: JSON.stringify({
                catagory: id
            }),
            headers: {
                'X-CSRF-TOKEN': window.csrf,
                'content-type': 'application/json'
            },
        })
        if (res.status == 200) {
            const parent = e.target.parentNode.cloneNode(true)
            const button = parent.childNodes[3]
            button.innerText = 'Remove'
            button.className = 'btn btn-danger catagory-item-remove'
            button.addEventListener('click', (e) => remove_catagory(e), {
                once: true
            })
            remove_catagory_box.appendChild(parent);
        }
    } catch (error) {
        console.log('error', error)
    }
}