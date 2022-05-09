jQuery(document).ready(function($){
	
	$('.alexproperty_add_to_wishlist').on('click',function(e){
		e.preventDefault();
		var id = $(this).data('property-id');
		
		var alexproperty_add_to_wishlist = {
			success: function(){
				$('#post-'+ id +'.alexproperty_add_to_wishlist').hide(0,function(){
					$('#post-'+ id +'.succesfull_added').delay(200).show();
				});
			}
		}
		
		$('#alexproperty_add_to_wishlist_form_'+id).ajaxSubmit(alexproperty_add_to_wishlist);
	});
	
	$('.alexproperty_remove_property').on('click',function(e){
		e.preventDefault();
		
		var id = $(this).data('property-id');
		
		$.ajax({
			url: $(this).attr('href'),
			type: "POST",
			data: {
				alex_property_id: $(this).data('property-id'),
				alex_user_id: $(this).data('user-id'),
				action: "alexproperty_remove_wishlist",
			},
			dataType: "html",
			success: function(result){
				$('#post-' + id).hide();
			}
		});
		
	});
});