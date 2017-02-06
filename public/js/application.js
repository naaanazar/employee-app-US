jQuery(document).on('submit', 'form.async', function (event) {
    event.defaultPrevented = true;

    var form = jQuery(this);

    jQuery.post(form.attr('action'), form.serializeArray(), function (response) {
        console.log(response);
    });

    return false;
});