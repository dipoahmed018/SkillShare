<div class="modal fade" id="create-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="moda-type" class="modal-title">create comment</h5>
                <button type="button" class="close-comment-modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="forum-group" action="/post/comment/create" id="create-comment-form" method="post">
                    <textarea name="content" id="comment-content"></textarea>
                    <div class="comment-error error-box">

                    </div>
                    <input class="btn btn-success" class="close-comment-modal" type="submit" value="comment">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
</script>
