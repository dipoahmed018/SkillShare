// compotnent scripts //
require('../component/course/review')

// neccessery modules 
import Chunker from "../asset/ChunkUpload";
import PopupHandler from "../asset/PopupHandler";
import { Modal, Tooltip } from 'bootstrap';

const one_mb = 1024 * 1028
const popup = new PopupHandler();

//trigger bootstrap tooltip element
document.querySelectorAll('[data-bs-hover="tooltip"]').forEach(el => {
  new Tooltip(el)
})

//tutorial streaming

const tutorial_links = [...document.getElementsByClassName('watch-tutorial')]
const tutorial_modal_element = document.getElementById('tutorial-video-modal')
let tutorial_watcher_modal = new Modal(tutorial_modal_element, { backdrop: 'static', keyboard: false });


tutorial_links?.forEach(element => {
  element.addEventListener('click', showTutorial)
});

function showTutorial(e) {
  const tutorial_id = e.currentTarget.getAttribute('data-tutorial-id')
  const video_frame = tutorial_modal_element.querySelector('#video-frame')
  const close_modal = tutorial_modal_element.querySelector('#close-modal')
  video_frame.src = `/show/tutorial/${tutorial_id}/${course.id}`;

  close_modal.addEventListener('click', () => {
    video_frame.src = '';
    tutorial_watcher_modal.hide()
  }, { once: true });

  tutorial_watcher_modal.show();
}

//course delete
const course_deleter_btn = document.getElementById('course-deleter-btn')
const course_deleter_modal = new Modal('#course-delete-modal')

course_deleter_btn?.addEventListener('click', (e) => {
  course_deleter_modal.show()
})


//thumbnail update

const thumbnail_update_btn = document.getElementById('thumbnail-updater-btn')
const thumbnail_update_form = document.getElementById('thumbnail-update-form')
const thumbnail_input = thumbnail_update_form.querySelector('[name="thumbnail"]')
const thumbnail_error = thumbnail_update_form.querySelector('.error-box')
const thumbnail_update_modal = new Modal('#thumbnail-update-modal')

thumbnail_update_btn?.addEventListener('click', () => {
  thumbnail_update_modal.show()
})

thumbnail_input?.addEventListener('change', async (e) => {
  //resetign the thumbnial error box
  thumbnail_error.innerHTML = ''

  //selecting the input image
  const image = e.target.files[0]

  //creating multipart form data and inserting the image as thumnail
  const form_data = new FormData
  form_data.append('thumbnail', image)

  //filter image 
  if (image.size > one_mb * 10) {
    const error = document.createElement('p')
    error.innerText = 'image must be shorter then 10 mb'
    thumbnail_error.append(error)
  }
  const res = await fetch(`/update/course/${course.id}/thumbnail`, {
    method: 'post',
    body: form_data,
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': window.csrf,
    }
  })
  if (res.status == 422) {

    const res_data = await res.json()

    res_data?.errors?.thumbnail?.forEach(element => {

      const error = document.createElement('p')
      error.innerText = element
      thumbnail_error.append(error)

    });
  }
  if (res.status == 200) {
    const res_data = await res.json()
    const file_link = res_data?.data?.file_link
    course.thumbnail = res.data
    add_thumbnail(file_link)
    thumbnail_update_modal.hide()
  }
})

//add new thumbnail 
function add_thumbnail(file_link) {
  const thumbnail_parent = document.querySelector('.thumbnail-video')
  if (course.introduction) {
    thumbnail_parent.querySelector('video').poster = file_link
    return true;
  }
  thumbnail_parent.innerHTML = ''
  const image_element = document.createElement('img')
  image_element.scr = file_link
  thumbnail_parent.append(image_element)
}

//introduction update

const introduction_update_btn = document.getElementById('introduction-updater-btn')
const introduction_update_box = document.getElementById('introduction-update-modal')
const introduction_update_modal = new Modal(introduction_update_box)

introduction_update_btn?.addEventListener('click', () => {
  introduction_update_modal.show()
})


