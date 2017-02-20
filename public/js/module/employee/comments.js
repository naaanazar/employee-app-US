jQuery(document).on('submit', 'form.leave-comment', function (event) {

    event.defaultPrevented = true;

    var action = new AjaxAction($(this).attr('action'), $(this).serializeArray(), '#comments-list');
    action.execute();

    return false;

});