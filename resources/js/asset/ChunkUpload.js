import axios from "axios";

const chunk_upload = async (
    file,
    url,
    inputs = {},
    chunksize = 1024 * 1024 * 3
) => {
    if (!file || !url) {
        return {
            status: 'failed',
            error: "please provide a file and a url",
        };
    }
    //return data 
    //chunking function
    let uploaded = 0;
    let filesize = file.size;
    let initial_chunk_size = Math.min(chunksize, file.size);

    while (uploaded !== filesize) {
        const chunked_file = await chunker(file, uploaded, Math.min(uploaded + initial_chunk_size, filesize))
        try {
            let data = {
                ...inputs,
                full_file_size: filesize,
                last_chunk: Math.min(uploaded + initial_chunk_size, filesize) === file.size,
                chunk_file: chunked_file,
            }
            
            const response = await uploader(url, data)
            uploaded = Math.min(uploaded + initial_chunk_size, filesize)
            if (uploaded == file.size) {
                return {
                    status: 'complete',
                    data: response,
                    error: null,
                }
            }
        } catch (error) {
            return {
                status: 'failed',
                error: error,
                data: null,
            }
        }
    }

}


function chunker(file, start, end) {
    let reader = new FileReader();
    let blob = file.slice(start, end);
    reader.readAsDataURL(blob);
    return new Promise((resolve, reject) => {
        reader.onerror = () => {
            reader.abort()
            reject(new DOMException('file proccess filed'))
        }
        reader.onload = () => {
            resolve(reader.result)
        }
    })
}
function uploader(url, data, config = {}) {
    return new Promise((resolve, reject) => {
        axios.post(url, data, config)
            .then(
                (res) => resolve(res.data),
                (err) => reject(err.response)
            )
    })
}

export default chunk_upload