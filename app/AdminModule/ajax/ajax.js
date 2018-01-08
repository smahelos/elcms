/*
 * jQuery ajax.js
 * by Lukas Smahel
 * 
 * parameters:
 * ajaxurl -> targeted url with parameters
 * target  -> target html element
 */

// Ajax Load HTML Content Function
function loadContent(ajaxurl,target){
    jQuery.ajax({
        type: "POST",
        dataType: "html",
        url: ajaxurl,
        cache: false,
        success: function(data){
            jQuery(target).fadeOut(300, function() {
                jQuery(target).html(data);
            }).fadeIn(300);
        },
        error: function(data){
        }
    });
    return false;
}


$( document ).ready(function()
{
});