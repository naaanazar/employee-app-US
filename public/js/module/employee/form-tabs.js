/**
 * STEP1
 */
jQuery(document).on('click', '.step1-next', function(e){
    e.preventDefault();
    ajaxFormSubmitByClass('.form-step1', stepNext, 'step_2');

})

/**
 * STEP2
 */
jQuery(document).on('click', '.step2-next', function(e){
    e.preventDefault();
    promise = jQuery.Deferred();

    if ('' === jQuery('#latitude').val() || '' === jQuery('#longitude').val()) {
        Address.setAddress();

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': Address.fullAddress}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                document.getElementById('latitude').value = results[0].geometry.location.lat();
                document.getElementById('longitude').value = results[0].geometry.location.lng();
                promise.resolve('ok');
            } else {
                alert('Address is not valid or GOOGLE Maps API returns bad response');
            }
        });
    } else {
        promise.resolve('ok');
    }

    jQuery.when(promise).then(
        function () {
            ajaxFormSubmitByClass('.form-step2', stepNext, 'step_3');
        }
    ).then(
        function () {
            jQuery('#latitude').val('');
            jQuery('#longitude').val('');
        }
    );
})

jQuery(document).on('click', '.step3-next', function(e){
    e.preventDefault();
    ajaxFormSubmitByClass('.form-step3', stepNext, 'step_4');

})

jQuery(document).on('click', '.form-ex-add', function(e){
    e.preventDefault();
    ajaxFormSubmitByClass('.form-step4');
})



jQuery(document).on('click', '.step5', function(e){
    e.preventDefault();
    ajaxFormSubmitByClass('.form-step5');
})




jQuery(document).on('click', '.step2-prev', function(e){
    setActivTab('step_1');
})

jQuery(document).on('click', '.step3-prev', function(e){
    setActivTab('step_2');
})

jQuery(document).on('click', '.step4-prev', function(e){
    setActivTab('step_3');
})

jQuery(document).on('click', '.step5-prev', function(){
    setActivTab('step_4');
})



/**
 * show tab
 * @param tab
 */
var setActivTab = function(tab){
    jQuery('.nav-tabs a[href="#' + tab + '"]').tab('show');
};

/**
 *
 * @param error
 * @param step
 */
var stepNext = function(error, step){
    if(error === true) {
        setActivTab(step);
    }
};

/**
 *
 * @param element
 * @param callback
 * @param step
 */
var ajaxFormSubmitByClass = function (element, callback, step) {


    var form  = jQuery(element);
    console.log(form);
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

            if (file instanceof File && file.type.match('.*')) {
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
                if (callback !== undefined) {
                    callback(response.errors, step);
                }

                Validate.redirect(response.redirect);

            }
        }
    );
};
