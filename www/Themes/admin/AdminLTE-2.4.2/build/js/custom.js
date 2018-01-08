//** custom scripts
$( document ).ready(function()
{
    //datagrid item active after click
    clickDatagridItemClass();

    //iCheck for checkbox and radio inputs
    //Flat red color scheme for iCheck
    $('.icheck input[type="checkbox"], .icheck input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass   : 'iradio_flat-green'
    });
});

//* reinitialize after ajax
$.nette.ext('snippets').after(function () {
    var contentWrapper = '.content-wrapper';
    // 101 is height of main-header and footer blocks
    var contentWrapperHeight = $(window).height() - 101;

    //datagrid item active after click
    clickDatagridItemClass();

    //iCheck for checkbox and radio inputs
    //Flat red color scheme for iCheck
    $('.icheck input[type="checkbox"], .icheck input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass   : 'iradio_flat-green'
    });

    //set new content height
    $('body, html, ' + contentWrapper).css({
        'min-height': contentWrapperHeight
    });
});

//* get datagrid item active after click
function clickDatagridItemClass()
{
    $('a.editArticleItem').on('click', function(){
        $('a.editArticleItem').removeClass('active');
        $(this).addClass('active');
    });
}