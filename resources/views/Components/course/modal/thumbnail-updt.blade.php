@props(['course'])

<div class="modal fade" id="thumbnail-update-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Thumbnail</h5>
            </div>
            <div class="modal-body">
                <form id="thumbnail-update-form" action="{{ route('update.course.thumbnail', ['course' => $course->id]) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <input class="tuhmbnail-update" style="visibility: hidden; width:0px; height:0px" accept=".jpg, .jpeg, .png" type="file" name="thumbnail" id="thumbnail">
                    <label style="max-width: 100%; min-width: 50%" class="btn btn-primary form-control" for="thumbnail">Update thumbnail</label><br>
                    <div class="error-box">
                        @if ($errors->has('thumbnail'))
                            <p>{{$errors->first('thumbnail')}}</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>