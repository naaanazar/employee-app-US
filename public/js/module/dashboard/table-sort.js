
jQuery('document').ready(function () {

    Sort.initSort('#overview_table');
    Sort.eventSort('#overview_table', '#filter_overview');

});

var formSubmit = {



}

var sessionStorageFormData = {
    setData: function(formId, storageValueName){
        var data = {}
        $.each(jQuery(formId).serializeArray(),
            function(i, v) {
                data[v.name] = v.value;
            });

        sessionStorage.setItem(storageValueName, JSON.stringify(data));
    },

    getData: function(storageValueName){

        return JSON.parse(sessionStorage.getItem(storageValueName));
    }
}

var Sort = {

    initSort: function(tableId){
        jQuery(tableId + ' > thead > tr > th').each(function(){
            jQuery(this).html(jQuery(this).text() + '<span style="color:#c1c1c1;" class="glyphicon glyphicon-sort span_no_event" aria-hidden="true"></span>');
        })

        var formData = sessionStorageFormData.getData('filterData');

        jQuery.each(formData, function(prop, val) {
            $( "input[name='" + prop + "']" ).val(val);
           console.log(prop + '--' + val);
        });
        console.log(formData);
    },

    eventSort: function(tableId, formId) {
        jQuery(tableId).on('click', 'th', function (e) {

            var element = jQuery(e.target);
            jQuery('#sort-column-name').val(jQuery(element).data('column-name'));

            if (jQuery(element).hasClass('sorting_asc')){
                Sort.addElementSortDESC(element);
            } else if (jQuery(element).hasClass('sorting_desc') || (false === jQuery(element).hasClass('sorting_asc'))){
                Sort.addElementSortASC(element);
            }

            Sort.defaultTh(tableId, element);

            sessionStorageFormData.setData(formId, 'filterData');

           /* setTimeout(function(){
                $(formId).submit();
            },3000)*/
        })
    },

    addElementSortDESC: function(element) {
        jQuery(element).removeClass('sorting_asc');
        jQuery(element).addClass('sorting_desc');
        jQuery('#sort-order').val('DESC');
        jQuery(element).html(jQuery(element).text() + '<span style="color:#c1c1c1;" class="glyphicon glyphicon-sort-by-attributes-alt span_no_event" aria-hidden="true"></span>')
    },

    addElementSortASC: function(element) {
        jQuery(element).removeClass('sorting_desc');
        jQuery(element).addClass('sorting_asc');
        jQuery('#sort-order').val('ASC');
        jQuery(element).html(jQuery(element).text() + '<span style="color:#c1c1c1;" class="glyphicon glyphicon-sort-by-attributes span_no_event" aria-hidden="true"></span>')
    },

    defaultTh: function(tableId, element) {
        jQuery(tableId + ' > thead > tr > th').each(function(){
            if ($( "th" ).index( this ) !== $( "th" ).index( element )){
                jQuery(this).removeClass('sorting_asc');
                jQuery(this).removeClass('sorting_desc');
                jQuery(this).html(jQuery(this).text() + '<span style="color:#c1c1c1;" class="glyphicon glyphicon-sort span_no_event" aria-hidden="true"></span>');
            }
        })
    }

};
