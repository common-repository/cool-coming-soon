jQuery(function($){
	/*
	 * Select/Upload logo event
	 */
	$('body').on('click', '.ccs_logo_upload_button', function(e){
		e.preventDefault();
 
    		var button = $(this),
    		    custom_uploader = wp.media({
			title: 'Upload or Choose Your Logo',
			library : {
				type : 'image'
			},
			button: {
				text: 'Use this image'
			},
			multiple: false
		}).on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$(button).removeClass('button').html('<img id="ccs_logo_image" src="' + attachment.url + '" />').next().val(attachment.id);
			$('#CCSInfoForm .ccs_change_logo_button').show();
			$('#CCSInfoForm .ccs_remove_logo_button').show();
			$('#CCSInfoForm #logo').val( attachment.url );
			$('#CCSInfoForm #logo_id').val( attachment.id );
		})
		.open();
	});
 
	/*
	 * Remove logo event
	 */
	$('body').on('click', '.ccs_remove_logo_button', function(){
		$(this).hide();
		$(this).prev().hide();
		$('#CCSInfoForm #ccs_logo_image').attr('src', '').parent().addClass('button').html('Upload Logo');
		$('#CCSInfoForm #logo').val( '' );
		return false;
	});

	/*
	 * Change logo event
	 */
	$('body').on('click', '.ccs_change_logo_button', function(){
		$('#CCSInfoForm .ccs_logo_upload_button').trigger('click');
		return false;
	});

	/*
	 * update logo if changed by url
	 */
	$('#CCSInfoForm #logo').focusout(update_logo_image);
	$('#CCSInfoForm #logo').mouseleave(update_logo_image);

	function update_logo_image() {
		let logo_url = $('#CCSInfoForm #logo').val();
		let current_logo = $('#CCSInfoForm .ccs_logo_upload_button img').attr( 'src');

		if(logo_url != '' && current_logo != logo_url){
			$('#CCSInfoForm .ccs_logo_upload_button').html( '<img id="ccs_logo_image" src="'+logo_url+'" />' );
			$('#CCSInfoForm .ccs_change_logo_button').show();
			$('#CCSInfoForm .ccs_remove_logo_button').show();
			$('#CCSInfoForm #logo_id').val( -1 );
		}
		
		return false;
	}

	$('#CCSInfoForm #bg-options').change( update_background_image );
	$('#CCSInfoForm #background').focusout( update_background_image );
	$('#CCSInfoForm #background').mouseleave( update_background_image );

	function update_background_image() {

		let path = $('#CCSInfoForm #background_url').val();
		let img = $('#CCSInfoForm #bg-options').val();

		let new_bg_image = path + img;
		let custom_background = $('#CCSInfoForm #background').val();
		let current_bg = $('#CCSInfoForm #ccs_background_image').attr('src');

		if(current_bg != new_bg_image){
			$('#CCSInfoForm #ccs_background_image').attr('src', new_bg_image);
		}

		if(custom_background != '' && current_bg != custom_background){
			$('#CCSInfoForm #ccs_background_image').attr('src', custom_background);
		}
		

	}
	
	
 
});

