@props(['course'])

<div class="modal fade" id="introduction-update-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Introduction</h5>
            </div>
            <div class="modal-body">

                <x-progressBar cancelable="true"/>

                <form action="" id="introduction-update-form">
                    <input accept=".mp4, .webm, .ogv" required type="file" name="introduction" id="introduction-upload"
                    style="width: 0px; visibility:hidden;">
                    <label for="introduction-upload" class="deep-green-btn" style="margin: auto">Add Video</label>
                    <div class="error"></div>
                </form>
            </div>
        </div>
    </div>
</div>
