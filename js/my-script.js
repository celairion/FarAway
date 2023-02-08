

jQuery(document).ready(function() {

	jQuery(".cancel_order").click(function (){

		var id = jQuery(this).attr('rel');
		jQuery("#cancel_order_div_id_" + id).toggle('slow');
		return false;
	});

});
