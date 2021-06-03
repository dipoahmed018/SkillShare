import ErrorHandler from '../asset/LaravelErrorParser'
import chunk_upload from "../asset/ChunkUpload";
import PopupHandler from "../asset/PopupHandler";

//tutorial upload
const popup = new PopupHandler();
const error = new ErrorHandler(popup);
const tutorial_input_element = document.getElementById("tutorial");
const tutorial_error_box = document.getElementById("tutorial-error-box");
if (tutorial_input_element) {
  tutorial_input_element.addEventListener("change", (e) => {
    let file = e.target.files[0];
    if (file.type !== "video/mp4") {
      tutorial_error_box.innerHTML = "<p> please provide a mp4 file</p>";
      return;
    }
    let data = {
      tutorial_name: (Date.now() + Math.random()).toString(36),
      tutorial_type: file.type,
    };
    chunk_upload(file, `/course/${course.id}/addvideo`, data).then((res) => {
      if (res.status == 'failed') {
        error.simpleError(res.error, 'tutorial-error')
        return;
      }
      if (res.status == 422) {
        error.inputHandle(res.error, null, true)
        return;
      }
      if (res.status !== 'success') {
        error.simpleError(res.error.message, null, true)
        return;
      }
    });
  });
}


//introduction upload

const introduction_input_lement = document.getElementById('introduction');
const introduction_error_box = document.getElementById("introduction-error-box");

if (introduction_input_lement) {
  introduction_input_lement.addEventListener("change", (e) => {
    let file = e.target.files[0];
    let url = `/update/course/${course.id}/introduction`;
    let data = {
      // introduction_name: (Date.now() + Math.random()).toString(36),
      introduction_type: file.type,
    };
    chunk_upload(file, url, data).then((res) => {
      if (res.status == 'failed') {
        error.simpleError(res.error, 'introduction-error')
        return;
      }
      if (res.status == 422) {
        error.inputHandle(res.error, null, true)
        return;
      }
      if (res.status !== 'success') {
        error.simpleError(res.error.message, null, true)
        return;
      }
      if (res.status == 'success') {
        console.log(res);
      }

    });
  });
}