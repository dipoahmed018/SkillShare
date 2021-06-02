
class ErrorHandler {

    constructor(popup = null) {
        this.popup = popup
    }
    inputHanle(error, error_box = null, pop = false) {
        if (this.popup && error.message && pop == true) {
            this.popup.addPopup(error.message)
        }
        if (!error.errors) {
            return;
        }

        if (!error_box || Object.keys(error_box).length < 1) {
            const { errors } = error
            for (var name in errors) {
                const box = document.getElementById(name)
                if (box) {
                    this.setInputMessageHtml(errors[name], box)
                } else if (pop == true && this.popup) {
                    this.popup.addPopup(errors[name])
                }
            }
        }

        if (error_box && Object.keys(error_box).length > 0) {
            const { errors } = error
            for (var name in errors) {
                if (error_box[name]) {
                    const box = document.getElementById(error_box[name])
                    const res_box = document.getElementById(name)
                    if (box) {
                        this.setInputMessageHtml(errors[name], box)
                    } else if (res_box) {
                        this.setInputMessageHtml(errors[name], res_box)

                    } else if (pop == true && this.popup) {
                        this.popup.addPopup(errors[name])
                    }

                }
            }
        }

    }

    setInputMessageHtml(message, append_to, element_type = 'p') {
        const message_element = document.createElement(element_type)
        const close_element = document.createElement('i')
        close_element.setAttribute('class', 'bi bi-x-lg')
        message_element.innerText = message
        append_to.append(message_element)
        append_to.append(close_element)
        close_element.addEventListener('click', () => this.remove_element(message_element, close_element))
    }
    remove_element(sibling, self) {
        sibling.remove()
        self.remove()
    }

}
export default ErrorHandler