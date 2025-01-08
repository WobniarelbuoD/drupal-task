/*!
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md.
 */(()=>{var e={331:(e,r,t)=>{e.exports=t(237)("./src/clipboard.js")},782:(e,r,t)=>{e.exports=t(237)("./src/core.js")},507:(e,r,t)=>{e.exports=t(237)("./src/enter.js")},727:(e,r,t)=>{e.exports=t(237)("./src/select-all.js")},834:(e,r,t)=>{e.exports=t(237)("./src/typing.js")},311:(e,r,t)=>{e.exports=t(237)("./src/ui.js")},251:(e,r,t)=>{e.exports=t(237)("./src/undo.js")},237:e=>{"use strict";e.exports=CKEditor5.dll}},r={};function t(s){var o=r[s];if(void 0!==o)return o.exports;var i=r[s]={exports:{}};return e[s](i,i.exports,t),i.exports}t.d=(e,r)=>{for(var s in r)t.o(r,s)&&!t.o(e,s)&&Object.defineProperty(e,s,{enumerable:!0,get:r[s]})},t.o=(e,r)=>Object.prototype.hasOwnProperty.call(e,r),t.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var s={};(()=>{"use strict";t.r(s),t.d(s,{Essentials:()=>a});var e=t(782),r=t(331),o=t(507),i=t(727),n=t(834),l=t(251),c=t(311);class a extends e.Plugin{static get requires(){return[c.AccessibilityHelp,r.Clipboard,o.Enter,i.SelectAll,o.ShiftEnter,n.Typing,l.Undo]}static get pluginName(){return"Essentials"}static get isOfficialPlugin(){return!0}}})(),(window.CKEditor5=window.CKEditor5||{}).essentials=s})();