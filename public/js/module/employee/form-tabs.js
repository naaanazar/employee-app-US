
jQuery(document).on('click', '.step1-next', function(e){
    e.preventDefault();
    ajaxFormSubmitByClass('.form-step1', stepNext, 'step_2');

})

var stepNext = function(error, step){
    if(error === true) {
        setActivTab(step);
    }
};


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


/*jQuery(document).on('submit', 'form.async', function (event) {
    ajaxFormSubmit(event, callback);
    return false;
});*/

jQuery(document).on('click', '.step2-next', function(){
    setActivTab('step_3');
})

jQuery(document).on('click', '.step3-next', function(){
    setActivTab('step_4');
})

jQuery(document).on('click', '.step4-next', function(){
    setActivTab('step_5');
})




jQuery(document).on('click', '.step2-prev', function(){
    setActivTab('step_1');
})

jQuery(document).on('click', '.step3-prev', function(){
    setActivTab('step_2');
})

jQuery(document).on('click', '.step4-prev', function(){
    setActivTab('step_3');
})

jQuery(document).on('click', '.step5-prev', function(){
    setActivTab('step_4');
})



/**
 * show tab
 * @param tab
 */
function setActivTab(tab){
    jQuery('.nav-tabs a[href="#' + tab + '"]').tab('show');
};
