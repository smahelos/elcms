/*
 * jQuery ajax.js
 * by Lukas Smahel
 * 
 * parameters:
 * ajaxurl -> targeted url with parameters
 * target  -> target html element
 */

// var saveButton = $('.ace_save');
// var cancelButton = $('.ace_cancel');
// var resourceContent;
// var getUrl = window.location;
// var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
// var aceDialog = $('.ace-dialog');
//
// // Ajax Load TEXT Content Function
// function loadFileContentToACE(ajaxurl,target){
//     jQuery.ajax({
//         type: "POST",
//         dataType: "text",
//         url: ajaxurl,
//         cache: false,
//         success: function(data, textStatus, jqXHR){
//             resourceContent = data;
//             aceDialog.fadeIn(300);
//             var editor = ace.edit("latteEditor");
//             ace.config.set('basePath', baseUrl + '/www/assets/admin/js/');
//             editor.setTheme("ace/theme/monokai");
//             editor.getSession().setMode("ace/mode/javascript");
//             editor.setValue(resourceContent); // or session.setValue
//             document.getElementById('editor').style.fontSize='16px';
//             editor.setHighlightActiveLine(false);
//         },
//         error: function(data){
//         }
//     });
//
//     return false;
// }
//
// // Ajax Save TEXT Content Function
// function saveFileContentFromACE(){
//     editor.on("input", function() {
//         saveButton.disabled = editor.session.getUndoManager().isClean();
//     });
//
//     saveButton.on('click', function() {
//         editor.session.getUndoManager().markClean()
//         saveButton.disabled = editor.session.getUndoManager().isClean()
//     });
//
//     return false;
// }
//
// // Ajax Save TEXT Content Function
// function closeACE(){
//     cancelButton.on('click', function() {
//         aceDialog.fadeOut(300);
//     });
//
//     return false;
// }
//
//
// $(document).ready(function()
// {
// });