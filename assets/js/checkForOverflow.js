console.log("CHECK FOR OVERFLOW 1");
var docWidth = document.documentElement.offsetWidth;
console.log(docWidth);

[].forEach.call(
  document.querySelectorAll('*'),
  function(el) {
    if (el.offsetWidth > docWidth) {
        console.log("CHECK FOR OVERFLOW 2");
        console.log(el);
    }
  }
);