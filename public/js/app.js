/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _bootstrap__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module './components/ExampleComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

var app = Object(function webpackMissingModule() { var e = new Error("Cannot find module 'vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())({});

app.component("example-component", Object(function webpackMissingModule() { var e = new Error("Cannot find module './components/ExampleComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()));
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount("#app"); // const vueApp = new Vue({
//     el: "#app",
//     created() {
//         Echo.channel("notification").listen("MessageNotification", (e) => {
//             console.log("Hello");
//         });
//     },
// });

/***/ }),

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'lodash'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'bootstrap'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module 'axios'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());

window._ = Object(function webpackMissingModule() { var e = new Error("Cannot find module 'lodash'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */


window.axios = Object(function webpackMissingModule() { var e = new Error("Cannot find module 'axios'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nModuleNotFoundError: Module not found: Error: Can't resolve '../../node_modules/css-loader/dist/runtime/api.js' in 'C:\\xampp\\htdocs\\xobible\\resources\\sass'\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\Compilation.js:2016:28\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:798:13\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:10:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:270:22\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:9:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:434:22\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:116:11\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:670:25\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:855:8\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:975:5\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\neo-async\\async.js:6883:13\n    at Array.<anonymous> (C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:950:14)\n    at arrayEachFunc (C:\\xampp\\htdocs\\xobible\\node_modules\\neo-async\\async.js:2517:19)\n    at Object.parallel (C:\\xampp\\htdocs\\xobible\\node_modules\\neo-async\\async.js:6858:9)\n    at NormalModuleFactory._resolveResourceErrorHints (C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:873:12)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\webpack\\lib\\NormalModuleFactory.js:834:18\n    at finishWithoutResolve (C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:312:11)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:386:15\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:16:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:27:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\DescriptionFilePlugin.js:87:43\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:15:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:16:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:16:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:27:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\DescriptionFilePlugin.js:87:43\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:16:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\Resolver.js:435:5\n    at eval (eval at create (C:\\xampp\\htdocs\\xobible\\node_modules\\tapable\\lib\\HookCodeFactory.js:33:10), <anonymous>:15:1)\n    at C:\\xampp\\htdocs\\xobible\\node_modules\\enhanced-resolve\\lib\\DirectoryExistsPlugin.js:41:15\n    at processTicksAndRejections (node:internal/process/task_queues:82:21)");

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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	__webpack_require__("./resources/js/app.js");
/******/ 	// This entry module doesn't tell about it's top-level declarations so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/sass/app.scss");
/******/ 	
/******/ })()
;