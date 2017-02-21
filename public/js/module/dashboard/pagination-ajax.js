'use strict';

/**
 * pagination ajax
 */
jQuery('document').ready(function () {
    var page = jQuery(event.target).data('page');
    jQuery('#page-number').val(page);

    jQuery(document).on('click', '.paginator-a', searchEmployee);
});

