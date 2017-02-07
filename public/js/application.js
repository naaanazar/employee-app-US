jQuery(document).on('submit', 'form.async', function (event) {
    event.defaultPrevented = true;

    var form = jQuery(this);

    jQuery.post(form.attr('action'), form.serializeArray(), function (response) {
        console.log(response.errors);

        Validate.showErrorsMassages(response.errors);
        Validate.redirect(response.redirect);
    });

    return false;
});

jQuery(document).on('change', '#select-language', function () {

        $("#select-language").submit();

});

jQuery('document').ready(function () {
   $('.nav-stacked').find('a[href="' + window.location.href + '"]').parent().addClass('active')
});


var Validate = {
    showErrorsMassages: function(errors) {
        jQuery('.errors-block').remove();

        if (errors !== undefined) {
            for (var field in errors) {
                if (errors[field] !== undefined){
                    $.each(errors[field], function( index, massage ) {
                      jQuery("input[name='" + field + "']").after('<span class="label errors-block label-danger">' + massage + '</span>');
                    });
                }
            }
        }
    },

    redirect: function (url) {
        if (url !== undefined) {
            window.location.assign(url);
        }
    }
};

