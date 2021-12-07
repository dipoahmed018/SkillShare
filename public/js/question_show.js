/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/dayjs/dayjs.min.js":
/*!*****************************************!*\
  !*** ./node_modules/dayjs/dayjs.min.js ***!
  \*****************************************/
/***/ (function(module) {

!function(t,e){ true?module.exports=e():0}(this,(function(){"use strict";var t=1e3,e=6e4,n=36e5,r="millisecond",i="second",s="minute",u="hour",a="day",o="week",f="month",h="quarter",c="year",d="date",$="Invalid Date",l=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,y=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,M={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_")},m=function(t,e,n){var r=String(t);return!r||r.length>=e?t:""+Array(e+1-r.length).join(n)+t},g={s:m,z:function(t){var e=-t.utcOffset(),n=Math.abs(e),r=Math.floor(n/60),i=n%60;return(e<=0?"+":"-")+m(r,2,"0")+":"+m(i,2,"0")},m:function t(e,n){if(e.date()<n.date())return-t(n,e);var r=12*(n.year()-e.year())+(n.month()-e.month()),i=e.clone().add(r,f),s=n-i<0,u=e.clone().add(r+(s?-1:1),f);return+(-(r+(n-i)/(s?i-u:u-i))||0)},a:function(t){return t<0?Math.ceil(t)||0:Math.floor(t)},p:function(t){return{M:f,y:c,w:o,d:a,D:d,h:u,m:s,s:i,ms:r,Q:h}[t]||String(t||"").toLowerCase().replace(/s$/,"")},u:function(t){return void 0===t}},D="en",v={};v[D]=M;var p=function(t){return t instanceof _},S=function(t,e,n){var r;if(!t)return D;if("string"==typeof t)v[t]&&(r=t),e&&(v[t]=e,r=t);else{var i=t.name;v[i]=t,r=i}return!n&&r&&(D=r),r||!n&&D},w=function(t,e){if(p(t))return t.clone();var n="object"==typeof e?e:{};return n.date=t,n.args=arguments,new _(n)},O=g;O.l=S,O.i=p,O.w=function(t,e){return w(t,{locale:e.$L,utc:e.$u,x:e.$x,$offset:e.$offset})};var _=function(){function M(t){this.$L=S(t.locale,null,!0),this.parse(t)}var m=M.prototype;return m.parse=function(t){this.$d=function(t){var e=t.date,n=t.utc;if(null===e)return new Date(NaN);if(O.u(e))return new Date;if(e instanceof Date)return new Date(e);if("string"==typeof e&&!/Z$/i.test(e)){var r=e.match(l);if(r){var i=r[2]-1||0,s=(r[7]||"0").substring(0,3);return n?new Date(Date.UTC(r[1],i,r[3]||1,r[4]||0,r[5]||0,r[6]||0,s)):new Date(r[1],i,r[3]||1,r[4]||0,r[5]||0,r[6]||0,s)}}return new Date(e)}(t),this.$x=t.x||{},this.init()},m.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},m.$utils=function(){return O},m.isValid=function(){return!(this.$d.toString()===$)},m.isSame=function(t,e){var n=w(t);return this.startOf(e)<=n&&n<=this.endOf(e)},m.isAfter=function(t,e){return w(t)<this.startOf(e)},m.isBefore=function(t,e){return this.endOf(e)<w(t)},m.$g=function(t,e,n){return O.u(t)?this[e]:this.set(n,t)},m.unix=function(){return Math.floor(this.valueOf()/1e3)},m.valueOf=function(){return this.$d.getTime()},m.startOf=function(t,e){var n=this,r=!!O.u(e)||e,h=O.p(t),$=function(t,e){var i=O.w(n.$u?Date.UTC(n.$y,e,t):new Date(n.$y,e,t),n);return r?i:i.endOf(a)},l=function(t,e){return O.w(n.toDate()[t].apply(n.toDate("s"),(r?[0,0,0,0]:[23,59,59,999]).slice(e)),n)},y=this.$W,M=this.$M,m=this.$D,g="set"+(this.$u?"UTC":"");switch(h){case c:return r?$(1,0):$(31,11);case f:return r?$(1,M):$(0,M+1);case o:var D=this.$locale().weekStart||0,v=(y<D?y+7:y)-D;return $(r?m-v:m+(6-v),M);case a:case d:return l(g+"Hours",0);case u:return l(g+"Minutes",1);case s:return l(g+"Seconds",2);case i:return l(g+"Milliseconds",3);default:return this.clone()}},m.endOf=function(t){return this.startOf(t,!1)},m.$set=function(t,e){var n,o=O.p(t),h="set"+(this.$u?"UTC":""),$=(n={},n[a]=h+"Date",n[d]=h+"Date",n[f]=h+"Month",n[c]=h+"FullYear",n[u]=h+"Hours",n[s]=h+"Minutes",n[i]=h+"Seconds",n[r]=h+"Milliseconds",n)[o],l=o===a?this.$D+(e-this.$W):e;if(o===f||o===c){var y=this.clone().set(d,1);y.$d[$](l),y.init(),this.$d=y.set(d,Math.min(this.$D,y.daysInMonth())).$d}else $&&this.$d[$](l);return this.init(),this},m.set=function(t,e){return this.clone().$set(t,e)},m.get=function(t){return this[O.p(t)]()},m.add=function(r,h){var d,$=this;r=Number(r);var l=O.p(h),y=function(t){var e=w($);return O.w(e.date(e.date()+Math.round(t*r)),$)};if(l===f)return this.set(f,this.$M+r);if(l===c)return this.set(c,this.$y+r);if(l===a)return y(1);if(l===o)return y(7);var M=(d={},d[s]=e,d[u]=n,d[i]=t,d)[l]||1,m=this.$d.getTime()+r*M;return O.w(m,this)},m.subtract=function(t,e){return this.add(-1*t,e)},m.format=function(t){var e=this,n=this.$locale();if(!this.isValid())return n.invalidDate||$;var r=t||"YYYY-MM-DDTHH:mm:ssZ",i=O.z(this),s=this.$H,u=this.$m,a=this.$M,o=n.weekdays,f=n.months,h=function(t,n,i,s){return t&&(t[n]||t(e,r))||i[n].substr(0,s)},c=function(t){return O.s(s%12||12,t,"0")},d=n.meridiem||function(t,e,n){var r=t<12?"AM":"PM";return n?r.toLowerCase():r},l={YY:String(this.$y).slice(-2),YYYY:this.$y,M:a+1,MM:O.s(a+1,2,"0"),MMM:h(n.monthsShort,a,f,3),MMMM:h(f,a),D:this.$D,DD:O.s(this.$D,2,"0"),d:String(this.$W),dd:h(n.weekdaysMin,this.$W,o,2),ddd:h(n.weekdaysShort,this.$W,o,3),dddd:o[this.$W],H:String(s),HH:O.s(s,2,"0"),h:c(1),hh:c(2),a:d(s,u,!0),A:d(s,u,!1),m:String(u),mm:O.s(u,2,"0"),s:String(this.$s),ss:O.s(this.$s,2,"0"),SSS:O.s(this.$ms,3,"0"),Z:i};return r.replace(y,(function(t,e){return e||l[t]||i.replace(":","")}))},m.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},m.diff=function(r,d,$){var l,y=O.p(d),M=w(r),m=(M.utcOffset()-this.utcOffset())*e,g=this-M,D=O.m(this,M);return D=(l={},l[c]=D/12,l[f]=D,l[h]=D/3,l[o]=(g-m)/6048e5,l[a]=(g-m)/864e5,l[u]=g/n,l[s]=g/e,l[i]=g/t,l)[y]||g,$?D:O.a(D)},m.daysInMonth=function(){return this.endOf(f).$D},m.$locale=function(){return v[this.$L]},m.locale=function(t,e){if(!t)return this.$L;var n=this.clone(),r=S(t,e,!0);return r&&(n.$L=r),n},m.clone=function(){return O.w(this.$d,this)},m.toDate=function(){return new Date(this.valueOf())},m.toJSON=function(){return this.isValid()?this.toISOString():null},m.toISOString=function(){return this.$d.toISOString()},m.toString=function(){return this.$d.toUTCString()},M}(),b=_.prototype;return w.prototype=b,[["$ms",r],["$s",i],["$m",s],["$H",u],["$W",a],["$M",f],["$y",c],["$D",d]].forEach((function(t){b[t[1]]=function(e){return this.$g(e,t[0],t[1])}})),w.extend=function(t,e){return t.$i||(t(e,_,w),t.$i=!0),w},w.locale=S,w.isDayjs=p,w.unix=function(t){return w(1e3*t)},w.en=v[D],w.Ls=v,w.p={},w}));

/***/ }),

/***/ "./node_modules/dayjs/plugin/relativeTime.js":
/*!***************************************************!*\
  !*** ./node_modules/dayjs/plugin/relativeTime.js ***!
  \***************************************************/
/***/ (function(module) {

!function(r,e){ true?module.exports=e():0}(this,(function(){"use strict";return function(r,e,t){r=r||{};var n=e.prototype,o={future:"in %s",past:"%s ago",s:"a few seconds",m:"a minute",mm:"%d minutes",h:"an hour",hh:"%d hours",d:"a day",dd:"%d days",M:"a month",MM:"%d months",y:"a year",yy:"%d years"};function i(r,e,t,o){return n.fromToBase(r,e,t,o)}t.en.relativeTime=o,n.fromToBase=function(e,n,i,d,u){for(var f,a,s,l=i.$locale().relativeTime||o,h=r.thresholds||[{l:"s",r:44,d:"second"},{l:"m",r:89},{l:"mm",r:44,d:"minute"},{l:"h",r:89},{l:"hh",r:21,d:"hour"},{l:"d",r:35},{l:"dd",r:25,d:"day"},{l:"M",r:45},{l:"MM",r:10,d:"month"},{l:"y",r:17},{l:"yy",d:"year"}],m=h.length,c=0;c<m;c+=1){var y=h[c];y.d&&(f=d?t(e).diff(i,y.d,!0):i.diff(e,y.d,!0));var p=(r.rounding||Math.round)(Math.abs(f));if(s=f>0,p<=y.r||!y.r){p<=1&&c>0&&(y=h[c-1]);var v=l[y.l];u&&(p=u(""+p)),a="string"==typeof v?v.replace("%d",p):v(p,n,y.l,s);break}}if(n)return a;var M=s?l.future:l.past;return"function"==typeof M?M(a):M.replace("%s",a)},n.to=function(r,e){return i(r,e,this,!0)},n.from=function(r,e){return i(r,e,this)};var d=function(r){return r.$u?t.utc():t()};n.toNow=function(r){return this.to(d(this),r)},n.fromNow=function(r){return this.from(d(this),r)}}}));

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
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
/*!**************************************************!*\
  !*** ./resources/js/pagescript/question_show.js ***!
  \**************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var dayjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! dayjs */ "./node_modules/dayjs/dayjs.min.js");
/* harmony import */ var dayjs__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(dayjs__WEBPACK_IMPORTED_MODULE_0__);
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }



var reletiveTime = __webpack_require__(/*! dayjs/plugin/relativeTime */ "./node_modules/dayjs/plugin/relativeTime.js");

dayjs__WEBPACK_IMPORTED_MODULE_0___default().extend(reletiveTime);
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
  element.addEventListener('click', scrollToCommentCreateForm);
});

function scrollToCommentCreateForm(e) {
  var reference_id = e.target.getAttribute('data-reference-id');
  var reference_name = e.target.getAttribute('data-reference-name');
  comment_creator_form.setAttribute('data-references', JSON.stringify([reference_id]));
  console.log(comment_input);
  comment_input.placeholder = "Reply to ".concat(reference_name);
  comment_creator_form.scrollIntoView();
  comment_input.focus();
} //comment creator


var comment_creator_btn = document.querySelector('.comment-create-btn');
comment_creator_btn.addEventListener('click', function () {
  comment_creator_form.setAttribute('data-references', null);
  comment_input.placeholder = 'Type your comment here';
  comment_input.focus();
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
    return makeCommentHTML(comment, document.querySelector('.comments'));
  })["catch"](function (err) {
    return console.log(err);
  });
}); //load more comment

