/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************!*\
  !*** ./resources/js/pagescript/forum_edit.js ***!
  \***********************************************/
var editor;
var edit_box = document.querySelector('#description');

if (edit_box) {
  ClassicEditor.create(edit_box, {
    toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|']
  }).then(function (ckeditor) {
    editor = ckeditor;
    editor.setData(forum_details.description);
  })["catch"](function (error) {
    return console.log(error);
  });
  var forum_submit = document.getElementById('forum');
}
/******/ })()
;