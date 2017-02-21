'use strict';

/**
 * pagination ajax
 */
jQuery(document).on('click', '.paginator-a', function (event) {
    event.defaultPrevented = true;

    var page = jQuery(event.target).data('page');
    jQuery('#page-number').val(page);

    /**
     * @param data
     */
    var callback = function (data) {

        jQuery('document').ready(function () {
            Sort.initTable('#employee_table');
            Sort.eventSort('#employee_table', '#filter-employee-form');
        });

        jQuery('#employees-list').html(data.html);
    };

    /**
     * @type {AjaxAction}
     */
    var action = new AjaxAction(jQuery('.search-employees').attr('action'), jQuery('.search-employees').serializeArray(), callback);
    action.execute();

    return false;
});
