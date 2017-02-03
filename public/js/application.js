window.onload = function () {
    jQuery(document).on('submit', 'form.async', function (event) {
        event.defaultPrevented = true;


        return false;
    });
};