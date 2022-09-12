jQuery(document).ready(function() {
	jQuery('body').on('click', '.scpfw_open', function() {

		var loader_image = jQuery(this).data("image");

		jQuery('body').addClass("scpfw_body_sizechart");
		jQuery('body').append('<div class="scpfw_loading"><img src="'+ loader_image +'" class="scpfw_loader"></div>');
		var loading = jQuery('.scpfw_loading');
		loading.show();

        var product_id = jQuery(this).data("id");
        var chart_id = jQuery(this).data("cid");
        var current = jQuery(this);

        jQuery.ajax({
	        url:scpfw_object.scpfw_ajax_url,
            type:'POST',
	        data:'action=scpfw_sizechart&product_id='+product_id+'&chart_id='+chart_id,
	        success : function(response) {
	        	var loading = jQuery('.scpfw_loading');
				loading.remove();

	            jQuery("#scpfw_sizechart_popup").css("display", "block");
	            jQuery("#scpfw_sizechart_popup").html(response);
	            jQuery('#scpfw_schart_popup_cls').css("display", "block");	
	        },
	        error: function() {
	            alert('Error occured');
	        }
	    });
       return false;
    });

	jQuery(document).on('click','.scpfw_popup_close',function() {
		jQuery("#scpfw_sizechart_popup").css("display", "none");
		jQuery('#scpfw_schart_popup_cls').css("display", "none");
		jQuery('body').removeClass("scpfw_body_sizechart");
	});

	jQuery("body").on('click', '#scpfw_schart_popup_cls', function() {
    	jQuery('#scpfw_sizechart_popup').css("display", "none");
        jQuery('#scpfw_schart_popup_cls').css("display", "none");
        jQuery('body').removeClass("scpfw_body_sizechart");
    });


	//sizingpopup js start
	jQuery('body').on('click', '.scpfw_sidepoup_open', function() {

		var loader_image = jQuery(this).data("image");

		jQuery('body').addClass("scpfw_sdpp_body");
		jQuery('body').append('<div class="scpfw_loading"><img src="'+ loader_image +'" class="scpfw_loader"></div>');
		var loading = jQuery('.scpfw_loading');
		loading.show();

        var product_id = jQuery(this).data("id");
        var chart_id = jQuery(this).data("cid");
        var current = jQuery(this);
        
        jQuery.ajax({
	        url:scpfw_object.scpfw_ajax_url,
            type:'POST',
	        data:'action=scpfw_sdpp_sizechart&product_id='+product_id+'&chart_id='+chart_id,
	        success : function(response) {
	        	var loading = jQuery('.scpfw_loading');
				loading.remove();

	            jQuery(".scpfw_schart_sdpopup_main").addClass("active");
	            jQuery(".scpfw_schart_sidpp_overlay").addClass("active");
	            jQuery("#scpfw_schart_sidepopup").html(response);
				
	        },
	        error: function() {
	            alert('Error occured');
	        }
	    });

       return false;
    });


    //sizing close
    jQuery("body").on("click", ".scpfw_schart_sdpopup_close", function() {
    	jQuery(".scpfw_schart_sdpopup_main").removeClass("active");
      	jQuery(".scpfw_schart_sidpp_overlay").removeClass("active");
      	jQuery("body").removeClass("scpfw_sdpp_body");
    });

	jQuery("body").on("click", ".scpfw_schart_sidpp_overlay", function() {
		jQuery(".scpfw_schart_sdpopup_main").removeClass("active");
      	jQuery(".scpfw_schart_sidpp_overlay").removeClass("active");
      	jQuery("body").removeClass("scpfw_sdpp_body");
	});
  	//sizingpopup js end


	jQuery('body').on('click','ul.scpfw_front_tabs li', function() {
		var closesta = jQuery(this).closest(".scpfw_tableclass");
        var tab_id = jQuery(this).attr('data-tab');
        closesta.find('ul.scpfw_front_tabs li').removeClass('current');
        closesta.find('.scpfw_front_tab_content').removeClass('current');
        jQuery(this).addClass('current');
        closesta.find("#"+tab_id).addClass('current');
    })

    jQuery('body').on('click','ul.scpfw_sdpp_front_tabs li', function() {
		var closesta = jQuery(this).closest(".scpfw_sdpp_table");
        var tab_id = jQuery(this).attr('data-tab');
        closesta.find('ul.scpfw_sdpp_front_tabs li').removeClass('current');
        closesta.find('.scpfw_sdpp_frtab_content').removeClass('current');
        jQuery(this).addClass('current');
        closesta.find("#"+tab_id).addClass('current');
    })
})