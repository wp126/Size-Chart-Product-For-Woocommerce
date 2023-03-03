//Jquery Tabs
jQuery(document).ready(function() {
    jQuery('body').on('click','.addcolumn',function() {

        var td = jQuery(this).closest('td');
        var indexa = td.index();
        jQuery('.scpfw_chart_tbl tr:first td:nth-child('+(indexa+1)+')').after('<td><a class="addcolumn"><img src= " '+ scpfw_object.scpfw_object_name + '/assets/images/plus.png"></a><a class="deletecolumn"><img src= " '+ scpfw_object.scpfw_object_name + '/assets/images/delete.png"></a></td>');

        jQuery(".scpfw_chart_tbl tr").not(':first').each(function(index) {
            jQuery(this).find('td:nth-child('+(indexa+1)+')').after("<td><input type='text' name='size_chartdata[]'></td>");     
        });
        var total_row = count_row();
        var total_column = count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
    });

    jQuery('body').on('click','.addrow',function() {
    	var total_column = count_col();
        let row = jQuery('<tr></tr>');
        var column = "";
        for (col = 1; col <= total_column; col++) {
            column += '<td><input type="text" name="size_chartdata[]"></td>';
        }
        column += '<td><a class="addrow"><img src= " '+ scpfw_object.scpfw_object_name + '/assets/images/plus.png"></a><a class="deleterow"><img src= " '+ scpfw_object.scpfw_object_name + '/assets/images/delete.png"></a></td>';
        row.append(column);
        jQuery(this).closest('tr').after(row);
        var total_row = count_row();
        var total_column = count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
    });

    function count_col() {
    	var colCount = 0;
	    jQuery('.scpfw_chart_tbl tr:nth-child(1) td').each(function () {
	       	colCount++;
	    });
	    return colCount - 1;
    }

    function count_row() {
    	var rowCount = jQuery('.scpfw_chart_tbl tr').length;
    	return rowCount - 1;
    }

    jQuery("body").on('click', '.deletecolumn', function() {
        var td = jQuery(this).closest('td');
        var indexa = td.index();

        jQuery(this).closest('table').find('tr').each(function() {
            this.removeChild(this.cells[ indexa ]);
        });

        var total_row = count_row();
        var total_column = count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
        return false;
    });

    jQuery("body").on('click', '.deleterow', function() {
        jQuery(this).parent().parent().remove();
        var total_row = count_row();
        var total_column = count_col();
        jQuery('input[name="totalrow"]').val(total_row);
        jQuery('input[name="totalcol"]').val(total_column);
        return false;
    });

    jQuery('input[name="btn_show"]').change(function() {
        var value = jQuery( 'input[name="btn_show"]:checked' ).val();
        if (value == "popup") {
            jQuery('.scpfw_popup_div').css('display','block');
            jQuery('.scpfw_tab_div').css('display','none');
        }else if (value == "sidepopup") {
            jQuery('.scpfw_popup_div').css('display','block');
            jQuery('.scpfw_tab_div').css('display','none');
        }else if (value == "tab") {
            jQuery('.scpfw_tab_div').css('display','block');
            jQuery('.scpfw_popup_div').css('display','none');
        }
    });

    var value = jQuery( 'input[name="btn_show"]:checked' ).val();
    if(value == "popup") {
        jQuery('.scpfw_popup_div').css('display','block');
        jQuery('.scpfw_tab_div').css('display','none');
    } else if (value == "sidepopup") {
        jQuery('.scpfw_popup_div').css('display','block');
        jQuery('.scpfw_tab_div').css('display','none');
    } else if (value == "tab") {
        jQuery('.scpfw_tab_div').css('display','block');
        jQuery('.scpfw_popup_div').css('display','none');
    }

    jQuery('#scpfw_selectchart').select2({
        ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 200,
                allowClear: true,
                data: function (params) {
                    
                    return {
                        q: params.term,
                        action: 'scpfw_search_chart'

                    };
                    
                },
                processResults: function( data ) {
                    var options = [];
                    if ( data ) {
                        jQuery.each( data, function( index, text ) { 
                            options.push( { id: text[0], text: text[1]} );
                        });
                    }
                    return {
                        results: options
                    };
                },
                cache: true
        },
        minimumInputLength: 1
    });

    jQuery('#asprods_select2_posts').select2({
        ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        action: 'SCPFW_asprods_get_posts'
                    };
                },
                processResults: function( data ) {
                var options = [];
                if ( data ) {
 
                    jQuery.each( data, function( index, text ) {
                        options.push( { id: text[0], text: text[1]  } );
                    });
 
                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    jQuery('#asprodcats_select2_posts').select2({
        ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        action: 'SCPFW_asprodcats_get_posts'
                    };
                },
                processResults: function( data ) {
                var options = [];
                if ( data ) {
 
                    jQuery.each( data, function( index, text ) {
                        options.push( { id: text[0], text: text[1]  } );
                    });
 
                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    jQuery('#asprodattrs_select2_posts').select2({
        ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        action: 'SCPFW_asprodattrs_get_posts'
                    };
                },
                processResults: function( data ) {
                var options = [];
                if ( data ) {
 
                    jQuery.each( data, function( index, text ) {
                        options.push( { id: text[0], text: text[1]  } );
                    });
 
                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

})


/* defult js */
//jquery tab
jQuery(document).ready(function(){

    jQuery('ul.nav-tab-wrapper li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('ul.nav-tab-wrapper li').removeClass('nav-tab-active');
        jQuery('.tab-content').removeClass('current');
        jQuery(this).addClass('nav-tab-active');
        jQuery("#"+tab_id).addClass('current');
    });
});