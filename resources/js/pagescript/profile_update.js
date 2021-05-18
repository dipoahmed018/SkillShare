import axios from "axios";
import Cropper from "cropperjs";

function pagescripts() {    
    //form default handel
    const image_form = document.getElementById('user_update_form_image')
    const name_form = document.getElementById('user_update_form_name')

    image_form.addEventListener('submit', (event) => event.preventDefault())
    name_form.addEventListener('submit', (event) => event.preventDefault())


    //image handel
    let image_preview = document.getElementById('image_crop_preview')
    image_preview.classList.add('hide')


    const startCropper = (event) => {
        //get upload image
        let image = event.target.files[0]
        image_preview.classList.add('hide')


        //error handle
        let error_box = document.getElementById('profile_picture_scripterror')
        error_box.innerText = '';
        if (image.type !== 'image/jpeg' && image.type !== 'image/png' && image.type !== 'image/jpg') {
            return error_box.innerText = 'File type must be under "jpeg, jpg, png"'
        }
        if(!image){
            return error_box.innerText = 'please provide an image'
        }

        //crop handle
        image_preview.classList.remove('hide')

        let imageBlob = URL.createObjectURL(image)
        const image_crop = document.getElementById('image_crop')
        image_crop.src = imageBlob
        const cropper = new Cropper(image_crop,{
            viewMode : 1,
            autoCrop : true,
            movable : false,
            minCropBoxWidth : 200,
            minCropBoxHeight: 200,
        })

        const cancel = document.getElementById('cancel_upload_image')
        const upload = document.getElementById('upload_image')
        //cancel handel
        cancel.addEventListener('click', (e) => {
            cropper.destroy()
            image_crop.src = null
            event.target.value = ''
            image_preview.classList.add('hide')


        })
        //upload handel
        upload.addEventListener('click',() => {
            fetch('/user/update',{
                method: 'put',
                // body : ,
            })
        })
    }
    document.getElementById('profile_picture').addEventListener('change', startCropper)

}
window.addEventListener('load', pagescripts());