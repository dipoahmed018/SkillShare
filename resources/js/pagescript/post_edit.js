// import { Filter_length, Inject_images, Image_picker } from '../asset/CKEditorHelper'
// import { Modal } from 'bootstrap'

// let question_editor;
// let answer_editor;
// const accept_answer = document.querySelectorAll('.accept-answer')
// const answer_create_btn = document.getElementById('answer-create')
// const answer_edit_btn = document.querySelectorAll('.answer-edit')
// const answer_editor_box = document.querySelector('#answer-editor-box')
// const answer_forum = document.getElementById('edit-answer')
// const like_buttons = document.querySelectorAll('.vote')
// const question_edit_btn = document.querySelector('.question-edit')
// const preview_question_btn = document.getElementById('preview-question-button')
// const question_forum = document.getElementById('edit-question')
// const question_box = document.getElementById('question-box')
// const question_title = document.querySelector('.question-title')
// const question_content = document.querySelector('.question-content')
// const question_editor_box = document.querySelector('#question-editor-box')

// //initialize modal form answer create or edit
// const modal = new Modal('#answer-editor')
// answer_create_btn?.addEventListener('click', (e) => {

//     //change action url
//     answer_forum.action = `/${question.id}/answer/create`

//     //remove put method
//     document.getElementById('forum-method')?.remove()

//     document.getElementById('answer-submit').value = 'create answer'

//     //set editor data to empty
//     answer_editor.setData('')
//     modal.show()
// })
// answer_edit_btn?.forEach(e => {
//     e.addEventListener('click', e => {

//         //set editor content
//         const answer = answers.find((answer) => answer.id == e.target.getAttribute('data-bs-id'))
//         answer_editor.setData(answer.content)
//         //change action url
//         answer_forum.action = `/post/edit/${answer.id}`
//         document.getElementById('answer-submit').value = 'edit answer'

//         //change forum method to put
//         const method_element = document.createElement('input')
//         method_element.type = 'hidden'
//         method_element.value = 'put'
//         method_element.name = '_method'
//         method_element.id = 'forum-method'
//         answer_forum.prepend(method_element)

//         modal.show()
//     })
// })


// //initialize ck question_editor for question editor
// if (question_editor_box) {
//     ClassicEditor.create(question_editor_box, {
//         toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
//         simpleUpload: {
//             uploadUrl: `/save/image`,
//             withCredentials: true,
//             headers: {
//                 'X-CSRF-TOKEN': window.csrf,
//             }
//         }
//     }).then(ckeditor => {
//         ckeditor.setData(question.content)
//         question_editor = ckeditor
//     })
//         .catch(error => console.log(error))
// }

// //initialize ck answer_creator or editor
// if (answer_editor_box) {
//     ClassicEditor.create(answer_editor_box, {
//         toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
//         simpleUpload: {
//             uploadUrl: `/save/image`,
//             withCredentials: true,
//             headers: {
//                 'X-CSRF-TOKEN': window.csrf,
//             }
//         }
//     }).then(ckeditor => {
//         answer_editor = ckeditor
//     })
//         .catch(error => console.log(error))
// }



// //toggole edit box and question shower
// question_edit_btn?.addEventListener('click', (e) => {
//     if (question_forum.classList.contains('hide')) {
//         question_forum.classList.remove('hide')
//         question_box.classList.add('hide')
//     }
// })

// preview_question_btn?.addEventListener('click', (e) => {
//     e.preventDefault()
//     const content = question_editor.getData()
//     const title = document.querySelector('[name="title"]').value
//     question_title.innerText = title
//     question_content.innerHTML = content
//     question_forum.classList.add('hide')
//     question_box.classList.remove('hide')
// })

// //submit fourm 
// answer_forum?.addEventListener('submit', (e) => ck_submit_handler(e, answer_editor))
// question_forum?.addEventListener('submit', (e) => ck_submit_handler(e, question_editor))

// const ck_submit_handler = (e, editor) => {
//     //parse the content of text question_editor to html for filtering and picking the image urls
//     const data = editor.getData()
//     const images = Image_picker(data)
//     const filter = Filter_length(data)
//     if (typeof images === 'object' && images) {
//         Inject_images(images, 'images', e.target)
//     }
//     if (typeof images == 'string') {
//         e.preventDefault()
//         popup.addPopup(images)
//         return false
//     }
//     if (typeof filter == 'string') {
//         e.preventDefault()
//         popup.addPopup(filter)
//         return false
//     }
// }

// //like dislike

// like_buttons.forEach(element => {
//     element?.addEventListener('click', (e) => {

//         const post_id = e.target.getAttribute('data-post-id')
//         const type = e.target.getAttribute('data-vote-type')
//         const parent = e.target.parentElement
//         const vote_counter = parent.querySelector('.vote-counter')
//         const increment_icon = parent.querySelector('.increment')
//         const dicrement_icon = parent.querySelector('.dicrement')
//         fetch(`/${post_id}/post/vote?type=${type}`, {
//             method: 'put',
//             headers: {
//                 'X-CSRF-TOKEN': window.csrf
//             }
//         }).then(res => res.ok ? res.json() : Promise.reject(res))
//             .then(data => {
//                 vote_counter.innerText = data.votes
//                 switch (data.type) {
//                     case 'increment':
//                         increment_icon.className = increment_icon.className.replace('square', 'square-fill')
//                         dicrement_icon.className = dicrement_icon.className.replace('-fill', '')
//                         break;
//                     case 'decrement':
//                         dicrement_icon.className = dicrement_icon.className.replace('square', 'square-fill')
//                         increment_icon.className = increment_icon.className.replace('-fill', '')
//                         break;
//                     case 'remove':
//                         dicrement_icon.className = dicrement_icon.className.replace('-fill', '')
//                         increment_icon.className = increment_icon.className.replace('-fill', '')
//                         break
//                     default:
//                         dicrement_icon.className = dicrement_icon.className.replace('-fill', '')
//                         increment_icon.className = increment_icon.className.replace('-fill', '')
//                         break;
//                 }
//             })
//             .catch((err) => console.log(err))
//     })
// });

// //accept answer

// accept_answer?.forEach(element => {
//     element.addEventListener('click', (e) => {
//         const post_id = e.target.getAttribute('data-post-id')
//         const answered = document.querySelector('.answered')
//         fetch(`/${post_id}/post/answer`, {
//             method: 'put',
//             headers: {
//                 'X-CSRF-TOKEN': window.csrf
//             }
//         }).then(res => {
//             if (res.ok) {
//                 if (e.target.isSameNode(answered) && e.target.classList.contains('answered')) {
//                     answered?.classList.remove('text-success')
//                     answered?.classList.remove('answered')
//                 } else {
//                     answered?.classList.remove('text-success')
//                     answered?.classList.remove('answered')
//                     e.target.classList.add('text-success')
//                     e.target.classList.add('answered')
//                 }

//             } else {
//                 Promise.reject(res)
//             }
//         })
//             .catch(err => console.log(err))
//     })
// })

