import axios from "axios";

    let res;
    if (!file || !url) {
        return {
            status: 'failed',
            error: "please provide a file and a url",
        };
    }
    if (file.type !== 'video/mp4') {
        return {
            status: 'failed',
            error: { message: 'please provide a mp4 file' }
        }
    }

// const config = {
//     cancelToekn: cancel_token.token,
//     onUploadProgress: (progressEvent) => progressBar(progressEvent)
// }
// let data = {
//     ...inputs,
//     full_file_size: filesize,
//     last_chunk: Math.min(uploaded + initial_chunk_size, filesize) === file.size,
//     chunk_file: chunked_file,
// }

// function progressBar(e) {
//     let extra_data = (e.total - next_chunk_size)

//     if (uploaded == 0) {
//         uploading = Math.floor((e.loaded / (filesize + (extra_data * Math.ceil(initial_chunk_size / filesize)))) * 100);

//     } else {
//         uploading = Math.floor(((e.loaded + uploaded + extra_data) / (filesize + (extra_data * Math.ceil(initial_chunk_size / filesize)))) * 100);
//     }
//     if (progressReport instanceof Function) {
//         progressReport(uploading)
//     }
// }
// export default chunk_upload