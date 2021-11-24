

let editor;
const edit_box = document.querySelector('#description')
if (edit_box) {
    ClassicEditor.create(edit_box, {
        toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote'],

    }).then(ckeditor => { editor = ckeditor; editor.setData(forum_details.description) })
        .catch(error => console.log(error))
    // const forum_submit = document.getElementById('forum')
}