//introduction upload
const introduction_input_lement = introduction_update_box.querySelector('[name="introduction"]')
const introduction_upload_control = introduction_update_box.querySelector('.upload-control')
const introduction_progress_bar = introduction_upload_control.querySelector('.progress-bar')
const introduction_progress_value = introduction_upload_control.querySelector('.progress-value')
const introduction_progress_cancel = introduction_upload_control.querySelector('.cancel-upload')
//handele introduction video uploading
introduction_input_lement?.addEventListener('change', (e) => {

  //select introduction video file
  const file = e.target.files[0]
  const url = `/update/course/${course.id}/introduction`;

  //video size filter
  if (file.size > 1024 * 1024 * 200) {
    popup.addPopup('file must not be bigger then 200 mb')
    return
  }

  // additional data for chunk upload 
  let form_data = {
    introduction_name: (Date.now() + Math.random()).toString(36),
    introduction_type: file.type,
  };

  //create a instace of chunk uploader with given file
  let uploader = new Chunker(file)

  // start uploading
  uploader.startUpload(url, form_data, window.csrf)

  //show upload progress
  introduction_upload_control.style.display = 'flex'
  uploader.showProgress = progress => {
    progress = progress > 100 ? 100 : progress
    introduction_progress_value.innerText = `${progress}%`
    introduction_progress_bar.style.width = `${progress}%`
  }

  //cancel upload
  introduction_progress_cancel.addEventListener('click', (e) => {
    //cancel upload will send a last request to server for deleting the file
    uploader.cancelUpload()

    //reset form and uploading progress
    reset_introduction_upload()

    //hide the introduction uploader modal
    introduction_update_modal.hide()
  }, { once: true })

  //handel response
  uploader.showResponse = (res, err) => {
    if (res?.data) {
      course.introduction = res.data
      reset_introduction_upload()
      add_introduction(res.data.file_link)
      introduction_update_modal.hide()
      popup.addPopup('introduction upload complete')
    }
  }
})
//reset uploading actions
function reset_introduction_upload() {

  //reset introduction upload form 
  introduction_update_box.querySelector('form').reset()

  //progress reset
  introduction_upload_control.style.display = 'none'
  introduction_progress_value.innerText = `0%`
  introduction_progress_bar.style.width = `0%`
}

function add_introduction(file_link) {
  const thumbnail_parent = document.querySelector('.thumbnail-video')
  thumbnail_parent.innerHTML = ''
  const video = document.createElement('video')
  video.src = file_link
  course.thumbnail ? video.poster = course.thumbnail.file_link : null;
  video.style.width = '100%'
  video.controls = true
  thumbnail_parent.append(video)
}

//tutorai upload box
const tutorial_upload_form = document.querySelector('.add-tutorial')
const tutorial_upload_dropbox = document.querySelector('[for="tutorial-upload"]')
const tutorial_input = document.getElementById('tutorial-upload')
const tutorial_upload_control = tutorial_upload_form?.querySelector('.upload-control')
const tutorial_progress_bar = tutorial_upload_form?.querySelector('.progress-bar')
const tutorial_progress_cancel = tutorial_upload_form?.querySelector('.cancel-upload')
const tutorial_progress_value = tutorial_upload_form?.querySelector('.progress-value')

//preventinf browser from opening the file
document.addEventListener('dragover', (e) => {
  e.preventDefault()
})

tutorial_upload_dropbox?.addEventListener('drop', (e) => {
  const file = e.dataTransfer.files[0]
  if (file) {
    upload_tutorial(file)
  }
})

tutorial_input?.addEventListener('change', (e) => {
  const file = e.target.files[0]
  upload_tutorial(file)
})

function upload_tutorial(file) {
  const { type, size } = file
  const acceptable_type = ['video/mp4']
  const url = `/course/${course.id}/tutorial`


  //file filter
  if (!acceptable_type.includes(type)) {
    popup.addPopup('file type must be mp4')
    tutorial_upload_form.reset()
    return true
  }
  if (size > one_mb * 200) {
    popup.addPopup('file must not be bigger then 200 mb')
    tutorial_upload_form.reset()
    return true
  }

  let form_data = {
    tutorial_name: (Date.now() + Math.random()).toString(36),
    tutorial_type: type,
  };

  //create a instace of chunk uploader with given file
  let uploader = new Chunker(file)

  // start uploading
  uploader.startUpload(url, form_data, window.csrf)

  //show upload progress
  tutorial_upload_control.style.display = 'flex'
  uploader.showProgress = progress => {
    progress = progress > 100 ? 100 : progress
    tutorial_progress_bar.style.width = `${progress}%`
    tutorial_progress_value.innerText = `${progress}%`
  }

  //cancel upload
  tutorial_progress_cancel.addEventListener('click', (e) => {
    //cancel upload will send a last request to server for deleting the file
    uploader.cancelUpload()

    reset_tutorial_upload()

  }, { once: true })

  //handel response
  uploader.showResponse = (res, err) => {
    if (res?.data?.success) {
      course.tutorial_details.push(res.data.data)
      add_tutorial_element(res.data.data)
      reset_tutorial_upload()
      popup.addPopup('tutorial upload complete')
    }
  }
}

function reset_tutorial_upload() {
  //progress box reset
  console.log(tutorial_upload_control);
  tutorial_upload_control.style.display = 'none'
  tutorial_progress_bar.style.width = `0`
  tutorial_progress_value.innerText = `0`

  //reset form and uploading progress
  tutorial_upload_form.reset()
}
function add_tutorial_element(tutorial_details) {
  const tutorial_template =
    `
  <div draggable="true" class="tutorial-card row">
    <div class="details col-sm-10">
        <h3 id="title">${tutorial_details.title}</h3>
        <span>created_at</span>
    </div>

    <div class="edit col">
        <a class="btn btn-warning" href="/course/${course.id}/tutorial/${tutorial_details.id}">Edit</a>
        <div class="watch">
          <button tutorial=${tutorial_details.id} class="btn btn-primary watch-tutorial" id='open-tutorial'>Watch</button>
        </div>
    </div>
  </div> 
`
  document.querySelector('.tutorials').insertAdjacentHTML('beforebegin', tutorial_template)
}

