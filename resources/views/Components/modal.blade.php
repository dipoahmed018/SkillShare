@props(['body', 'title' => 'Modal'])


<div class="modal fade" {{$attributes}} tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{$title}}
                </h5>
            </div>
            <div class="modal-body">
                {{$body}}
            </div>
        </div>
    </div>
</div>
