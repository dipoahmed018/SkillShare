/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/asset/CKEditorHelper.js":
/*!**********************************************!*\
  !*** ./resources/js/asset/CKEditorHelper.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Image_picker": () => (/* binding */ Image_picker),
/* harmony export */   "Filter_length": () => (/* binding */ Filter_length),
/* harmony export */   "Inject_images": () => (/* binding */ Inject_images)
/* harmony export */ });
function Image_picker(content) {
  var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 3;
  var parser = new DOMParser().parseFromString(content, 'text/html');
  var images = parser.querySelectorAll('img');

  try {
    if (images.length > limit) {
      throw new Error("You can not upload more then ".concat(limit, " image"));
    }

    if (images.length > 0) {
      var sources = [];
      images.forEach(function (element) {
        return sources.push(element.src);
      });
      return sources;
    }

    return null;
  } catch (error) {
    return error;
  }
}
function Filter_length(content) {
  var limit = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1500;

  if (content.length > 1500) {
    return new Error("question must be completed under ".concat(limit, " charecters"));
  }

  return true;
}
function Inject_images(images) {
  var name = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'images';
  var forum = arguments.length > 2 ? arguments[2] : undefined;
  var sources = JSON.stringify(images);
  var srclist = document.createElement('input');
  srclist.type = 'hidden';
  srclist.name = name;
  srclist.value = sources;

  if (forum instanceof Element || forum instanceof Document) {
    forum.prepend(srclist);
  } else if (typeof forum == 'string') {
    var forum_element = document.getElementById(forum);
    return forum_element ? forum_element.prepend(srclist) : new Error('Forum element does not exists please provide a valid id');
  }

  return true;
}

/***/ }),

/***/ "./resources/js/asset/PopupHandler.js":
/*!********************************************!*\
  !*** ./resources/js/asset/PopupHandler.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ PopupHandler)
/* harmony export */ });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var PopupHandler = /*#__PURE__*/function () {
  function PopupHandler() {
    var id = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'popup_box';

    _classCallCheck(this, PopupHandler);

    _defineProperty(this, "_popups", []);

    this.popup_box_id = id;
  }

  _createClass(PopupHandler, [{
    key: "getPopups",
    value: function getPopups() {
      return this._popups;
    }
  }, {
    key: "addPopup",
    value: function addPopup(message) {
      if (this._popups.length < 1) {
        this._popups.push({
          id: 1,
          message: message,
          running: false
        });
      } else {
        var _this$_popups$reduce = this._popups.reduce(function (prev, cur) {
          return prev.id < cur.id ? cur : prev;
        }, {
          id: 1
        }),
            id = _this$_popups$reduce.id;

        this._popups.push({
          id: id + 1,
          message: message,
          running: false
        });
      }

      this._popup_queue();
    }
  }, {
    key: "_popup_queue",
    value: function _popup_queue() {
      var next_popup;

      if (this._popups.length < 1) {
        document.getElementById(this.popup_box_id).classList.add('hide');
        return;
      } else {
        next_popup = this._popups.reduce(function (prev, cur) {
          if (prev.id < cur.id && prev.running == false) {
            return prev;
          }

          return cur;
        });
      }

      var running = [];

      this._popups.forEach(function (value) {
        if (value.running !== false) {
          running.push(value);
        }
      });

      if (running.length < 3 && next_popup.running == false) {
        this._createPopup(next_popup);
      }
    }
  }, {
    key: "_createPopup",
    value: function _createPopup(popup) {
      var _this = this;

      var popup_box = document.getElementById(this.popup_box_id) ? document.getElementById(this.popup_box_id) : document.createElement('div').setAttribute('id', this.popup_box_id);
      popup_box.classList.remove('hide');
      var popup_message_box = document.createElement('div');
      var close_popup = document.createElement('i');
      var popup_message = document.createElement('p');
      var unique_id = (Date.now() + Math.random()).toString(36);
      close_popup.setAttribute('class', 'bi bi-x-lg');
      close_popup.setAttribute('id', unique_id);
      popup_message_box.setAttribute('class', 'popup_message_box');
      popup_message.innerText = popup.message;
      popup_message_box.append(close_popup);
      popup_message_box.append(popup_message);
      popup_box.appendChild(popup_message_box); // close popup call

      this._popups.find(function (value) {
        return value.id == popup.id;
      }).running = setTimeout(function () {
        return _this._closePopup(popup.id, close_popup.parentNode);
      }, 1000 * 30);
      close_popup.addEventListener('click', function () {
        return _this._closePopup(popup.id, close_popup.parentNode, popup.running);
      });
    }
  }, {
    key: "_closePopup",
    value: function _closePopup(id, close_popup) {
      var timer = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

      var newQueue = this._popups.filter(function (value) {
        return value.id !== id;
      });

      this._popups = newQueue;
      close_popup.remove();

      this._popup_queue();
    }
  }]);

  return PopupHandler;
}();



