export default class PopupHandler {

    _popups = []
    constructor(id = 'popup_box') {
        this.popup_box_id = id;
    }
    getPopups() {
        return this._popups
    }
    addPopup(message) {
        if (this._popups.length < 1) {
            this._popups.push({ id: 1, message, running: false })
        } else {
            let { id } = this._popups.reduce((prev, cur) => prev.id < cur.id ? cur : prev, { id: 1 })
            this._popups.push({ id: id + 1, message, running: false })
        }
        let i = 0
        this._popup_queue()
    }

    _popup_queue() {
        let next_popup;
        if (this._popups.length < 1) {
            return;
        } else {
            next_popup = this._popups.reduce((prev, cur) => {
                if (prev.id < cur.id && prev.running == false) {
                    return prev
                }
                return cur
            })
        }
        let running = []
        this._popups.forEach(value => {
            if (value.running !== false) {
                running.push(value)
            }
        })
        if (running.length < 3 && next_popup.running == false) {
            this._createPopup(next_popup)
        }

    }
    _createPopup(popup) {
        let popup_box = document.getElementById(this.popup_box_id) ? document.getElementById(this.popup_box_id) : document.createElement('div').setAttribute('id', this.popup_box_id);
        let popup_message_box = document.createElement('div')
        let close_popup = document.createElement('i')
        let popup_message = document.createElement('p')
        let unique_id = (Date.now() + Math.random()).toString(36)

        close_popup.setAttribute('class', 'bi bi-x-lg')
        close_popup.setAttribute('id', unique_id)
        popup_message_box.setAttribute('class', 'popup_message_box')
        popup_message.innerText = popup.message
        popup_message_box.append(close_popup)
        popup_message_box.append(popup_message)
        popup_box.appendChild(popup_message_box)

        // close popup call
    
        this._popups.find(value => value.id == popup.id).running = setTimeout( () => this._closePopup(popup.id, close_popup.parentNode), 1000 * 3)
        close_popup.addEventListener('click', () => this._closePopup(popup.id, close_popup.parentNode, popup.running))
    }
    _closePopup(id, close_popup, timer = null) {
        let newQueue = this._popups.filter(value => value.id !== id)
        this._popups = newQueue;
        close_popup.remove()
        this._popup_queue()
    }
}