(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"2+Mu":function(t,r,e){"use strict";e.r(r);var n=e("ma/q");function o(t){return(o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function i(t,r){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);r&&(n=n.filter((function(r){return Object.getOwnPropertyDescriptor(t,r).enumerable}))),e.push.apply(e,n)}return e}function a(t,r,e){return(r=function(t){var r=function(t,r){if("object"!==o(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var n=e.call(t,r||"default");if("object"!==o(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===r?String:Number)(t)}(t,"string");return"symbol"===o(r)?r:String(r)}(r))in t?Object.defineProperty(t,r,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[r]=e,t}var u={props:{attrs:Object},data:function(){return{antv:null}},mounted:function(){this.antv=new n.Area(this.attrs.canvasId,function(t){for(var r=1;r<arguments.length;r++){var e=null!=arguments[r]?arguments[r]:{};r%2?i(Object(e),!0).forEach((function(r){a(t,r,e[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(e)):i(Object(e)).forEach((function(r){Object.defineProperty(t,r,Object.getOwnPropertyDescriptor(e,r))}))}return t}({data:this.attrs.data},this.attrs.config)),this.antv.render()},updated:function(){this.antv.changeData(this.attrs.data)},destroyed:function(){}},c=e("KHd+"),s=Object(c.a)(u,(function(){return(0,this._self._c)("div",{attrs:{id:this.attrs.canvasId}})}),[],!1,null,null,null);r.default=s.exports}}]);