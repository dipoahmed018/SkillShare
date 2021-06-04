/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/asset/ChunkUpload.js":
/*!*******************************************!*\
  !*** ./resources/js/asset/ChunkUpload.js ***!
  \*******************************************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/babel-loader/lib/index.js):\nSyntaxError: /home/dipo/Desktop/Projects/Laravel/LearningApp/resources/js/asset/ChunkUpload.js: 'return' outside of function. (5:8)\n\n\u001b[0m \u001b[90m 3 |\u001b[39m     \u001b[36mlet\u001b[39m res\u001b[33m;\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 4 |\u001b[39m     \u001b[36mif\u001b[39m (\u001b[33m!\u001b[39mfile \u001b[33m||\u001b[39m \u001b[33m!\u001b[39murl) {\u001b[0m\n\u001b[0m\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m 5 |\u001b[39m         \u001b[36mreturn\u001b[39m {\u001b[0m\n\u001b[0m \u001b[90m   |\u001b[39m         \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 6 |\u001b[39m             status\u001b[33m:\u001b[39m \u001b[32m'failed'\u001b[39m\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 7 |\u001b[39m             error\u001b[33m:\u001b[39m \u001b[32m\"please provide a file and a url\"\u001b[39m\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 8 |\u001b[39m         }\u001b[33m;\u001b[39m\u001b[0m\n    at Parser._raise (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:810:17)\n    at Parser.raiseWithData (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:803:17)\n    at Parser.raise (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:764:17)\n    at Parser.parseReturnStatement (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:12921:12)\n    at Parser.parseStatementContent (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:12599:21)\n    at Parser.parseStatement (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:12551:17)\n    at Parser.parseBlockOrModuleBlockBody (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:13140:25)\n    at Parser.parseBlockBody (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:13131:10)\n    at Parser.parseBlock (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:13115:10)\n    at Parser.parseStatementContent (/home/dipo/Desktop/Projects/Laravel/LearningApp/node_modules/@babel/parser/lib/index.js:12627:21)");

/***/ }),

/***/ "./resources/js/asset/LaravelErrorParser.js":
/*!**************************************************!*\
  !*** ./resources/js/asset/LaravelErrorParser.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ErrorHandler = /*#__PURE__*/function () {
  function ErrorHandler() {
    var popup = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

    _classCallCheck(this, ErrorHandler);

    this.popup = popup;
  }

  _createClass(ErrorHandler, [{
    key: "simpleError",
    value: function simpleError(message) {
      var box = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      var pop = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

      if (box == null && pop == false) {
        return;
      }

      if (box == null && pop == true && this.popup) {
        this.popup.addPopup(message);
        return;
      }

      var input_box = document.getElementById(box);

      if (!input_box) {
        console.log(new DOMException("".concat(box, " not found")));
        return;
      }

      if (input_box && message) {
        this.setInputMessageHtml(message, input_box);
        return;
      }
    }
  }, {
    key: "inputHandle",
    value: function inputHandle(error) {
      var error_box = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      var pop = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

      if (this.popup && error.message && pop == true) {
        this.popup.addPopup(error.message);
      }

      if (!error.errors) {
        return;
      }

      if (!error_box || Object.keys(error_box).length < 1) {
        var errors = error.errors;

        for (var name in errors) {
          var box = document.getElementById(name);

          if (box) {
            this.setInputMessageHtml(errors[name], box);
          } else if (pop == true && this.popup) {
            this.popup.addPopup(errors[name]);
          }
        }

        return;
      }

      if (error_box && Object.keys(error_box).length > 0) {
        var _errors = error.errors;

        for (var name in _errors) {
          if (error_box[name]) {
            var _box = document.getElementById(error_box[name]);

            var res_box = document.getElementById(name);

            if (_box) {
              this.setInputMessageHtml(_errors[name], _box);
            } else if (res_box) {
              this.setInputMessageHtml(_errors[name], res_box);
            } else if (pop == true && this.popup) {
              this.popup.addPopup(_errors[name]);
            }
          }
        }

        return;
      }
    }
  }, {
    key: "setInputMessageHtml",
    value: function setInputMessageHtml(message, append_to) {
      var _this = this;

      var element_type = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'p';
      var error_box = document.createElement('div');
      error_box.setAttribute('class', 'error-box');
      var message_element = document.createElement(element_type);
      var close_element = document.createElement('i');
      close_element.setAttribute('class', 'bi bi-x-lg');
      message_element.innerText = message;
      error_box.append(close_element);
      error_box.append(message_element);
      append_to.append(error_box);
      close_element.addEventListener('click', function () {
        return _this.remove_element(message_element, close_element);
      });
    }
  }, {
    key: "remove_element",
    value: function remove_element(sibling, self) {
      sibling.remove();
      self.remove();
    }
  }]);

  return ErrorHandler;
}();

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ErrorHandler);

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

      var i = 0;

      this._popup_queue();
    }
  }, {
    key: "_popup_queue",
    value: function _popup_queue() {
      var next_popup;

      if (this._popups.length < 1) {
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
/*!************************************************!*\
  !*** ./resources/js/pagescript/course-show.js ***!
  \************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _asset_LaravelErrorParser__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../asset/LaravelErrorParser */ "./resources/js/asset/LaravelErrorParser.js");
/* harmony import */ var _asset_ChunkUpload__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../asset/ChunkUpload */ "./resources/js/asset/ChunkUpload.js");
/* harmony import */ var _asset_PopupHandler__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../asset/PopupHandler */ "./resources/js/asset/PopupHandler.js");


 //tutorial upload

