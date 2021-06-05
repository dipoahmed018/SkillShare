import ErrorHandler from '../asset/LaravelErrorParser'
import Chunker from "../asset/ChunkUpload";
import PopupHandler from "../asset/PopupHandler";

//tutorial upload
const popup = new PopupHandler();
const error = new ErrorHandler(popup);


const tutorial_input_element = document.getElementById("tutorial");
const tutorial_upload_box = document.getElementById('tutorial-upload-box')
const tutorial_progress_box = document.getElementById('tutorial-progress-box')
const tutorial_progress_cancel = document.getElementById('tutorial-up-cancel')
const tutorial_progress_pause = document.getElementById('tutorial-up-pause')

if (tutorial_input_element) {
  tutorial_input_element.addEventListener("change", (e) => {
    let file = e.target.files[0];
    let url = `/course/${course.id}/addvideo`
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
      full_file_size: file.size,
    };
    let uploader = new Chunker(file)
    uploader.startUpload(url, file)
    tutorial_upload_box.classList.add('hide')
    tutorial_progress_box.classList.remove('hide')
  });
}


//introduction upload

const introduction_input_lement = document.getElementById('introduction');
const introduction_upload_box = document.getElementById('introduction-upload-box')
const introduction_progress_box = document.getElementById('introduction-progress-box')
const introduction_progress_cancel = document.getElementById('introduction-up-cancel')
const introduction_progress_pause = document.getElementById('introduction-up-pause')

if (introduction_input_lement) {
  introduction_input_lement.addEventListener("change", (e) => {
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
    let uploader = new Chunker(file)
    uploader.startUpload(url, input_data, csrf)


    introduction_progress_box.classList.remove('hide')
    introduction_upload_box.classList.add('hide')

    //canceler 
    introduction_progress_cancel.addEventListener('click', () => {
      introduction_upload_box.classList.remove('hide')
      introduction_progress_box.classList.add('hide')
      uploader.cancelUpload()
    })

    //progress
    uploader.progress = (byte) => {
      console.log(byte)
      //count progress
      // show_progress(byte, 'introduction-progress-box')
    }
  });


}

//chunk upload progress bar 


let progress_fix = 0;
const show_progress = (total, element) => {
  const parent_element = document.getElementById(element)
  let progress_element
  parent_element.childNodes.forEach(node => {
    if (node.className == 'progress') {
      progress_element = node.childNodes.item(1)
    }
  });
  if (parent_element) {

    if (total >= 100) {
      if (parent_element) {
        parent_element.classList.add('hide')
      }
      return;
    }
    if (total > progress_fix) {
      progress_fix = total;
      if (parent_element) {
        parent_element.classList.remove('hide')
      }
      progress_element.innerHTML = `${progress_fix} %`
      progress_element.setAttribute('aria-valuenow', progress_fix)
      progress_element.setAttribute('style', `width:${progress_fix}%`)
    }
  }
}