/***/ }),

/***/ "./node_modules/bootstrap/dist/js/bootstrap.esm.js":
/*!*********************************************************!*\
  !*** ./node_modules/bootstrap/dist/js/bootstrap.esm.js ***!
  \*********************************************************/
/***/ (() => {

throw new Error("Module build failed: Error: ENOENT: no such file or directory, open 'G:\\Projects\\Larave\\SkillShare\\node_modules\\bootstrap\\dist\\js\\bootstrap.esm.js'");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!***********************************************!*\
  !*** ./resources/js/pagescript/forum_show.js ***!
  \***********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _asset_CKEditorHelper__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../asset/CKEditorHelper */ "./resources/js/asset/CKEditorHelper.js");
/* harmony import */ var bootstrap__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.esm.js");
/* harmony import */ var _asset_PopupHandler__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../asset/PopupHandler */ "./resources/js/asset/PopupHandler.js");



var popup = new _asset_PopupHandler__WEBPACK_IMPORTED_MODULE_2__.default();
var forum_contents_showers = [document.getElementById('questions'), document.getElementById('students'), document.getElementById('about')];
var forum_contents = [document.querySelector('.questions-wrapper'), document.querySelector('.students-wrapper'), document.querySelector('.about')];
forum_contents_showers === null || forum_contents_showers === void 0 ? void 0 : forum_contents_showers.forEach(function (element) {
  element === null || element === void 0 ? void 0 : element.addEventListener('click', function (e) {
    forum_contents.forEach(function (element) {
      if (element.classList.contains(e.target.getAttribute('data-event-target'))) {
        element.classList.remove('hide');
        return;
      }

      element.classList.add('hide');
    });
  });
}); //question create

var question_editor;
var question_creator_forum = document.getElementById('create-question');
var question_textarea = document.getElementById('question-input'); // const question_create = question_creator_forum.getElementsByTagName('button')

if (question_textarea) {
  ClassicEditor.create(question_textarea, {
    toolbar: ['undo', 'redo', '|', 'heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'ImageUpload'],
    simpleUpload: {
      uploadUrl: "/save/image",
      withCredentials: true,
      headers: {
        'X-CSRF-TOKEN': window.csrf
      }
    }
  }).then(function (CKeditor) {
    question_editor = CKeditor;
  })["catch"](function (err) {
    return console.log(err);
  });
}

question_creator_forum.addEventListener('submit', createQuestion);

function createQuestion(e) {
  var data = question_editor.getData();
  var images = (0,_asset_CKEditorHelper__WEBPACK_IMPORTED_MODULE_0__.Image_picker)(data, 4);
  var filter = (0,_asset_CKEditorHelper__WEBPACK_IMPORTED_MODULE_0__.Filter_length)(data);
  var image_add = (0,_asset_CKEditorHelper__WEBPACK_IMPORTED_MODULE_0__.Inject_images)(images, 'images', e.target);

  if (images instanceof Error) {
    e.preventDefault();
    return popup.addPopup(images.message);
  }

  if (filter instanceof Error) {
    e.preventDefault();
    return popup.addPopup(filter.message);
  }

  if (image_add instanceof Error) {
    e.preventDefault();
    return popup.addPopup(image_add.message);
  }
}
})();

/******/ })()
;