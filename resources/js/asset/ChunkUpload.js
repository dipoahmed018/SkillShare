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
        this.full_file_size = 0
        this._uploaded = 0
    }


    async startUpload(url, inputs, csrf = null, bearer = null) {
        //calculate total

        this._url = url;
        this._inputs = inputs;


        if (Object.keys(this.chunkData).length < 1) {
            console.log('chunking')
            await this._chunkFile()
        }
        let input_length = new TextEncoder().encode(JSON.stringify(inputs)).length
        let blob_length = 0;
        this._totalUpload = (input_length * this.chunks) + this.full_file_size
        let lastChunk = Object.keys(this.chunkData).pop()
        for (const chunkN in this.chunkData) {
            if (this.chunkData[chunkN].status == 'complete') {
                continue;
            }
            let form = {}
            blob_length = this.chunkData[chunkN].data.length
            form['chunk_file'] = this.chunkData[chunkN].data
            Object.entries(inputs).forEach(([key, value]) => {
                form[key] = value
            })
            let header = lastChunk == chunkN ? { 'x-last': true } : {}
            try {
                let res = await this._uploadFile(url, form, header)
                this.chunkData[chunkN].status = 'complete'
                this._uploaded += (blob_length + input_length)
                if (chunkN == lastChunk) {
                    this.showResponse(res, null)
                    break
                }
            } catch (error) {
                if (error.message == 'paused' || error.message == 'canceled') {
                    break;
                } else {
                    this.showResponse(null, error.response)
                    break
                }
            }

        }
    }
    async resumeUpload() {
        try {
            const res = await this._uploadFile(this._url, this._inputs, { 'x-resumeable': true })
            if (res) {
                this.startUpload(this._url, this._inputs)
            }

        } catch (error) {
            this.startUpload(this._url, this._inputs)
        }
    }
    pauseUpload() {
        this.cancel.cancel('paused')
    }
    async cancelUpload() {
        this.cancel.cancel('canceled')
        try {
            const res = await this._uploadFile(this._url, this._inputs, {
                'x-cancel': true,
            })
            return res
        } catch (error) {
            return error.response
        }
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
        for (let i = 1; i <= this.chunks; i++) {
            let blob = this.file.slice(completed, Math.min(this.chunksize + completed, this.filesize))
            let data = await chunk(blob)
            this.full_file_size += data.length
            let name = 'chunk-' + (Object.keys(this.chunkData).length + 1)
            this.chunkData[name] = {
                data: data,
                status: 'ready',
            }

            completed = Math.min(this.chunksize + completed, this.filesize)
        }

    }
    _uploadFile(url, data, header = {}) {

        return new Promise((resolve, reject) => {
            this.cancel = axios.CancelToken.source()
            axios.post(url, data, {
                onUploadProgress: (e) => this._fix_progress(e),
                onabort: () => reject(),
                cancelToken: this.cancel.token,
                headers: {
                    ...header
                }
            }).then(res => resolve(res), err => reject(err))
        })
    }

    _fix_progress(e) {
        this.showProgress(Math.round(((e.loaded + this._uploaded) / this._totalUpload) * 100))
    }
    showProgress

    showResponse

}




export default ChunkUpload