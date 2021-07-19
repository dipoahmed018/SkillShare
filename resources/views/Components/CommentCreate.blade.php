<div class="modal fade" id="create-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="moda-type" class="modal-title">create comment</h5>
                <button data-bs-dismiss="modal" type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forum-group"
                    action="post/create/comment"
                    id="create-comment-form" method="post">
                    @csrf
                    <textarea name="comment-content" id="comment-content"></textarea>
                    <input type="text" name="heloo" id="hello">
                    <input class="btn btn-success" type="submit" value="comment">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const comment_edit_box = document.getElementById('comment-content')

    //button to open the modal button need some data attribute about the post or comment can be opened form any component
    const comment_modal = document.getElementById('create-comment-modal')
    comment_modal.addEventListener('show.bs.modal', (e) => {
        commentable_id = e.relatedTarget.getAttribute('data-commentable-id')
        comment_type = e.relatedTarget.getAttribute('data-comment-type')
    })

    document.addEventListener('DOMContentLoaded', () => {
        ClassicEditor.create(comment_edit_box, {
                toolbar: ['undo', 'redo', '|', 'bold', 'italic',
                    'blockQuote', '|', 'ImageUpload'
                ],
                simpleUpload: {
                    uploadUrl: `https://skillshare.com/comment/image/save`,
                    withCredentials: true,
                    headers: {
                        'X-CSRF-TOKEN': window.csrf,
                    }
                }
            }).then(ckeditor => {
                editor = ckeditor
            })
            .catch(error => console.log(error))
    })
</script>
