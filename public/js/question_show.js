/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************************!*\
  !*** ./resources/js/pagescript/question_show.js ***!
  \**************************************************/
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

// require('../component/comment/edi')
var answer_input_box = document.getElementById('answer-input');
var answer_editor;

if (answer_input_box) {
  ClassicEditor.create(answer_input_box, {
    toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'image'],
    simpleUpload: {
      uploadUrl: "/save/image",
      withCredentials: true,
      headers: {
        'X-CSRF-TOKEN': window.csrf
      }
    }
  }).then(function (ckeditor) {
    return answer_editor = ckeditor;
  })["catch"](function (error) {
    return console.log(error);
  });
} // vote control


var vote_buttons = _toConsumableArray(document.getElementsByClassName('vote-control'));

var arrow_up = "bi bi-arrow-up-square",
    arrow_down = "bi bi-arrow-down-square",
    arrow_up_fill = "bi bi-arrow-up-square-fill",
    arrow_down_fill = "bi bi-arrow-down-square-fill";
vote_buttons.forEach(function (element) {
  element.addEventListener('click', function (e) {
    var vote_type = e.target.getAttribute('data-vote-type');
    var post_id = e.target.getAttribute('data-post-id');
    vote_control(e.target.parentElement, vote_type, post_id);
  });
});

function vote_control(vote_box, vote_type, post_id) {
  var _ref = _toConsumableArray(vote_box.getElementsByClassName('vote-control')),
      increment_btn = _ref[0],
      decrement_btn = _ref[1];

  var vote_count_box = vote_box.querySelector('.vote-count');
  var vote_count = parseInt(vote_count_box.innerText); //update vote

  fetch("/".concat(post_id, "/post/vote"), {
    method: 'put',
    body: JSON.stringify({
      'type': vote_type
    }),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': window.csrf
    }
  }).then(function (res) {
    return res.ok ? res.json() : Promise.reject(res.json());
  }).then(function (data) {
    var _data$vote;

    switch (data === null || data === void 0 ? void 0 : (_data$vote = data.vote) === null || _data$vote === void 0 ? void 0 : _data$vote.vote_type) {
      case 'increment':
        increment_btn.className = increment_btn.className.replace(arrow_up, arrow_up_fill);
        decrement_btn.className = decrement_btn.className.replace(arrow_down_fill, arrow_down);
        break;

      case 'decrement':
        decrement_btn.className = decrement_btn.className.replace(arrow_down, arrow_down_fill);
        increment_btn.className = increment_btn.className.replace(arrow_up_fill, arrow_up);
        break;

      case undefined:
        increment_btn.className = increment_btn.className.replace(arrow_up_fill, arrow_up);
        decrement_btn.className = decrement_btn.className.replace(arrow_down_fill, arrow_down);
        break;

      default:
        break;
    }

    vote_count_box.innerText = data === null || data === void 0 ? void 0 : data.vote_count;
  })["catch"](function (err) {
    return console.log(err);
  });
} //overriding the default befavior of comment creation from comment component


var reply_form_show_btns = _toConsumableArray(document.getElementsByClassName('reply-creator-show'));

var comment_creator_form = document.getElementById("comment-create-".concat(question.id)),
    comment_input = comment_creator_form.querySelector('[name="content"]'); //reply creator

reply_form_show_btns === null || reply_form_show_btns === void 0 ? void 0 : reply_form_show_btns.forEach(function (element) {
  element.addEventListener('click', function (e) {
    var reference_id = e.target.getAttribute('data-reference-id');
    var reference_name = e.target.getAttribute('data-reference-name');
    comment_creator_form.setAttribute('data-references', JSON.stringify([reference_id]));
    console.log(comment_input);
    comment_input.placeholder = "Reply to ".concat(reference_name);
    comment_creator_form.scrollIntoView();
    comment_input.focus();
  });
}); //comment creator

var comment_creator_btn = document.querySelector('.comment-create-btn');
comment_creator_btn.addEventListener('click', function () {
  comment_creator_form.setAttribute('data-references', null);
  comment_input.placeholder = 'Type your comment here';
});
comment_creator_form.addEventListener('submit', function (e) {
  var _question;

  e.preventDefault();
  var references = e.target.getAttribute('data-references');
  var form_data = {
    'commentable': (_question = question) === null || _question === void 0 ? void 0 : _question.id,
    'type': 'parent',
    'content': comment_input.value
  };

  if (references) {
    form_data['references'] = JSON.parse(references);
  }

  fetch('/comment/create', {
    method: 'post',
    body: JSON.stringify(form_data),
    headers: {
      'X-CSRF-TOKEN': window.csrf,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  }).then(function (res) {
    return res.ok ? res.json() : Promise.reject(res.json());
  }).then(function (comment) {
    return console.log(comment);
  })["catch"](function (err) {
    return console.log(err);
  });
});
/******/ })()
;