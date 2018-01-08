// (function($, undefined) {
//     $.nette.ext('spinner', {
//         init: function () {
//         },
//         start: function (jqXHR, settings) {
//             this.spinner = $('#ajax-spinner');
//             this.speed = 50;
//             $('#ajax-spinner-inner').css({
//                 left: '50%',
//                 top: '40%'
//             });
//             this.spinner.show(this.speed);
//         },
//         complete: function () {
//             this.spinner.hide(this.speed);
//         }
//     });
// })(jQuery);
(function($, undefined) {

    $.nette.ext('spinner', {
        init: function () {
            this.spinner = this.createSpinner();
            this.spinner.appendTo('body');
        },
        start: function (jqXHR, settings) {
            this.spinner = $('#ajax-spinner');
            $('#ajax-spinner-inner').css({
                left: '50%',
                top: '40%'
            });
            this.counter++;
            if (this.counter === 1) {
                this.spinner.show(this.speed);
            }
        },
        complete: function () {
            this.counter--;
            if (this.counter <= 0) {
                this.spinner.hide(this.speed);
            }
        }
    },
    {
        createSpinner: function () {
            var spinner = $('<div>', {
                id: 'ajax-spinner',
                css: {
                    display: 'none'
                }
            });
            var spinner_inner = $('<div>', {
                id: 'ajax-spinner-inner'
            });
            // -- delete if you use bacground image, no ico
            var icon = $('<i>', {
                class: 'fa fa-circle-o-notch fa-spin fa-3x fa-fw'
            });
            spinner.append(spinner_inner);
            spinner_inner.append(icon);
            // -- delete if you use bacgroun image, no ico
            return spinner;
        },
        spinner: null,
        speed: undefined,
        counter: 0
    });

})(jQuery);