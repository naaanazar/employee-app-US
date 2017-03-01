jQuery('document').ready(function () {

    Sort.initForm('#filter-employee-form');
    Sort.initTable('#employee_table');
    Sort.eventSort('#employee_table', '#filter-employee-form');
});

var Sort = {

    initTable: function(tableId){
        jQuery(tableId + ' > thead > tr > th').each(function(){
            jQuery(this).html(jQuery(this).text() + '<span style="color:#c1c1c1; float: right;" class="glyphicon glyphicon-sort" aria-hidden="true"></span>');
        })
        jQuery(tableId + ' > thead > tr > th').css( "cursor", "pointer" );

        Sort.getStatus();
    },

    initForm: function(formId){

        var html = '<input type="hidden" class="" id="column-name" name="sort_name" value="">' +
            '<input type="hidden" class="" id="sort-order"  name="sort_order" value="">';

        jQuery(formId).append(html);

    },

    getStatus: function(){
        var columnName = jQuery('#column-name').val();
        var columnOrder = jQuery('#sort-order').val();

        if (columnName && columnOrder) {
            if (columnOrder === 'ASC') {
                Sort.addElementSortASC(jQuery("th[data-column-name='" + columnName + "']"));
            } else if (columnOrder === 'DESC') {
                Sort.addElementSortDESC(jQuery("th[data-column-name='" + columnName + "']"));
            }
        }
    },

    eventSort: function(tableId, formId) {
        jQuery(tableId).on('click', 'th', function (e) {

            var element = jQuery(e.target).closest('th');
            jQuery('#column-name').val(jQuery(element).data('column-name'));

            if (jQuery(element).hasClass('sorting_asc')){
                Sort.addElementSortDESC(element);
            } else if (jQuery(element).hasClass('sorting_desc') || (false === jQuery(element).hasClass('sorting_asc'))){
                Sort.addElementSortASC(element);
            }

            Sort.defaultTh(tableId, element);
            Sort.tableId = tableId;
            Sort.formId = formId;

            var action = new AjaxAction(jQuery(formId).attr('action'), jQuery(formId).serializeArray(), Sort.callback);
            action.execute();
        })
    },

    callback: function (data) {

        jQuery('document').ready(function () {
            Sort.initTable(Sort.tableId);
            Sort.eventSort(Sort.tableId, Sort.formId);
        });

        jQuery('#employees-list').html(data.html);
    },

    addElementSortDESC: function(element) {
        jQuery(element).removeClass('sorting_asc');
        jQuery(element).addClass('sorting_desc');
        jQuery('#sort-order').val('DESC');
        jQuery(element).html(jQuery(element).text() + '<span style="color:#c1c1c1; float: right;" class="glyphicon glyphicon-sort-by-attributes-alt span_no_event" aria-hidden="true"></span>')
    },

    addElementSortASC: function(element) {
        jQuery(element).removeClass('sorting_desc');
        jQuery(element).addClass('sorting_asc');
        jQuery('#sort-order').val('ASC');
        jQuery(element).html(jQuery(element).text() + '<span style="color:#c1c1c1; float: right;" class="glyphicon glyphicon-sort-by-attributes span_no_event" aria-hidden="true"></span>')
    },

    defaultTh: function(tableId, element) {
        jQuery(tableId + ' > thead > tr > th').each(function(){
            if ($( "th" ).index( this ) !== $( "th" ).index( element )){
                jQuery(this).removeClass('sorting_asc');
                jQuery(this).removeClass('sorting_desc');
                jQuery(this).html(jQuery(this).text() + '<span style="color:#c1c1c1; float: right;" class="glyphicon glyphicon-sort span_no_event" aria-hidden="true"></span>');
            }
        })
    }

};
