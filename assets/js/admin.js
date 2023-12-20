jQuery(document).ready(function($) {

	// Tabs for admin page
	$( "#tabs" ).tabs({
		active: $('#LastActiveTab').val(),
		activate: function( event, ui ) {
		$('#LastActiveTab').val($( "#tabs" ).tabs( "option", "active" ));
		}
	});

	// Shotcode generate - media upload
	$( 'body' ).on( 'click', '.wp-slide-upload', function( event ){
		event.preventDefault();
		
		const button = $(this);
		
		const customUploader = wp.media({
			title: 'Insert image', // modal window title
			library : {
				type : 'image',
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: true
		}).on( 'select', function() { // it also has "open" and "close" events
			const attachments = customUploader.state().get( 'selection' ).toJSON();
			const option = button.data('option');
			$(attachments).each(function(index, attachment) {
				//console.log(attachment);
				$( '.wp-slide-grid' ).append( '<div class="wp-slide-grid-item"><img src="' + 
					attachment.url + '"><input type="hidden" name="'+ 
					option +'[wp_slide_imgs][]" value="'+ 
					attachment.id +'"><span class="wp-slide-remove ui-icon ui-icon-closethick"></span></div>');
			});
			$('#submit').focus();
		})

		customUploader.open();
	
	});

	// on remove button click
	$( 'body' ).on( 'click', '.wp-slide-remove', function( event ){
		event.preventDefault();
		$(this).parent('.wp-slide-grid-item').fadeOut("normal", function() {
			$(this).remove();
			$('#submit').focus();
		});
	});

	// jQuery UI Sortable
	$('.wp-slide-grid').sortable();
});