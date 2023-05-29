/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*********************************!*\
  !*** ../assets/js/gutenberg.js ***!
  \*********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const {
  registerPlugin
} = wp.plugins;
const {
  __
} = wp.i18n;
const {
  PluginDocumentSettingPanel,
  PluginSidebarMoreMenuItem,
  PluginSidebar
} = wp.editPost;
const {
  PanelBody,
  PanelRow,
  Icon
} = wp.components;
const {
  compose
} = wp.compose;
const {
  withSelect,
  withDispatch
} = wp.data;
const {
  useState,
  Fragment,
  useEffect
} = wp.element;

const BoosterSidebarPanel = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginSidebarMoreMenuItem, null, twb.cta_button.section_label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginSidebar, {
    title: twb.cta_button.section_label
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BoosterPanelRow, null)));
};

const BoosterSettingPanel = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginDocumentSettingPanel, {
    title: twb.cta_button.section_label
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BoosterPanelRow, null)));
};

const BoosterPanelRow = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, {
    className: "twb-cont"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(OptimizeButton, null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Dismiss, null));
};

const OptimizeButton = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: twb.href,
    target: "_blank",
    className: twb.cta_button.class + " twb-custom-button"
  }, twb.cta_button.label);
};

const Dismiss = () => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "twb-dismiss-info"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, __("You can hide this element from the ", "tenweb-booster"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: twb.href + "&twb_dismiss=1",
    target: "_blank"
  }, __('settings', "tenweb-booster"))));
};

registerPlugin('booster-sidebar-panel', {
  render: BoosterSidebarPanel,
  icon: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    class: "twb-speed-icon",
    xmlns: "http://www.w3.org/2000/svg",
    width: "26",
    height: "26",
    viewBox: "0 0 26 26"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
    id: "Group_103139",
    "data-name": "Group 103139",
    transform: "translate(0 -0.391)"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    id: "Path_171039",
    "data-name": "Path 171039",
    d: "M.441,38.127h0a1.445,1.445,0,0,0,2.065-.037l7.924-6.038a.131.131,0,0,0,.033-.18.126.126,0,0,0-.158-.045l-9.409,4a1.426,1.426,0,0,0-.52,2.23Z",
    transform: "translate(-0.028 -23.443)",
    fill: "#fff"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    id: "Path_171040",
    "data-name": "Path 171040",
    d: "M5.434,48.088a1.443,1.443,0,0,1-2.063.039l0,0L.462,45.274l-.034-.029-.06-.063a1.427,1.427,0,0,1,.12-1.992,1.393,1.393,0,0,1,.4-.252L4.295,41.49,3.723,42a1.571,1.571,0,0,0-.163,2.191q.045.054.095.1l1.74,1.7a1.5,1.5,0,0,1,.039,2.1",
    transform: "translate(-0.014 -30.56)",
    fill: "#9ea3a8"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    id: "Path_171041",
    "data-name": "Path 171041",
    d: "M69.869,43.142h0a1.445,1.445,0,0,0-2.065.037l-7.911,6.038a.131.131,0,0,0-.033.18.126.126,0,0,0,.158.045l9.4-4a1.426,1.426,0,0,0,.52-2.23Z",
    transform: "translate(-44.277 -31.469)",
    fill: "#fff"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    id: "Path_171042",
    "data-name": "Path 171042",
    d: "M78,32.276a1.443,1.443,0,0,1,2.063-.039l0,0,2.907,2.851L83,35.12l.06.063a1.427,1.427,0,0,1-.12,1.992,1.393,1.393,0,0,1-.4.252l-3.407,1.448.572-.507a1.571,1.571,0,0,0,.163-2.191q-.045-.054-.095-.1l-1.742-1.7a1.5,1.5,0,0,1-.036-2.1",
    transform: "translate(-57.411 -23.446)",
    fill: "#9ea3a8"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    id: "Path_171043",
    "data-name": "Path 171043",
    d: "M31.607,23.5l5.172-7.19a.126.126,0,0,0,0-.176.121.121,0,0,0-.173,0l-13.2,10.025a.131.131,0,0,0-.03.18.127.127,0,0,0,.106.055h4.143a.136.136,0,0,1,.134.139.138.138,0,0,1-.025.078L22.56,33.8a.126.126,0,0,0,0,.176.121.121,0,0,0,.173,0l13.2-10.025a.131.131,0,0,0,.03-.18.127.127,0,0,0-.106-.055H31.717a.136.136,0,0,1-.134-.139.138.138,0,0,1,.025-.078",
    transform: "translate(-16.668 -11.879)",
    fill: "#9ea3a8"
  })))
});
registerPlugin('booster-settings-panel', {
  render: BoosterSettingPanel,
  icon: ''
});
}();
/******/ })()
;
//# sourceMappingURL=gutenberg-compiled.js.map