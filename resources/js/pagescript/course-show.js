import chunk_upload from "../asset/ChunkUpload";
const tutorial_input_element = document.getElementById("tutorial");
const error_box = document.getElementById("error-box");
if (tutorial_input_element) {
  tutorial_input_element.addEventListener("change", (e) => {
    let file = e.target.files[0];
    if (file.type !== "video/mp4") {
      error_box.innerHTML = "<p> please provide a mp4 file</p>";
      return;
    }
    let data = {
      tutorial_name: (Date.now() + Math.random()).toString(36),
      tutorial_type: file.type,
    };
    chunk_upload(file, `/course/${course.id}/addvideo`, data).then((res) => {
      console.log(res)
    });
  });
}
