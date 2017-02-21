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
        $('form.search-employee').submit();

            return false;

    }
}

