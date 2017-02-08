jQuery(document).on('submit', 'form.async', function (event) {
    event.defaultPrevented = true;

    var form = jQuery(this);

    jQuery.post(form.attr('action'), form.serializeArray(), function (response) {
        console.log(response.errors);

        Validate.showErrorsMassages(response.errors);
    });

    return false;
});

jQuery(document).on('change', '#select-language', function () {

        $("#select-language").submit();

});

jQuery( document ).ready(function() {

    jQuery('body .input-group.date').datepicker({});

    $("#start_day_field_picker").datepicker({
            autoclose: true,
            todayBtn: "linked",
            todayHighlight: true
        }
    )
});


var Validate = {
    showErrorsMassages: function(errors) {
        jQuery('.errors-block').remove();

        if (errors !== undefined) {
            for (var field in errors) {
                if (errors[field] !== undefined){
                    $.each(errors[field], function( index, massage ) {
                      jQuery("input[name='" + field + "']").after('<span class="label errors-block label-danger">' + massage + '</span>');
                        jQuery("select[name='" + field + "']").after('<span class="label errors-block label-danger">' + massage + '</span>');
                    });
                }
            }
        }
    }
};

