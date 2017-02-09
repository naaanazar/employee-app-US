/**
 * Submit form with ajax
 */
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

/**
 * Event of language change
 */
jQuery(document).on('change', '#select-language', function () {
    $("#select-language").submit();
});

/**
 * Modal action event
 */
jQuery(document).on('click', '.modal-action', function (event) {
    event.defaultPrevented = true;
    var element = $(this);
    var modalAction = new ModalAction(element.data('action'), element.data('element'));
    modalAction.execute();

    return false;
});

/**
 * On load event
 */
jQuery('document').ready(function () {
   $('.nav-stacked').find('a[href="' + window.location.href + '"]').parent().addClass('active')

    jQuery('input .input-group.date').datepicker({});

    $("#start_day_field_picker").datepicker({
            autoclose: true,
            todayBtn: "linked",
            format: 'yyyy-mm-dd',
            todayHighlight: true
        }
    )
});

/**
 * @type {{showErrorsMassages: Validate.showErrorsMassages, redirect: Validate.redirect}}
 */
var Validate = {
    showErrorsMassages: function(errors) {
        jQuery('.errors-block').remove();

        if (errors !== undefined) {
            for (var field in errors) {
                if (errors[field] !== undefined){
                    $.each(errors[field], function( index, massage ) {
                      jQuery("input[name='" + field + "']").closest('.form-group').append('<span class="label errors-block label-danger">' + massage + '</span>');
                        jQuery("select[name='" + field + "']").after('<span class="label errors-block label-danger">' + massage + '</span>');
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

/**
 * @param action   Url to call
 * @param selector Css selector to render response
 * @param params   Request params
 * @constructor
 */
var ModalAction = function (action, selector, params) {

    /**
     * Execute ajax for html get
     */
    this.execute = function () {

        $.ajax(
            {
                url: action,
                data: params,
                success: function (data) {
                    if ($(selector) && data.html) {
                        $(selector).modal().find('.modal-body').html(data.html);
                    }
                }
            }
        );

    };

};