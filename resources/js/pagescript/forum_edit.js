import EditorJS from "@editorjs/editorjs";

const editor = new EditorJS({
    placeholder : 'write your description here',
    autofocus : true
})

const forum_submit = document.getElementById('forum')
forum_submit.addEventListener('submit', (e) => {
    const text = document.querySelector("input[name='description']")
    editor.save().then((data) => text.value = JSON.stringify(data))
    return true
})
