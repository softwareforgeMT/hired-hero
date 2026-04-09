/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};

// ckeditor
itemid = 13;


var dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
dropzonePreviewNode.itemid = "";
var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);
var dropzone = new Dropzone(".dropzone", {
  url: 'https://httpbin.org/post',
  method: "post",
  previewTemplate: previewTemplate,
  previewsContainer: "#dropzone-preview"
}); // Form Event

(function () {
  'use strict'; // Fetch all the forms we want to apply custom Bootstrap validation styles to
   document.querySelector("#product-image-input").addEventListener("change", function () {
    var preview = document.querySelector("#product-img");
    var file = document.querySelector("#product-image-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load", function () {
      preview.src = reader.result;
    }, false);

    if (file) {
      reader.readAsDataURL(file);
    }
  });
 
})();
/******/ })()
;