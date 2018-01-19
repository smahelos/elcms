//** custom scripts
var $content = $('#snippet--content');

$( document ).ready(function()
{
    //datagrid item active after click
    clickDatagridItemClass();

    //show passwords inputs in user form after change password input is checked
    showPasswordInputs();

    //iCheck for checkbox and radio inputs
    //Flat red color scheme for iCheck
    $('.icheck input[type="checkbox"], .icheck input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass   : 'iradio_flat-green'
    });

    //Date picker
    $('.datepicker').datetimepicker({
        locale: 'cs'
    });

});

//* reinitialize after all snippets
$.nette.ext('snippets').complete(function () {
    var contentWrapper = '.content-wrapper';
    // 101 is height of main-header and footer blocks
    var contentWrapperHeight = $(window).height() - 101;

    //datagrid item active after click
    clickDatagridItemClass();

    //show passwords inputs in user form after change password input is checked
    showPasswordInputs();

    // init ckEdior
    loadCkEditor();

    //iCheck for checkbox and radio inputs
    //Flat red color scheme for iCheck
    $('.icheck input[type="checkbox"], .icheck input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass   : 'iradio_flat-green'
    });

    //Date picker
    $('.datepicker').datetimepicker({
        locale: 'cs',
        format: 'YYYY-MM-DD hh:mm'
    });

    //set new content height
    $('body, html, ' + contentWrapper).css({
        'min-height': contentWrapperHeight
    });

});

// // before ajax
// $.nette.ext('snippets').before(function () {
// });

// after each snippet
// $.nette.ext('snippets').after(function () {
// });

//* get datagrid item active after click
function clickDatagridItemClass()
{
    $('a.editArticleItem').on('click', function(){
        $('a.editArticleItem').removeClass('active');
        $(this).addClass('active');
    });
}

//* get datagrid item active after click
function showPasswordInputs()
{
    $('input[name="newPassword"]').on('ifChanged', function(){
        if (this.checked) {
            $('.passwordInputs').removeClass('hidden').show('100');
        } else {
            $('.passwordInputs').hide('100');
        }
    });
}

//CKEDITOR init and destroy
function loadCkEditor() {
    CKEDITOR.replace('ckEditor', {
        baseHref: CKEDITOR.basePath + '/../../../../../',
        filebrowserBrowseUrl: CKEDITOR.basePath + '../kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: CKEDITOR.basePath + '../kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: CKEDITOR.basePath + './kcfinder/browse.php?type=flash',
        filebrowserUploadUrl: CKEDITOR.basePath + '../kcfinder/upload.php?type=files',
        filebrowserImageUploadUrl: CKEDITOR.basePath + '../kcfinder/upload.php?type=images',
        filebrowserFlashUploadUrl: CKEDITOR.basePath + '../kcfinder/upload.php?type=flash'
    });
}