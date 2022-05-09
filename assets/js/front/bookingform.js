jQuery(document).ready(function($){
	$('#alexproperty_booking_submit').on('click',function(e){
		e.preventDefault();
		$.ajax({
			url: alexproperty_bookingform_var.ajaxurl,
			type: 'post',
			data: {
				action: 'booking_form',
				nonce: alexproperty_bookingform_var.nonce,
				name: $('#alexproperty_name').val(),
				email: $('#alexproperty_email').val(),
				phone: $('#alexproperty_phone').val(),
				price: $('#alexproperty_price').val(),
				location: $('#alexproperty_location').val(),
				agent: $('#alexproperty_agent').val(),
				
			},
			success: function(data){
				$('#alexproperty_result').html(data);
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
	});
});