import axios from "axios";

class ChunkUpload {

    constructor(file, chunksize = 1024 * 1024 * 3, popup = null) {
        this.chunksize = chunksize
        this.file = file
        this.filesize = file.size
        this.filetype = file.type
        this.chunks = Math.ceil(this.filesize / Math.min(chunksize, this.filesize))
        this.chunkData = {}
        this.popup = popup
    }


    async startUpload(url, inputs, csrf = null, bearer = null) {
        await this._chunkFile()
        let lastChunk = Object.keys(this.chunkData).length
        for (const chunkN in this.chunkData) {
            let form = new FormData
            form.append('chunk_file', this.chunkData[chunkN].data)
            Object.entries(inputs).forEach(([key, value]) => {
                form.append(key, value)
            })
            try {
                let res = await this._uploadFile(url, form, csrf)
                this.chunkData[chunkN].status = 'complete'

            } catch (error) {
                return {
                    status: error.status,
                    error: error.data,
                }
            }

        }
    }
    resumeUpload() {

    }
    cancelUpload() {
        this.cancel.cancel()
    }
    pauseUpload() {
    }


    async _chunkFile() {
        let completed = 0;
        const chunk = (blob) => (new Promise((resolve, reject) => {
            const reader = new FileReader()
            reader.readAsDataURL(blob)
            reader.onload = () => {
                resolve(reader.result)
            }
        }))
        for (let i = 0; i <= this.chunks; i++) {
            let blob = this.file.slice(completed, Math.min(this.chunksize + completed, this.filesize))
            let data = await chunk(blob)
            let name = 'chunk-' + (Object.keys(this.chunkData).length + 1)
            this.chunkData[name] = {
                data: data,
                status: 'ready',
            }
            completed = Math.min(this.chunksize + completed, this.filesize)
        }

    }
    _uploadFile(url, data, csrf) {

        return new Promise((resolve, reject) => {
            this.cancel = axios.CancelToken.source()
            axios.post(url, data, {
                onUploadProgress: this.progress(),
                onabort: reject({ status: 'abort' }),
                cancelToken: this.cancel.token,
            }).then(res => resolve(res.response), err => reject(err.response))
        })
    }
    progress
}




export default ChunkUpload