var popup = new _asset_PopupHandler__WEBPACK_IMPORTED_MODULE_2__.default();
var error = new _asset_LaravelErrorParser__WEBPACK_IMPORTED_MODULE_0__.default(popup);
var tutorial_input_element = document.getElementById("tutorial");

if (tutorial_input_element) {
  tutorial_input_element.addEventListener("change", function (e) {
    var file = e.target.files[0];
    var data = {
      tutorial_name: (Date.now() + Math.random()).toString(36),
      tutorial_type: file.type
    };
    document.getElementById('tutorial-upload-box').classList.add('hide');
    (0,_asset_ChunkUpload__WEBPACK_IMPORTED_MODULE_1__.default)(file, "/course/".concat(course.id, "/addvideo"), function (total) {
      return progress_bar(total, 'tutorial_progress_box');
    }, 'tutorial_up_cancel', data).then(function (res) {
      document.getElementById('tutorial-upload-box').classList.remove('hide');

      if (res.status == 422) {
        error.inputHandle(res.error, null, true);
        return;
      }

      if (res.status !== 'success') {
        error.simpleError(res.error.message, null, true);
        return;
      }

      if (res.status == 'success') {
        popup.addPopup('introduction upload compleate');
        console.log(res);
      }
    });
  });
} //introduction upload


var introduction_input_lement = document.getElementById('introduction');

if (introduction_input_lement) {
  introduction_input_lement.addEventListener("change", function (e) {
    var file = e.target.files[0];
    var url = "/update/course/".concat(course.id, "/introduction");
    var data = {
      introduction_name: (Date.now() + Math.random()).toString(36),
      introduction_type: file.type
    };
    document.getElementById('introduction-upload-box').classList.add('hide');
    (0,_asset_ChunkUpload__WEBPACK_IMPORTED_MODULE_1__.default)(file, url, function (total) {
      return progress_bar(total, 'introduction_progress_box');
    }, 'introduction_up_cancel', data).then(function (res) {
      console.log(res);
      document.getElementById('introduction-upload-box').classList.remove('hide');

      if (res.status == 422) {
        error.inputHandle(res.error, null, true);
        return;
      }

      if (res.status !== 'success') {
        error.simpleError(res.error.message, null, true);
        return;
      }

      if (res.status == 'success') {
        popup.addPopup('introduction upload compleate');
        console.log(res);
      }
    });
  });
} //chunk upload progress bar 


var progress_fix = 0;

var progress_bar = function progress_bar(total, element) {
  var parent_element = document.getElementById(element);
  var progress_element;
  parent_element.childNodes.forEach(function (node) {
    if (node.className == 'progress') {
      progress_element = node.childNodes.item(1);
    }
  });

  if (parent_element) {
    if (total >= 100) {
      if (parent_element) {
        parent_element.classList.add('hide');
      }

      return;
    }

    if (total > progress_fix) {
      progress_fix = total;

      if (parent_element) {
        parent_element.classList.remove('hide');
      }

      progress_element.innerHTML = "".concat(progress_fix, " %");
      progress_element.setAttribute('aria-valuenow', progress_fix);
      progress_element.setAttribute('style', "width:".concat(progress_fix, "%"));
    }
  }
};
})();

/******/ })()
;