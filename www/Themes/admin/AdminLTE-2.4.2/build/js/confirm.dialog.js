/**
 * Confirm dialog plugin (For bootstrap 3 @Raito Akehanareru)
 *
 * @copyright  Copyright (c) 2012 Jan Červený
 * @license    BSD
 * @link       confirmdialog.redsoft.cz
 * @version    1.1
 */

(function ($, undefined) {

    $.nette.ext({
        load: function () {
            $('[data-confirm]').click(function (event) {
                var obj = this;
                event.preventDefault();
                event.stopImmediatePropagation();
                $("<div id='dConfirm' class='modal fade'></div>").appendTo('body');
                $('#dConfirm').html("<div class='modal-dialog'><div class='modal-content'><div id='dConfirmHeader' class='modal-header'></div><div id='dConfirmBody' class='modal-body'></div><div id='dConfirmFooter' class='modal-footer'></div></div></div>");
                $('#dConfirmHeader').html("<a class='close' data-dismiss='modal'>×</a><h3 id='dConfirmTitle'></h3>");
                $('#dConfirmTitle').html($(obj).data('confirm-title'));
                $('#dConfirmBody').html($(obj).data('confirm-text'));
                $('#dConfirmFooter').html("<a id='dConfirmOk' class='btn " + $(obj).data('confirm-ok-class') + "' data-dismiss='modal'>Ano</a><a id='dConfirmCancel' class='btn " + $(obj).data('confirm-cancel-class') + "' data-dismiss='modal'>Ne</a>");
                if ($(obj).data('confirm-header-class')) {
                    $('#dConfirmHeader').addClass($(obj).data('confirm-header-class'));
                }
                if ($(obj).data('confirm-ok-text')) {
                    $('#dConfirmOk').html($(obj).data('confirm-ok-text'));
                }
                if ($(obj).data('confirm-cancel-text')) {
                    $('#dConfirmCancel').html($(obj).data('confirm-cancel-text'));
                }
                $('#dConfirmOk').on('click', function () {
                    var tagName = $(obj).prop("tagName");
                    if (tagName == 'INPUT') {
                        var form = $(obj).closest('form');
                        form.submit();
                    } else {
                        if ($(obj).data('ajax') == 'on') {
                            $.nette.ajax({
                                url: obj.href
                            });
                        } else {
                            document.location = obj.href;
                        }
                    }
                });
                $('#dConfirm').on('hidden', function () {
                    $('#dConfirm').remove();
                });
                $('#dConfirm').modal('show');
                return false;
            });
        }
    });

})(jQuery);