var load_more_btn = _toConsumableArray(document.getElementsByClassName('load-more'));

load_more_btn.forEach(function (element) {
  element.addEventListener('click', function (e) {
    var post_id = e.target.getAttribute('data-post-id');
    var offset = e.target.getAttribute('data-offset');
    fetch("/comments/index/".concat(post_id, "?offset=").concat(offset !== null && offset !== void 0 ? offset : 0), {
      method: 'get',
      headers: {
        'X-CSRF-TOKEN': window.csrf,
        'Accept': 'application/json'
      }
    }).then(function (res) {
      return res.ok ? res.json() : Promise.reject(res.json());
    }).then(function (data) {
      if (!data.has_more) {
        e.target.remove();
      }

      data.comments.forEach(function (comment) {
        makeCommentHTML(comment, document.querySelector('.comments'));
      });
      e.target.setAttribute('data-offset', parseInt(offset) + 5);
    })["catch"](function (err) {
      return console.log(err);
    });
  });
}); //edit comment
//show comment editor

var comment_edit_btns = _toConsumableArray(document.getElementsByClassName('comment-editor-show'));

comment_edit_btns.forEach(function (element) {
  element.addEventListener('click', showCommentEditForm);
});

function showCommentEditForm(e) {
  var comment_id = e.target.getAttribute('data-comment-id');
  var comment_card = document.getElementById("comment-".concat(comment_id));
  var edit_form = document.getElementById("comment-edit-".concat(comment_id));
  edit_form.classList.remove('hide');
  comment_card.querySelector('.content-wrapper').classList.add('hide');
} //hide comment editor


