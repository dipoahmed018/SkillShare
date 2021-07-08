<div>
    <div class="comment row">
        <div class="col col-1">
            owner
        </div>
        <div class="content col col-11">
            {{ $comment->content }}
        </div>
        <div class="control d-flex col-12 ">
            <button>L {{ $comment->vote }}</button>
            <button>reply</button>
        </div>
    </div>
</div>
