/* global window, Gplcart, jQuery */
(function (window, document, $, Gplcart) {

    "use strict";

    /**
     * Controls file uploads
     * @returns {undefined}
     */
    Gplcart.onload.controlUpload = function () {

        var limit, text;

        $(document).on('change', '.filemanager [name="files[]"]', function () {

            limit = Gplcart.settings.file_manager.upload_limit;
            text = Gplcart.text('PHP allows you to upload only @num files at a time', {"@num": limit});

            $('.upload-limit-warning').remove();

            if (this.files.length > limit) {
                $(this).val('').after('<div class="upload-limit-warning text-danger">' + text + '</div>');
            }
        });
    };

    /**
     * Handle filters
     * @returns {undefined}
     */
    Gplcart.onload.handleFilters = function () {

        var params, input = $('.filemanager :input[name="filter_key"]');

        if (input.length) {
            changeFilterValueInput(input);
            input.change(function () {
                changeFilterValueInput($(this));
            });
        }

        $(document).on('change', '[name="command_id"]', function () {
            $(':submit[name="process_selected"]').click();
            return false;
        });

        $(document).on('click', '.filemanager :submit.filter', function () {

            params = $(this).closest('form').find(':input[name^="filter_"]').filter(function () {
                return this.value !== '';
            }).serialize();

            if (params) {
                window.location = window.location.href.split('?')[0] + '?' + params;
            }

            return false;
        });
    };

    /**
     * Changes filter value field depending on the selected filter option
     * @param {Object} input
     * @returns {undefined}
     */
    var changeFilterValueInput = function (input) {

        var placeholder, text, op = input.find(':selected');

        if (op.length) {

            placeholder = op.data('input');
            text = input.closest('form').find(':text');

            if (op.val().length === 0 || placeholder === undefined) {
                text.attr({placeholder: '', disabled: true});
            } else {
                text.attr({placeholder: placeholder, disabled: false});
            }
        }
    };

})(window, document, jQuery, Gplcart);