import ErrorHandler from '../asset/LaravelErrorParser'
import Chunker from "../asset/ChunkUpload";
import PopupHandler from "../asset/PopupHandler";
import { Modal } from 'bootstrap';
//tutorial upload
const popup = new PopupHandler();
const error = new ErrorHandler(popup);

const tutorial_input_element = document.getElementById("tutorial");
const tutorial_upload_box = document.getElementById('tutorial-upload-box')
const tutorial_progress_box = document.getElementById('tutorial-progress-box')
const tutorial_progress_bar = document.getElementById('tutorial-progress-bar')
const tutorial_progress_cancel = document.getElementById('tutorial-up-cancel')
const tutorial_progress_pause = document.getElementById('tutorial-up-pause')
if (tutorial_input_element) {
  tutorial_input_element.addEventListener("change", (e) => {
    let file = e.target.files[0];
    let url = `/course/${course.id}/tutorial`
    if (file.typ == 'video/mp4') {
      popup.addPopup('file must be a mp4 type')
      return
    }
    if (file.size > 1024 * 1024 * 200) {
      popup.addPopup('file must not be bigger then 200 mb')
      return
    }
    let data = {
      tutorial_name: (Date.now() + Math.random()).toString(36),
      tutorial_type: file.type,
    };
    let uploader = new Chunker(file)
    uploader.startUpload(url, data)
    tutorial_upload_box.classList.add('hide')
    tutorial_progress_box.classList.remove('hide')


    //canceler and pauser
    const pause_or_resume = () => {

      if (tutorial_progress_pause.classList.contains('pause')) {
        tutorial_progress_pause.innerText = 'resume'
        tutorial_progress_pause.classList.remove('pause')
        tutorial_progress_pause.classList.add('resume')
        uploader.pauseUpload()
      } else {
        tutorial_progress_pause.innerText = 'pause'
        tutorial_progress_pause.classList.remove('resume')
        tutorial_progress_pause.classList.add('pause')
        uploader.resumeUpload()
      }
    }
    tutorial_progress_cancel.addEventListener('click', (e) => {
      e.preventDefault()
      clearBox()
      uploader.cancelUpload()
    }, { once: true })
    tutorial_progress_pause.addEventListener('click', pause_or_resume)


    //show progrews
    uploader.showProgress = progress => {
      progress = progress > 100 ? 100 : progress
      tutorial_progress_bar.innerHTML = `${progress} %`
      tutorial_progress_bar.setAttribute('aria-valuenow', progress)
      tutorial_progress_bar.setAttribute('style', `width:${progress}%`)
    }

    function clearBox() {
      tutorial_progress_pause.removeEventListener('click', pause_or_resume)
      tutorial_input_element.value = null;
      tutorial_progress_pause.classList.add('pause')
      tutorial_progress_pause.classList.remove('resume')
      tutorial_progress_pause.innerText = 'pause'
      tutorial_upload_box.classList.remove('hide')
      tutorial_progress_box.classList.add('hide')
    }

    uploader.showResponse = (res, err) => {
      if (err !== null) {
        clearBox()
        if (err.status == 422) {
          error.inputHandle(err.data, null, true)
        } else {
          error.simpleError(err.data.message)
        }
      }
      if (res !== null) {
        clearBox()
        popup.addPopup('tutorial upload complete')
        location.reload()
      }
    }
  });
}


//introduction upload

const introduction_input_lement = document.getElementById('introduction');
const introduction_upload_box = document.getElementById('introduction-upload-box')
const introduction_progress_box = document.getElementById('introduction-progress-box')
const introduction_progress_bar = document.getElementById('introduction-progress-bar')
const introduction_progress_cancel = document.getElementById('introduction-up-cancel')
const introduction_progress_pause = document.getElementById('introduction-up-pause')
const video_box = document.getElementById('introduction-video')

if (introduction_input_lement) {
  introduction_input_lement.addEventListener("change", (e) => {
    e.preventDefault()
    let file = e.target.files[0];
    let url = `/update/course/${course.id}/introduction`;
    if (file.typ == 'video/mp4') {
      popup.addPopup('file must be a mp4 type')
      return
    }
    if (file.size > 1024 * 1024 * 200) {
      popup.addPopup('file must not be bigger then 200 mb')
      return
    }

    let input_data = {
      introduction_name: (Date.now() + Math.random()).toString(36),
      introduction_type: file.type,
    };
    //upload file
    let uploader = new Chunker(file)
    uploader.startUpload(url, input_data, csrf)

    //show uploader
    introduction_progress_box.classList.remove('hide')
    introduction_upload_box.classList.add('hide')

    //canceler and pauser
    const pause_or_resume = () => {
      if (introduction_progress_pause.classList.contains('pause')) {
        introduction_progress_pause.innerText = 'resume'
        introduction_progress_pause.classList.remove('pause')
        introduction_progress_pause.classList.add('resume')
        uploader.pauseUpload()
      } else {
        introduction_progress_pause.innerText = 'pause'
        introduction_progress_pause.classList.remove('resume')
        introduction_progress_pause.classList.add('pause')
        uploader.resumeUpload()
      }
    }
    introduction_progress_cancel.addEventListener('click', (e) => {
      e.preventDefault()
      clearBox()
      uploader.cancelUpload()

    }, { once: true })
    introduction_progress_pause.addEventListener('click', pause_or_resume)


    //show progrews
    uploader.showProgress = progress => {
      progress = progress > 100 ? 100 : progress
      introduction_progress_bar.innerHTML = `${progress} %`
      introduction_progress_bar.setAttribute('aria-valuenow', progress)
      introduction_progress_bar.setAttribute('style', `width:${progress}%`)
    }

    //clear box 
    function clearBox() {
      introduction_progress_pause.removeEventListener('click', pause_or_resume)
      introduction_input_lement.value = null;
      introduction_progress_pause.classList.remove('resume')
      introduction_progress_pause.classList.add('pause')
      introduction_progress_pause.innerText = 'pause'
      introduction_upload_box.classList.remove('hide')
      introduction_progress_box.classList.add('hide')
    }

    uploader.showResponse = (res, err) => {
      if (err !== null) {
        console.log(err)
        clearBox()
        if (err.status == 422) {
          error.inputHandle(err.data, null, true)
        } else {
          error.simpleError(err.data.message)
        }
      }
      if (res !== null) {
        clearBox()
        popup.addPopup('introduction upload complete')
        video_box ? video_box.src = res.data.file_link : location.reload()
      }
    }
  });

}

//tutorial streaming

const tutorial_links = document.querySelectorAll('.watch-tutorial')
let video_modal = new Modal(document.getElementById('tutorial-video'), { backdrop: 'static', keyboard: false });

if (tutorial_links.length > 0) {
  tutorial_links.forEach(node => {
    node.addEventListener('click', (e) => showTutorial(e))
  });
}

const showTutorial = async (e) => {
  const id = e.target.getAttribute('tutorial')
  const video_frame = document.getElementById('video-frame')
  const close_modal = document.getElementById('close-modal')
  try {
    // const res = await fetch(`/show/tutorial/${id}/${course.id}`, {
    //   method: 'get',
    // })
    // res.blob().then(blob => {
    //   const src = URL.createObjectURL(blob)
    // })
    video_modal.show();
    video_frame.src = `https://skillshare.com/show/tutorial/${id}/${course.id}`;
    close_modal.addEventListener('click', () => {
      video_frame.src = '';
      video_modal.hide()
    }, { once: true });
  } catch (error) {
    console.log('err', error)
  }
}