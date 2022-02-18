(function( $ ) {
	'use strict';

    jQuery("#excerpt").focusin(function(){
        tinyMCE.triggerSave();
    });
    jQuery("#post_title, #post_type").focusout(function(){
        tinyMCE.triggerSave();
    });
	/*
    * Guest Post Ajax Fomm Handler
	**/
    jQuery("#guest_post_form").submit(function(event) {
        event.preventDefault();
        var $thisForm = jQuery(this);
        var data = {};
        var fieldName = '';
        var popError = false;
        $thisForm.find('span.error-message').remove();
        $thisForm.find(".error").removeClass();

        jQuery($thisForm).find('input, textarea, select').each(function(i, field) {
            data[field.name] = field.value;
            fieldName = field.name;
            fieldName = fieldName.replace(/_/gi, " ");
            fieldName = fieldName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });

            if (field.name == 'post_title' ) {
                if (field.value == '') {
                    $thisForm.find('input[name="' + field.name + '"]').addClass('error').after('<span class="error-message">Please fill in the required field.</span>');
                    if (popError == false)
                        popError = true;
                }
            } else if ( field.name == 'description' || field.name == 'excerpt' ) {
                if (field.value == '') {
                    $thisForm.find('textarea[name="' + field.name + '"]').addClass('error').after('<span class="error-message">Please fill in the required field.</span>');
                    if (popError == false)
                        popError = true;
                }
            } else if ( field.name == 'post_type' ) {
                if (field.value == '') {
                    $thisForm.find('select[name="' + field.name + '"]').addClass('error').after('<span class="error-message">Please select an option.</span>');
                    if (popError == false)
                        popError = true;
                }
            }
            else if ( field.name == 'feature_image' ) {
                if (field.value == '') {
                    $thisForm.find('input[name="' + field.name + '"]').addClass('error').after('<span class="error-message">Please upload a feature image.</span>');
                    if (popError == false)
                        popError = true;
                }
            }
        });

        if (popError == true) {
            $thisForm.addClass('invalid');
            jQuery(".error-message").show();
        } else {
            $thisForm.removeClass('invalid');
            jQuery(".error-message").hide();
            jQuery(".error").removeClass();
			
            var formdata = (window.FormData) ? new FormData($thisForm[0]) : null;			
            jQuery.ajax({
                type: 'POST',
                url: gpsp_params.ajaxurl,
                data: formdata,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $thisForm.find('.overlay-loader-blk').show();
                },
                success: function(data) {
                    if (data.result == 'fail') {
                        $thisForm.find('.ajax-message').html(data.message);
                        $thisForm.find('.overlay-loader-blk').hide();
                    } else {
                        $thisForm.find('.ajax-message').html(data.message);
                        $thisForm.trigger("reset");
                        $thisForm.find('.overlay-loader-blk').hide();
                    }
                },
                error: function() {
					$thisForm.find('.ajax-message').html("Error: There is some issue please try again.")
                }
            });
        }
    });
})( jQuery );
