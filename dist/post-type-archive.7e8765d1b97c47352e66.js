/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/debounce/index.js":
/*!****************************************!*\
  !*** ./node_modules/debounce/index.js ***!
  \****************************************/
/***/ (function(module) {

eval("/**\n * Returns a function, that, as long as it continues to be invoked, will not\n * be triggered. The function will be called after it stops being called for\n * N milliseconds. If `immediate` is passed, trigger the function on the\n * leading edge, instead of the trailing. The function also has a property 'clear' \n * that is a function which will clear the timer to prevent previously scheduled executions. \n *\n * @source underscore.js\n * @see http://unscriptable.com/2009/03/20/debouncing-javascript-methods/\n * @param {Function} function to wrap\n * @param {Number} timeout in ms (`100`)\n * @param {Boolean} whether to execute at the beginning (`false`)\n * @api public\n */\nfunction debounce(func, wait, immediate){\n  var timeout, args, context, timestamp, result;\n  if (null == wait) wait = 100;\n\n  function later() {\n    var last = Date.now() - timestamp;\n\n    if (last < wait && last >= 0) {\n      timeout = setTimeout(later, wait - last);\n    } else {\n      timeout = null;\n      if (!immediate) {\n        result = func.apply(context, args);\n        context = args = null;\n      }\n    }\n  };\n\n  var debounced = function(){\n    context = this;\n    args = arguments;\n    timestamp = Date.now();\n    var callNow = immediate && !timeout;\n    if (!timeout) timeout = setTimeout(later, wait);\n    if (callNow) {\n      result = func.apply(context, args);\n      context = args = null;\n    }\n\n    return result;\n  };\n\n  debounced.clear = function() {\n    if (timeout) {\n      clearTimeout(timeout);\n      timeout = null;\n    }\n  };\n  \n  debounced.flush = function() {\n    if (timeout) {\n      result = func.apply(context, args);\n      context = args = null;\n      \n      clearTimeout(timeout);\n      timeout = null;\n    }\n  };\n\n  return debounced;\n};\n\n// Adds compatibility for ES modules\ndebounce.debounce = debounce;\n\nmodule.exports = debounce;\n\n\n//# sourceURL=webpack://post-type-helper/./node_modules/debounce/index.js?");

/***/ }),

