<div class="modal fade" id="create-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="moda-type" class="modal-title">create comment</h5>
                <button data-bs-dismiss="modal" type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forum-group" action="post/create/comment" id="create-comment-form" method="post">
                    @csrf
                    <textarea name="content" id="comment-content"></textarea>
                    <div class="comment-error error-box">

                    </div>
                    <input class="btn btn-success" type="submit" value="comment">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const comment_edit_box = document.getElementById('comment-content')
    const content = document.getElementById('comment-content');
    const error = document.querySelector('.comment-error');
    const comment_form = document.getElementById('create-comment-form')
    let comment_editor;
    //button to open the modal button need some data attribute about the post or comment can be opened form any component
    const comment_modal = document.getElementById('create-comment-modal')
    comment_modal.addEventListener('show.bs.modal', (e) => {
       let commentable_id = e.relatedTarget.getAttribute('data-commentable-id')
       let comment_type = e.relatedTarget.getAttribute('data-comment-type')
       comment_form.action = window.location.hostname + `/${commentable_id}/${comment_type}/create`
    })
    comment_form.addEventListener('submit', (e) => {
        if (comment_editor) {
            const data = comment_editor.getData()
            if (data.length < 1) {
                e.preventDefault()
                error.innerHTML = 'content must not be empty'
                return;
            }
            const parser = new DOMParser().parseFromString(data, 'text/html')
            const images = parser.querySelectorAll('img')
            if (images.length > 3) {
                e.preventDefault()
                error.innerHTML = 'you can not use more then 3 image'
            }
            if (editor.getData().length > 1000) {
                e.preventDefault()
                error.innerHTML = 'too much content'
                return false;
            }
            if (images.length > 0) {
                let srclist = document.createElement('input')
                let sources = {}
                srclist.type = 'hidden'
                srclist.name = 'images'
                images.forEach(element => {
                    const {
                        src
                    } = element
                    sources[Object.keys(sources).length + 1] = src
                })
                sources = JSON.stringify(sources)
                srclist.value = sources
                e.target.prepend(srclist)
            }
        }
    })
    document.addEventListener('DOMContentLoaded', () => {

        ClassicEditor.create(comment_edit_box, {
                toolbar: ['undo', 'redo', '|', 'bold', 'italic',
                    'blockQuote', '|', 'ImageUpload'
                ],
                simpleUpload: {
                    uploadUrl: `https://skillshare.com/save/comment/image`,
                    withCredentials: true,
                    headers: {
                        'X-CSRF-TOKEN': window.csrf,
                    }
                }
            }).then(ckeditor => {
                comment_editor = ckeditor
            })
            .catch(error => console.log(error))
    })
</script>