//tutorial edit 

const tutorial_container = document.querySelector('.tutorials')
const tutorial_cards = [...document.getElementsByClassName('tutorial-card')]
const edit_initiator_btn = [...document.getElementsByClassName('tutorial-title-editor')]

edit_initiator_btn?.forEach(element => {
  element.addEventListener('click', (e) => {
    e.stopPropagation()
    const tutorial_id = e.target.getAttribute('data-tutorial-id')
    const tutorial_card = document.getElementById(`tutorial-${tutorial_id}`)
    const title = tutorial_card.querySelector('.title')
    const title_input = tutorial_card.querySelector('input[name="title"]')
    const save_button = tutorial_card.querySelector('.tutorial-title-save')

    //hide edit buttton
    e.target.style.display = 'none'

    //show save button
    save_button.style.display = 'inline-block'

    //show title edit form 
    title_input.style.display = 'block'

    //hide title
    title.style.display = 'none'

    save_button.addEventListener('click', () => {
      update_tutorial_title(tutorial_id, title_input.value)
    })
  })
})

function update_tutorial_title(tutorial_id, title) {
  if (title.length < 10) { popup.addPopup('The title must be of minimum 10 character'); return }

  fetch(`/update/course/tutorial/${tutorial_id}/title`, {
    method: 'put',
    body: JSON.stringify({ 'title': title }),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': window.csrf,
    }
  })
    .then(res => res.ok ? res.json() : Promise.reject(res))
    .then(data => {
      const tutorial_card = document.getElementById(`tutorial-${tutorial_id}`)
      const title = tutorial_card.querySelector('.title')
      const title_input = tutorial_card.querySelector('input[name="title"]')
      const save_button = tutorial_card.querySelector('.tutorial-title-save')
      const edit_button = tutorial_card.querySelector('.tutorial-title-editor')

      //updating the tutorial card
      title.innerText = data.title
      title_input.value = data.title
      title.style.display = 'block'
      title_input.style.display = 'none'
      save_button.style.display = 'none'
      edit_button.style.display = 'inline-block'
    })
    .catch(err => console.log(err))
}


//tutorial card position changer
tutorial_cards.forEach(element => {

  element.addEventListener('dragstart', (e) => {
    e.dataTransfer.setData('id', e.target.getAttribute('data-tutorial-id'))
    e.target.style.opacity = '.5'
  })

  element.addEventListener('dragend', e => {
    e.target.style.opacity = '1'
  })

  element.addEventListener('drop', e => {
    e.preventDefault()

    const tutorial_id = e.dataTransfer.getData('id')
    const self_element = e.currentTarget
    const droped_element = document.getElementById(`tutorial-${tutorial_id}`)
    const child_elements = [...tutorial_container.children]

    if (self_element == droped_element) { return }

    if (!child_elements.includes(droped_element)) { true }

    /** if dropped tutorial cards order is bigger(lower) then where it dropped,
     *  then insert it before the tareget element else insert it after
     */

    if (child_elements.indexOf(self_element) > child_elements.indexOf(droped_element)) {
      //going down
      self_element.insertAdjacentElement('afterend', droped_element)
      update_tutorial_order(tutorial_id, child_elements.indexOf(self_element) + 1)
    } else {
      //going up
      self_element.insertAdjacentElement('beforebegin', droped_element)
      update_tutorial_order(tutorial_id, child_elements.indexOf(self_element) + 1)
    }

  })

})

function update_tutorial_order(tutorial_id, order) {
  fetch(`/update/course/tutorial/${tutorial_id}/order`, {
    method: 'put',
    body: JSON.stringify({ 'order': order }),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': window.csrf,
    }
  })
    .then(res => res.ok ? res.json() : Promise.reject(res))
    .then(res => console.log('position changed'))
    .catch(err => console.log('something went wrong'))
}


//tutorial delete 


const tutorial_deleter_modal = document.getElementById('tutorial-delete-modal')
const tutorial_delete_yes = tutorial_deleter_modal.querySelector('.yes')
let tutorial_id;
const tutorial_delete = async (e) => {
  const res = await fetch(`/delete/course/${course.id}/tutorial/${tutorial_id}`, {
    method: 'delete',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': window.csrf,
    },
  })
  if (res.status == 200) {
    document.getElementById(`tutorial-${tutorial_id}`).remove()
    Modal.getInstance(tutorial_deleter_modal).hide()
  }
}

//handel modal shown event
tutorial_deleter_modal?.addEventListener('shown.bs.modal', (e) => {
  //changing the global tutorial_id variable each time the target changes
  tutorial_id = e.relatedTarget.getAttribute('data-tutorial-id')
  tutorial_delete_yes.removeEventListener('click', tutorial_delete)
  tutorial_delete_yes.addEventListener('click', tutorial_delete, { once: true })
})