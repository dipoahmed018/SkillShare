<div id="tamplate-comment-wrapper" class="tamplate-comment row hide">
    <div class="col col-1 comment-owner">
        owner
    </div>
    <div class="content col col-11">
        content
    </div>
    <div class="control d-flex col-12 ">
        <div data-commentable-id="0" class="like-comment" data-method='increment'>
            <i class="bi bi-hand-thumbs-up"></i>
            <span>0</span>
        </div>
        <button class="show-comment-creator" data-bs-toggle='modal' data-bs-target='#create-comment-modal'
            data-commentable-id="0" data-comment-type="reply">reply</button>
        <button class="show-replies">show replies</button>
        
        <button class="btn btn-warning edit-comment" data-bs-action="update" data-comment-id="0">edit</button>
        <button data-comment-id='0' class="btn btn-danger delete-comment">delete</button>

    </div>
    <div id="comment-box" class="reply-box m-5">
    </div>
</div>