/***/ "./src/post-type-archive.ts":
/*!**********************************!*\
  !*** ./src/post-type-archive.ts ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var debounce__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! debounce */ \"./node_modules/debounce/index.js\");\n/* harmony import */ var debounce__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(debounce__WEBPACK_IMPORTED_MODULE_0__);\nvar __assign = (undefined && undefined.__assign) || function () {\r\n    __assign = Object.assign || function(t) {\r\n        for (var s, i = 1, n = arguments.length; i < n; i++) {\r\n            s = arguments[i];\r\n            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))\r\n                t[p] = s[p];\r\n        }\r\n        return t;\r\n    };\r\n    return __assign.apply(this, arguments);\r\n};\r\nvar __awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {\r\n    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }\r\n    return new (P || (P = Promise))(function (resolve, reject) {\r\n        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }\r\n        function rejected(value) { try { step(generator[\"throw\"](value)); } catch (e) { reject(e); } }\r\n        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }\r\n        step((generator = generator.apply(thisArg, _arguments || [])).next());\r\n    });\r\n};\r\nvar __generator = (undefined && undefined.__generator) || function (thisArg, body) {\r\n    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;\r\n    return g = { next: verb(0), \"throw\": verb(1), \"return\": verb(2) }, typeof Symbol === \"function\" && (g[Symbol.iterator] = function() { return this; }), g;\r\n    function verb(n) { return function (v) { return step([n, v]); }; }\r\n    function step(op) {\r\n        if (f) throw new TypeError(\"Generator is already executing.\");\r\n        while (_) try {\r\n            if (f = 1, y && (t = op[0] & 2 ? y[\"return\"] : op[0] ? y[\"throw\"] || ((t = y[\"return\"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;\r\n            if (y = 0, t) op = [op[0] & 2, t.value];\r\n            switch (op[0]) {\r\n                case 0: case 1: t = op; break;\r\n                case 4: _.label++; return { value: op[1], done: false };\r\n                case 5: _.label++; y = op[1]; op = [0]; continue;\r\n                case 7: op = _.ops.pop(); _.trys.pop(); continue;\r\n                default:\r\n                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }\r\n                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }\r\n                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }\r\n                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }\r\n                    if (t[2]) _.ops.pop();\r\n                    _.trys.pop(); continue;\r\n            }\r\n            op = body.call(thisArg, _);\r\n        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }\r\n        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };\r\n    }\r\n};\r\nvar __spreadArray = (undefined && undefined.__spreadArray) || function (to, from, pack) {\r\n    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {\r\n        if (ar || !(i in from)) {\r\n            if (!ar) ar = Array.prototype.slice.call(from, 0, i);\r\n            ar[i] = from[i];\r\n        }\r\n    }\r\n    return to.concat(ar || Array.prototype.slice.call(from));\r\n};\r\n\r\nfunction getUrlQuery() {\r\n    var match = window.location.href.match(\"q/(.*)\");\r\n    if (!match) {\r\n        return \"\";\r\n    }\r\n    return match[1];\r\n}\r\nfunction buildQueryFromFilters(filters) {\r\n    var filtersObj = filters.reduce(function (acc, curr) {\r\n        var _a;\r\n        return (__assign(__assign({}, acc), (_a = {}, _a[curr.taxonomy] = __spreadArray(__spreadArray([], (acc[curr.taxonomy] || []), true), [curr.term], false), _a)));\r\n    }, {});\r\n    return Object.keys(filtersObj)\r\n        .sort()\r\n        .map(function (taxonomy) {\r\n        return \"\".concat(taxonomy, \"/\").concat(filtersObj[taxonomy]\r\n            .sort()\r\n            // TODO underscore replace hack, should really fetch the\r\n            // real taxonomy slug from somewhere instead\r\n            .map(function (term) { return \"\".concat(term); })\r\n            .join(\"/\"));\r\n    })\r\n        .join(\"/\");\r\n}\r\njQuery(function ($) {\r\n    var config = {\r\n        containerSelector: \".posts\",\r\n        heroSelector: \".hero\",\r\n        heroBottomSelector: \".hero-bottom\",\r\n        pageSelector: \".page\",\r\n        itemSelector: \".box\",\r\n        filtersContainerSelector: \".filters-container\",\r\n        filterSelector: \".filter\",\r\n        loadMoreContSelector: \".load-more\",\r\n        loadMoreSelector: \".load-more a\",\r\n    };\r\n    var state = {\r\n        count: 0,\r\n        page: 1,\r\n        total: 0,\r\n        totalPages: 1,\r\n        filters: [],\r\n    };\r\n    function setState(obj) {\r\n        Object.assign(state, obj);\r\n        if (state.$loadMore) {\r\n            state.$loadMore.toggle(state.totalPages > state.page);\r\n        }\r\n    }\r\n    function init() {\r\n        setState({\r\n            $cont: $(config.containerSelector),\r\n            $hero: $(config.heroSelector),\r\n            $heroBottom: $(config.heroBottomSelector),\r\n            $page: $(config.pageSelector),\r\n            $filtersContainer: $(config.filtersContainerSelector),\r\n            $filters: $(config.filterSelector),\r\n            $loadMoreCont: $(config.loadMoreContSelector),\r\n            $loadMore: $(config.loadMoreSelector),\r\n        });\r\n        queryDomFilters();\r\n        queryDomPosts();\r\n        $(document).on(\"click\", config.loadMoreContSelector, onLoadMoreClick);\r\n        $(document).on(\"change\", config.filterSelector, onFilterChange);\r\n        window.addEventListener(\"popstate\", onPopState);\r\n    }\r\n    function queryDomFilters() {\r\n        if (!state.$filters) {\r\n            return;\r\n        }\r\n        var filters = state.$filters\r\n            .filter(function (_, el) { return el.checked; })\r\n            .map(function (_, _a) {\r\n            var name = _a.name, value = _a.value;\r\n            return ({\r\n                taxonomy: name,\r\n                term: value,\r\n            });\r\n        })\r\n            .get();\r\n        setState({ filters: filters });\r\n    }\r\n    function queryDomPosts() {\r\n        var $items = $(config.itemSelector);\r\n        setState({\r\n            $items: $items,\r\n            count: $items.length,\r\n        });\r\n    }\r\n    function setRouteState() {\r\n        var filters = state.filters;\r\n        window.history.replaceState({ filters: filters }, \"\");\r\n    }\r\n    function setRoute() {\r\n        var parts = [window.PTH.homeUrl, window.PTH.postTypeSlug];\r\n        var q = buildQueryFromFilters(state.filters);\r\n        if (q) {\r\n            parts.push(\"q\", q);\r\n        }\r\n        var next = parts.filter(Boolean).join(\"/\") + \"/\";\r\n        window.history.pushState(null, \"\", next);\r\n    }\r\n    function onLoadMoreClick(e) {\r\n        e.preventDefault();\r\n        setState({ page: state.page + 1 });\r\n        getPosts();\r\n    }\r\n    function onFilterChange(e) {\r\n        var _a = e.target, name = _a.name, value = _a.value;\r\n        if (!name || !value) {\r\n            return;\r\n        }\r\n        if (state.getPostsDebounce) {\r\n            state.getPostsDebounce.clear();\r\n        }\r\n        setRouteState();\r\n        queryDomFilters();\r\n        var getPostsDebounce = debounce__WEBPACK_IMPORTED_MODULE_0___default()(getPostsFresh, 1);\r\n        getPostsDebounce();\r\n        setState({ page: 1, getPostsDebounce: getPostsDebounce });\r\n        setRoute();\r\n    }\r\n    function onPopState(event) {\r\n        var newState = event.state;\r\n        setState(newState);\r\n        getPostsFresh();\r\n    }\r\n    function getPostsFresh() {\r\n        return getPosts({ empty: true });\r\n    }\r\n    function getPosts(params) {\r\n        if (params === void 0) { params = {}; }\r\n        return __awaiter(this, void 0, void 0, function () {\r\n            var defaults, _a, placeItems, empty, q, requestParams, url, _i, _b, paramKey, abortController, newState, result, $filtersContainer, $hero, $heroBottom, $pageClone, _c, _d, item, $elem, err_1;\r\n            return __generator(this, function (_e) {\r\n                switch (_e.label) {\r\n                    case 0:\r\n                        defaults = {\r\n                            empty: false,\r\n                            placeItems: true,\r\n                        };\r\n                        _a = __assign(__assign({}, defaults), params), placeItems = _a.placeItems, empty = _a.empty;\r\n                        q = buildQueryFromFilters(state.filters);\r\n                        requestParams = {\r\n                            action: \"get-posts\",\r\n                            page: String(state.page),\r\n                            type: window.PTH.postType,\r\n                            q: q,\r\n                        };\r\n                        url = new URL(window.PTH.ajaxUrl);\r\n                        for (_i = 0, _b = Object.keys(requestParams); _i < _b.length; _i++) {\r\n                            paramKey = _b[_i];\r\n                            url.searchParams.append(paramKey, requestParams[paramKey]);\r\n                        }\r\n                        if (state.getPostsAbort) {\r\n                            state.getPostsAbort.abort();\r\n                        }\r\n                        abortController = new AbortController();\r\n                        setState({\r\n                            getPostsAbort: abortController,\r\n                        });\r\n                        _e.label = 1;\r\n                    case 1:\r\n                        _e.trys.push([1, 3, , 4]);\r\n                        newState = {};\r\n                        return [4 /*yield*/, window\r\n                                .fetch(url.toString(), { signal: abortController.signal })\r\n                                .then(function (d) { return d.json().then(function (data) { return data; }); })];\r\n                    case 2:\r\n                        result = _e.sent();\r\n                        if (state.$filtersContainer && result.filtersContainer) {\r\n                            $filtersContainer = $(result.filtersContainer.markup);\r\n                            state.$filtersContainer.replaceWith($filtersContainer);\r\n                            newState.$filtersContainer = $filtersContainer;\r\n                            newState.$filters = newState.$filtersContainer.find(config.filterSelector);\r\n                        }\r\n                        if (state.$hero && result.hero) {\r\n                            $hero = $(result.hero.markup);\r\n                            state.$hero.replaceWith($hero);\r\n                            newState.$hero = $hero;\r\n                        }\r\n                        if (state.$heroBottom && result.heroBottom) {\r\n                            $heroBottom = $(result.heroBottom.markup);\r\n                            state.$heroBottom.replaceWith($heroBottom);\r\n                            newState.$heroBottom = $heroBottom;\r\n                        }\r\n                        if (state.$cont && state.$page) {\r\n                            if (empty) {\r\n                                state.$cont.empty();\r\n                            }\r\n                            if (placeItems) {\r\n                                $pageClone = state.$page.clone().empty();\r\n                                for (_c = 0, _d = result.items; _c < _d.length; _c++) {\r\n                                    item = _d[_c];\r\n                                    if (!item.markup) {\r\n                                        continue;\r\n                                    }\r\n                                    $elem = $(item.markup);\r\n                                    $elem.appendTo($pageClone);\r\n                                }\r\n                                state.$cont.append($pageClone);\r\n                                if (result.cta) {\r\n                                    $(result.cta.markup).appendTo(state.$cont);\r\n                                }\r\n                                if (state.$loadMoreCont) {\r\n                                    state.$cont.append(state.$loadMoreCont);\r\n                                }\r\n                                queryDomPosts();\r\n                            }\r\n                        }\r\n                        setState(__assign(__assign({}, newState), { total: result.total, totalPages: result.totalPages, getPostsAbort: undefined }));\r\n                        return [3 /*break*/, 4];\r\n                    case 3:\r\n                        err_1 = _e.sent();\r\n                        return [3 /*break*/, 4];\r\n                    case 4: return [2 /*return*/];\r\n                }\r\n            });\r\n        });\r\n    }\r\n    init();\r\n    getPosts({\r\n        placeItems: false,\r\n    });\r\n});\r\n\n\n//# sourceURL=webpack://post-type-helper/./src/post-type-archive.ts?");

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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./src/post-type-archive.ts");
/******/ 	
/******/ })()
;