jQuery(document).on('click', '.create-search-request', function (event) {
    event.defaultPrevented = true;

    var callback = function (response) {
        alert(response.message);
    };

    var params = {};

    $.each($('form.search-employees').serializeArray(), function () {
        params[this.name] = this.value;
    });

    var action = new AjaxAction(
        $(this).data('action'),
        {params: params},
        callback
    );
    action.execute();

    return false;
});



