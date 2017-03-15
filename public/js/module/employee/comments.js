jQuery(document).on('submit', 'form.leave-comment', function (event) {

    event.defaultPrevented = true;

    var action = new AjaxAction($(this).attr('action'), $(this).serializeArray(), '#comments-list');
    action.execute();

    return false;
});

/**
 * delete comment
 */
jQuery(document).on('click', '.comment-delete', function (event) {
    var id = jQuery(event.target).closest('.comment-block').data('id');
    var action = jQuery(event.target).closest('.comment-buttons').data('action-delete');

    jQuery.post( action, {id : id}, function( data ) {
        jQuery(event.target).closest('.comment-block').remove();
    })
});

/**
 * show field edit comment
 */
jQuery(document).on('click', '.comment-edit-save', function (event) {
    var id = jQuery(event.target).closest('.comment-block').data('id');
    var commentText = jQuery(event.target).closest('.comment-block').find('.comment-edit-field').val();
    var action = jQuery(event.target).closest('a').data('action-save');
    jQuery(event.target).closest('.comment-block').find('.comment-buttons').show();

    jQuery.post(action, {body : commentText, id: id}, function( data ) {
        jQuery(event.target).closest('.comment-block').find('.comment-body').html(commentText);
    })
});

/**
 * save edit comment
 */
jQuery(document).on('click', '.comment-edit', function (event) {
    var commentText = jQuery(event.target).closest('.comment-block').find('.comment-body').text();
    var action = jQuery(event.target).closest('.comment-buttons').data('action-edit');
    console.log(action);

    jQuery.post(action, {body : commentText}, function( data ) {
        jQuery(event.target).closest('.comment-block').find('.comment-body').html(data.html);
        jQuery(event.target).closest('.comment-buttons').hide();
    })
});

jQuery(".attachments-input").change(function(){

    var files = jQuery(".attachments-input")[0].files;
    var html = '';
    for (var i = 0; i < files.length; i++)
    {
        html += '<div class="a-dashboard">' + files[i].name + '</div>';
    }

    jQuery('.upload-file-attach').html(html)
});
