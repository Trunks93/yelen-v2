console.log('---YELEN CUSTOMIZATION JS----')
console.log('---YELEN CUSTOMIZATION JS - safeActiveElement----', safeActiveElement())

if(typeof safeActiveElement === "undefined" || typeof safeActiveElement !== "function"){
  console.log('---YELEN CUSTOMIZATION JS - safeActiveElement is not a function----')
}
function safeActiveElement() {
  try {
    return document.activeElement;
  } catch ( err ) { }
}
