import ErrorHandler from '../asset/LaravelErrorParser'
import chunk_upload from "../asset/ChunkUpload";
import PopupHandler from "../asset/PopupHandler";

//tutorial upload
const popup = new PopupHandler();
const error = new ErrorHandler(popup);
const tutorial_input_element = document.getElementById("tutorial");
if (tutorial_input_element) {
  tutorial_input_element.addEventListener("change", (e) => {
    let file = e.target.files[0];
    let data = {
      tutorial_name: (Date.now() + Math.random()).toString(36),
      tutorial_type: file.type,
    };
    document.getElementById('tutorial-upload-box').classList.add('hide')

    chunk_upload(file, `/course/${course.id}/addvideo`, (total) => progress_bar(total, 'tutorial_progress_box'), 'tutorial_up_cancel', data).then((res) => {
      document.getElementById('tutorial-upload-box').classList.remove('hide')
      if (res.status == 422) {
        error.inputHandle(res.error, null, true)
        return;
      }
      if (res.status !== 'success') {
        error.simpleError(res.error.message, null, true)
        return;
      }
      if (res.status == 'success') {
        popup.addPopup('introduction upload compleate')
        console.log(res);
      }
    });

  });
}


//introduction upload

const introduction_input_lement = document.getElementById('introduction');

if (introduction_input_lement) {
  introduction_input_lement.addEventListener("change", (e) => {
    let file = e.target.files[0];
    let url = `/update/course/${course.id}/introduction`;
    let data = {
      introduction_name: (Date.now() + Math.random()).toString(36),
      introduction_type: file.type,
    };
    document.getElementById('introduction-upload-box').classList.add('hide')
    chunk_upload(file, url, (total) => progress_bar(total, 'introduction_progress_box'), 'introduction_up_cancel', data).then((res) => {
      console.log(res)
      document.getElementById('introduction-upload-box').classList.remove('hide')
      if (res.status == 422) {
        error.inputHandle(res.error, null, true)
        return;
      }
      if (res.status !== 'success') {
        error.simpleError(res.error.message, null, true)
        return;
      }
      if (res.status == 'success') {
        popup.addPopup('introduction upload compleate')
        console.log(res);
      }
    });
  });
}

//chunk upload progress bar 


let progress_fix = 0;
const progress_bar = (total, element) => {
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