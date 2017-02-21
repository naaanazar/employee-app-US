'use strict';

/**
 * pagination ajax
 */
jQuery('document').ready(function () {
    jQuery(document).on('click', '.paginator-a', function (event) {
    Paginator.paginatorEvent(event)
    })
});

var Paginator = {

    paginatorEvent: function (event) {
        event.defaultPrevented = true;

        var page = jQuery(event.target).data('page');
        jQuery('#page-number').val(page);
        
        $('form.search-employee').submit();

            return false;

    }
}

