import Pusher from "pusher-js"
//serach box
const search_input = document.getElementById('search');
const suggestion_box = document.getElementById('suggestion-box')

search_input.addEventListener('input', (e) => {
    const input = e.target.value
    if (input.length >= 4) {
        fetch(`/courses?suggestion=true&search=${input}`, {
            method: 'get',
            headers: {
                'X-CSRF-TOKEN': window.csrf,
            }
        })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(res => {
                //slice the suggestions to 9
                const data = res.data?.slice(1, 10)
                //clear suggestion box 
                while (child = suggestion_box.firstChild) {
                    suggestion_box.removeChild(child)
                }
                data?.forEach(element => {
                    const wrapper = document.createElement('a');
                    wrapper.href = `/show/course/${element.id}`
                    wrapper.innerText = element.title.length > 30 ? element.title.slice(1, 30) +
                        '...' : element.title
                    wrapper.classList.add('searched-item')
                    suggestion_box.appendChild(wrapper);
                });
            })
            .catch(err => {
                console.log(err)
            })

        //suggestion box hider listener
        const hideSuggestion = (e) => {
            if (!suggestion_box.contains(e.target)) {
                document.removeEventListener('click', hideSuggestion)
                suggestion_box.classList.add('hide');
            }
        }

        //adding event listerner to document to hide the suggestion box onclick
        suggestion_box.classList.contains('hide') ? document.addEventListener('click', hideSuggestion) :
            null;
        suggestion_box.classList.remove('hide')
        return true;
    }
    suggestion_box.classList.add('hide')
})

//filter box
const filter_box = document.querySelector('.filters')
const filter_button = document.querySelector('.filter-button')
const filter_form = document.getElementById('filter-form')

//toogle filter box
filter_button.addEventListener('click', () => {
    if (filter_box.classList.contains('filters-hidden')) {
        filter_button.classList.add('close-filter')
        filter_box.classList.remove('filters-hidden')
        filter_box.classList.add('filters-show')
    } else {
        filter_button.classList.remove('close-filter')
        filter_box.classList.remove('filters-show')
        filter_box.classList.add('filters-hidden')
    }
})

//dual slicer price range
const lowerSlider = document.querySelector('#lower')
const upperSlider = document.querySelector('#upper')
const min_price = document.getElementById('min_price')
const max_price = document.getElementById('max_price')


const range_input_listener = (e) => {
    let lowerVal = parseInt(lowerSlider.value);
    let upperVal = parseInt(upperSlider.value);

    if (upperVal < lowerVal + 15) {
        upperSlider.value = lowerVal + 15
    }
    if (lowerVal > upperVal - 15) {
        lowerSlider.value = upperVal - 15
    }
    //set max min price input 
    min_price.value = lowerSlider.value * 100
    max_price.value = upperSlider.value * 100
}
upperSlider.addEventListener('input', range_input_listener)
lowerSlider.addEventListener('input', range_input_listener)

//review input
const review_inputs = document.querySelectorAll('.review-stars');
review_inputs.forEach(elm => {
    elm.addEventListener('click', (e) => {
        const target = e.target.getAttribute('data-stars') ? e.target : e.target.parentElement;
        Array.prototype.find.call(review_inputs, (elm) => elm.classList.contains('selected'))
            .classList.remove('selected')
        target.classList.add('selected');
        document.querySelector('[name="review"').value = target.getAttribute('data-stars');
    })
    document.querySelector('[name="review"').value = elm.classList.contains('selected') ? elm.getAttribute(
        'data-stars') : 10;
})

//notifications handel
if (user) {
    const notification_box = document.querySelector('.notifications')

    //notification observer
    const pusher = new Pusher(window.pusher_app_key, {
        cluster: window.pusher_app_cluster,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
          headers: {
              "X-CSRF-TOKEN": window.csrf,
          }
        }
    });

    const notification_channel = pusher.subscribe(`private-User.Notification.${user.id}`)

    notification_channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (notification) => {
        console.log(notification)
        makeNotificationElement(notification, notification_box, 'afterbegin')
    })

    fetch(`/notifications/${user.id}`, {
        method: 'get',
        headers: {
            "Accept": "application/json"
        }
    })
        .then(res => res.ok ? res.json() : Promise.reject(res.json))
        .then(notifications => {
            notifications.forEach(notification => {
                notification.data.id = notification.id
                makeNotificationElement(notification.data, notification_box, 'beforeend')
            })
        })


    /**
     * 
     * @param {Object} notification "notification contents"
     * @param {HTMLElement} parentElement "notification box "
     * @param {String} insert "placement of the notification"
     */

    function makeNotificationElement(notification, parentElement, insert = "beforeend")
    {
        let message = notification.message
        if(message.length > 70) {
            //reduce the length of the string
            let shortened_str = message.substr(0, message.lastIndexOf(' ', 70))

            //if shorthened string length is less then 50 forget about shorened string just reduce it to 100
            message = shortened_str.length < 30 ? message.substr(70) + "..." : shortened_str + "..."
        }

        let from;
        from = !notification.icon_image || `<img src="${notification.icon_image}" />`
        from = from ?? `<span>${notification.icon_text}</span>`;

        let notification_card = `<div class="notification" id="${notification.id}">
            <div class="from-icon">
                    ${from}
            </div>
            <p class="notification-card">${message}</p>
            <i class="bi bi-x-lg delete-btn" data-notification-id=${notification.id}></i>
        </div>`
        parentElement.insertAdjacentHTML(insert, notification_card)

        const notification_element = document.getElementById(notification.id)
        const notification_delete_btn = notification_element.querySelector('.delete-btn')

        notification_element.addEventListener('click', e => {
            location.href = notification.link_to
        })
        
        notification_delete_btn.addEventListener('click', (e) => {
            e.stopPropagation()
            
            fetch(`/notification/delete/${notification.id}`, {
                method: "delete",
                headers: {
                    "Accept": "application/json",
                    'X-CSRF-TOKEN': window.csrf,
                }
            })
                .then(res => res.ok ? res.json() : Promise.reject(res.json()))
                .then(notification => {
                    document.getElementById(notification.id).remove()
                })
        })

    }
}



//mobile sider bar toogle
const close_btn = document.querySelector('.sidebar-cls-icn')
const open_btn = document.querySelector('.sidebar-opn-icn')
const side_bar = document.querySelector('.sidebar')
const toggleSideBar = (e) => {
    if (side_bar.classList.contains('sidebar-hidden')) {
        side_bar.classList.remove('sidebar-hidden')
        side_bar.classList.add('sidebar-show')
    } else {
        side_bar.classList.add('sidebar-hidden')
        side_bar.classList.remove('sidebar-show')
    }
}
open_btn.addEventListener('click', toggleSideBar)
close_btn.addEventListener('click', toggleSideBar)