var comment_edit_hide_btns = _toConsumableArray(document.getElementsByClassName('comment-form-cancel'));

comment_edit_hide_btns.forEach(function (element) {
  element.addEventListener('click', hideCommentEditForm);
});

function hideCommentEditForm(e) {
  var edit_form = e.target.parentElement;
  var comment_id = edit_form.getAttribute('data-comment-id');
  var comment_card = document.getElementById("comment-".concat(comment_id));
  edit_form.classList.add('hide');
  comment_card.querySelector('.content-wrapper').classList.remove('hide');
} //templates 


function makeCommentHTML(comment) {
  var _comment$reference_us;

  var append_to = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var references = '';
  var profile = '';
  var comment_editor = '';
  var comment_editor_btn = '';
  var comment_deleter_btn = ''; //making refernce useres html

  (_comment$reference_us = comment.reference_users) === null || _comment$reference_us === void 0 ? void 0 : _comment$reference_us.forEach(function (user) {
    references = "<a href=\"".concat(user.id, "/profile\">@").concat(user.name, "</a>") + references;
  }); //add owner profile box

  if (comment.owner_details.profile_picture) {
    profile = "<img class=\"profile-image image\" src=\"".concat(comment.owner_details.profile_picture.file_link, " alt=\"avatar\">");
  } else {
    profile = "<div class=\"profile-text\">".concat(comment.owner_details.name.charAt(0), "</div>");
  } //add comment editor and deleter


  if (user.id !== comment.owner_details.id) {
    //editor
    comment_editor = "<form class=\"comment-form comment-edit hide\"\n         id=\"comment-edit-".concat(comment.id, "\" data-comment-id=").concat(comment.id, "\n          action=\"/comment/create\" method=\"POST\">\n            <input type=\"text\" name=\"content\" placeholder=\"Type your comment here\"\n             value=\"").concat(comment.content, "\" minlength=\"5\" maxlength=\"2000\" required>\n            <button class=\"comment-submit\" type=\"submit\" title=\"save changes\"></button>\n            <i class=\"bi bi-x-lg comment-form-cancel\"></i>\n        </form>");
    comment_editor_btn = "<span class=\"comment-editor-show\" data-comment-id=".concat(comment.id, " style=\"cursor: pointer\"\">Edit</span>"); //deteler

    comment_deleter_btn = "<span class=\"comment-delete\" style=\"cursor: pointer\" data-comment-id=\"".concat(comment.id, "\">Delete</span>");
  }

  var comment_card = "<div class=\"comment-card\" id=\"comment-".concat(comment.id, "\">\n            <div class=\"comment-content\">\n                <a class=\"owner-details\" href=\"/user/").concat(comment.owner_details.id, "/profile\">             \n                    ").concat(profile, "\n                </a>\n                <div class=\"content-wrapper\">\n                    <div class=\"references\">\n                        ").concat(references, "\n                    </div>\n                    <div class=\"content\">\n                        ").concat(comment.content, "\n                    </div>\n                </div>\n                ").concat(comment_editor, "\n            </div>\n            <div class=\"comment-control\">\n                    ").concat(comment_deleter_btn, "\n                    ").concat(comment_editor_btn, "\n                    <span class=\"reply-creator-show\" data-comment-id=\"").concat(comment.id, "\"\n                    data-reference-id=").concat(comment.owner, " data-reference-name=\"").concat(comment.owner_details.name, "\"\n                    style=\"cursor: pointer\">reply</span>\n                    <span class=\"created-at\">").concat(dayjs__WEBPACK_IMPORTED_MODULE_0___default()(comment.created_at).fromNow(), "</span>\n            </div>\n        </div>");

  if (append_to) {
    append_to.insertAdjacentHTML('beforeend', comment_card); //adding the event listeners

    var comment_dom_card = document.getElementById("comment-".concat(comment.id)),
        comment_reply_shower = comment_dom_card.querySelector('.reply-creator-show'),
        commnet_editor_shower = comment_dom_card.querySelector('.comment-editor-show'),
        coment_deleter = comment_dom_card.querySelector('.coment-delete'),
        comment_editor_form = comment_dom_card.querySelector('.comment-edit'),
        comment_form_cancel = comment_dom_card.querySelector('.comment-form-cancel'),
        comment_form_submit = comment_dom_card.querySelector('.comment-submit');
    comment_reply_shower.addEventListener('click', scrollToCommentCreateForm); // console.log(comment_html)
    // comment_html.

    return true;
  }

  return comment_card;
}
})();

/******/ })()
;