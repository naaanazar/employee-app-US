var ModalAction;
/**
 * Submit form with ajax
 */
jQuery(document).on('submit', 'form.async', function (event) {
    event.defaultPrevented = true;

    var form           = jQuery(this);
    var formData       = new FormData;
    var serializedForm = form.serializeArray();

    for (var i in serializedForm) {
        var input = serializedForm[i];
        formData.append(input.name, input.value);
    }

    [].slice.call(form.find('[type="file"]')).forEach(function (fileInput) {

        var files = fileInput.files;

        for (var i in files) {
            var file = files[i];

            if (file instanceof File && file.type.match('image.*')) {
                formData.append($(fileInput).attr('name'), file);
            }
        }
    });

    jQuery.ajax(
        {
            url: form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            method: 'post',
            success: function (response) {
                Validate.showErrorsMassages(response.errors);
                Validate.redirect(response.redirect);
            }
        }
    );

    return false;
});

/**
 * Event of language change
 */
jQuery(document).on('change', '#select-language', function () {
    $("#select-language").submit();
});

/**
 * Event of statistic last date change
 */
jQuery(document).on('change', '.statistics-form', function () {
    $(".statistics-form").submit();
});

$(document).ajaxStart(function() {
    $('body').loading();
});

$(document).ajaxComplete(function() {
    $('body').loading('toggle');
});

/**
 * Modal action event
 */
jQuery(document).on('click', '.modal-action', function (event) {
    event.defaultPrevented = true;
    var element = $(this);
    var _url = window.location.href.replace(/#modal-action(.+)&#modal-element(.+)/, '');
    window.history.pushState("", "", _url + '#modal-action' + element.data('action') + '&#modal-element' + element.data('element'));
    var modalAction = new ModalAction(element.data('action'), element.data('element'));
    modalAction.execute();

    return false;
});

jQuery(document).on('hidden.bs.modal', '#modal-action', function () {
    window.history.pushState(
        '', '', window.location.href.replace(/#modal-action(.+)&#modal-element(.+)/, '')
    );
});

jQuery(document).on('click', '#delete_employee', function(event) {
    event.defaultPrevented = true;

    var element = $(this);
    var deleteEmployee = new DeleteEmployee(element.data('action'), {hash: element.data('hash')});

    deleteEmployee.execute();

    return false;
});

/**
 * On load event
 */
jQuery('document').ready(function () {

   var modalParams = window.location.href.match(/#modal-action(.+)&#modal-element(.+)/);

   if (modalParams !== null && modalParams.length === 3) {
       var modalAction = new ModalAction(modalParams[1], modalParams[2]);
       modalAction.execute();
   }

   $('.nav-stacked').find('a[href="' + window.location.href + '"]').parent().addClass('active')

    /**
     * init datapicker
     */
    jQuery('input .input-group.date').datepicker({});

    /**
     * set datapicker range search form
     */
    jQuery('#sandbox-container .input-daterange').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    /**
     * set datapicker employee form
     */
    jQuery("#start_day_field_picker").datepicker({
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
    /**
     * show errors block under form fields
     * @param errors
     */
    showErrorsMassages: function(errors) {
        jQuery('.errors-block').remove();

        if (errors !== undefined) {
            for (var field in errors) {
                if (errors[field] !== undefined){

                    $.each(errors[field], function( index, massage ) {

                        if (jQuery("input[name='" + field + "']").closest('.form-group').length === 0){
                            console.log(jQuery("input[name='" + field + "']").closest('.form-group').length);
                            jQuery("input[name='" + field + "']").closest('.input-group').after('<div class="label errors-block label-danger" style="padding-top: -15px;">' + massage + '</div>');
                        }

                      jQuery("input[name='" + field + "']").closest('.form-group').append('<div class="label errors-block label-danger">' + massage + '</div>');
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
ModalAction = function (action, selector, params) {
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

/**
 * Ajax call
 * @param action
 * @param data
 * @param success
 * @constructor
 */
var AjaxAction = function (action, data, success) {

    this.execute = function () {

        var successFunction;
        if (typeof success !== 'function') {
            successFunction = function (data) {

                if (data.hasOwnProperty('html')) {
                    $(success).html(data.html);
                }
            };
        } else {
            successFunction = success;
        }

        $.ajax(
            {
                url: action,
                data: data,
                success: successFunction,
                method: 'post'
            }
        );
    }
};

/**
 *
 * @param action Url to call
 * @param data Request params
 * @constructor
 */
var DeleteEmployee = function(action, data) {
    this.execute = function () {

        $.ajax(
            {
                url: action,
                data: data,
                success: function(data) {
                    if('deleted' == data.status) {
                        jQuery('#modal-action').modal('hide');

                        searchEmployee();
                    }

                    $('body').loading('toggle');
                },
                method: 'post'
            }
        );
    }
};

/**
 * Hide button delete in show employee
 */
jQuery(document).on('click', '#delete_employee_show', function () {
    jQuery('#delete_employee_show').toggle();
    jQuery('.edit-profile-modal').toggle();

});


/**
 * delete comment
 */
jQuery(document).on('click', '.configure-delete', function (event) {
    var id = jQuery(event.target).closest('.configure-buttons').data('id');
    var config = jQuery(event.target).closest('.configure-buttons').data('config');
    console.log(id);
    console.log(config);
    jQuery.post( "/dashboard/configure-delete", {id : id, config : config}, function( data ) {
        jQuery(event.target).closest('tr').remove();
    })
});


jQuery('span').css('pointer-events', 'none');


