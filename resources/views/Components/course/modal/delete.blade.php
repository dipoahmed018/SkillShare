@props(['course'])

<div class="modal fade" id="course-delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Course</h5>
            </div>
            <div class="modal-body">
                <div class="yes-no-confirm">
                    <p>Are you sure you want to delete this course</p>
                    <div class="butttons">
                        <button>No</button>
                        <form class="tool" action="{{ route('delete.course', ['course' => $course->id]) }}"
                            method="post" id="course-delete">
                            @method('delete')
                            @csrf
                            <button type="submit">
                                Yes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>