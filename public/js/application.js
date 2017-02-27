var ModalAction;

var promise = jQuery.Deferred();

/**
 * Submit form with ajax
 */
jQuery(document).on('submit', 'form.async', function (event) {
    ajaxFormSubmit(event);
    return false;
});

jQuery(document).on('submit', 'form.create-employee', function (event) {

    promise = jQuery.Deferred();
    CheckCoords();
    jQuery.when(promise).then(
        function () {
            ajaxFormSubmit(event);
        }
    );
    return false;
});

var ajaxFormSubmit = function (event) {
    event.defaultPrevented = true;

    var form  = jQuery(event.target);

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
};

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
    var deleteEmployee = new DeleteEmployee(
        element.data('action'),
        {
            hash: element.data('hash'),
            status: element.data('status'),
            reason: jQuery('#delete-ask :selected').text()
        });

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
        format: 'dd-mm-yyyy'
    });

    /**
     * set datapicker employee form
     */
    jQuery("#start_day_field_picker").datepicker({
            autoclose: true,
            todayBtn: "linked",
            format: 'dd-mm-yyyy',
            todayHighlight: true
        }
    )
});

var CheckCoords = function() {

    if ('' === jQuery('#latitude').val() || '' === jQuery('#longitude').val()) {
        Address.setAddress();

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': Address.fullAddress}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                document.getElementById('latitude').value = results[0].geometry.location.lat();
                document.getElementById('longitude').value = results[0].geometry.location.lng();
                promise.resolve('ok');
            }
        });

    }
};

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
                method: 'post',
                success: function(data) {
                    $('body').loading('toggle');

                    if ((modalAction = jQuery('#modal-action')).length === 1) {
                        modalAction.modal('hide');
                    }

                    if(typeof searchEmployee === 'function') {
                        searchEmployee();
                    } else {
                        window.location.reload(true);
                    }
                }
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
 * actions configure
 */
jQuery(document).on('click', '.configure-delete', function (event) {
    configureDelete(event);
});

jQuery(document).on('click', '.configure-save', function (event) {
    saveEdite(event);
});

jQuery(document).on('click', '.configure-edit', function (event) {
    editShow(event);
});


/**
 * @param event
 */
var configureDelete = function(event){
    var id = jQuery(event.target).closest('.configure-buttons').data('id');
    var action = jQuery(event.target).closest('.configure-buttons').data('action');
    jQuery.post(action, {id : id}, function( data ) {
        jQuery(event.target).closest('tr').remove();
    })
}

jQuery('span').css('pointer-events', 'none');

/**
 * show edit configure
 */
var editShow = function(event){
    var row = jQuery(event.target).closest('tr');
    var value = row.find('.value-name').text();
    var html = '<input name="body" class="form-control configure-edit-field" value = "' + value + '">';

    row.find('.value-name').html(html);
    row.find('.configure-buttons').append('<a class="btn a-dashboard configure-save"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</a>&nbsp;');
    row.find('.configure-delete').hide();
    row.find('.configure-edit').hide();
}

/**
 *
 * @param event
 */
var saveEdite = function (event){
    var id = jQuery(event.target).closest('.configure-buttons').data('id');
    var url = jQuery(event.target).closest('.configure-buttons').data('action-save');
    var value = jQuery(event.target).closest('tr').find('.configure-edit-field').val();

    jQuery.post(url, {value : value, id: id}, function( data ) {
        Validate.redirect(data.redirect);

    })
}

/**
 * Show image in form employee
 * @param input
 */
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            jQuery('#image').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Event Show image in form employee
 */
jQuery(document).on('change',"#avatar_field", function(){
    readURL(this);
});

/**
 * Show files list selected files in form employee
 */

jQuery(document).on('change', ".attachments-input", function(){


    var files = jQuery("#attachments-input")[0].files;
    var html = '';
    for (var i = 0; i < files.length; i++)
    {
        html += '<div class="a-dashboard">' + files[i].name + '</div>';
    }

    jQuery('.upload-file-attach').html(html)
});

jQuery(document).on('change', "#attachments-input-show", function(){

    $( ".async" ).submit();
});


jQuery(document).on('click', '.attach-delete', function (event) {
    deleteFile(event);
});


/**
 * delete file
 * @param event
 */
var deleteFile = function(event){
    var id = jQuery(event.target).closest('a').data('id');
    var path = jQuery(event.target).closest('a').data('path');
    jQuery.post('/employee/file-remove', {id : id, path: path}, function( data ) {
        jQuery(event.target).closest('.file-container').remove();
    })
}


/**
 * set found in search reuest
 */
jQuery(document).on('click', '.disable-mail', function (event) {
    event.defaultPrevented = true;
    var id = jQuery(event.target).closest('a').data('id');
    jQuery.post('/dashboard/search-requests-set-found', {id : id, found: 1}, function( data ) {
        Validate.redirect(data.redirect);
    })
});

jQuery(document).on('click', '.enable-mail', function (event) {
    event.defaultPrevented = true;
    var id = jQuery(event.target).closest('a').data('id');
    jQuery.post('/dashboard/search-requests-set-found', {id : id, found: 0}, function( data ) {
        Validate.redirect(data.redirect);
    })
});

