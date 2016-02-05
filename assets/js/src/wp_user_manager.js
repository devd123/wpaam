/**
 * WP User Manager
 * http://wp-user-manager.com
 *
 * Copyright (c) 2015 Alessandro Tesoro
 * Licensed under the GPLv2+ license.
 */

jQuery(document).ready(function ($) {

	/**
	 *  Get current page url
	 */
	var wpaam_location = $( location );

	/**
	 * Frontend Scripts
	 */
	var wpaam_Frontend = {

		init : function() {
			this.ajax_remove_file();
			this.directory_sort();
		},

		// Check password strenght function
		checkPasswordStrength : function( $pass1, $strengthResult, $submitButton, blacklistArray ) {

	      var pass1 = $pass1.val();

	    	// Reset the form & meter
	      $strengthResult.removeClass( 'short bad good strong' );

		    // Extend our blacklist array with those from the inputs & site data
		    blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputBlacklist() )

		    // Get the password strength
		    var strength = wp.passwordStrength.meter( pass1, blacklistArray );

		    // Add the strength meter results
		    switch ( strength ) {

		        case 2:
		            $strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
		            break;

		        case 3:
		            $strengthResult.addClass( 'good' ).html( pwsL10n.good );
		            break;

		        case 4:
		            $strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
		            break;

		        case 5:
		            $strengthResult.addClass( 'short' ).html( pwsL10n.mismatch );
		            break;

		        default:
		            $strengthResult.addClass( 'short' ).html( pwsL10n.short );

		    }

		    return strength;

		},

		// Process removal of the user avatar
		ajax_remove_file : function() {

			$('a.wpaam-remove-uploaded-file').on('click', function(e) {

				e.preventDefault();
				var wpaam_removal_button = this; // form element
				var wpaam_removal_nonce  = $( '.wpaam-profile-form' ).find('#_wpnonce').val();
				var wpaam_field_id = $( wpaam_removal_button ).data("remove");
				var wpaam_submitted_form = $( '.wpaam-profile-form' ).find("[name='wpaam_submit_form']").val();

				console.log( wpaam_submitted_form );

				$.ajax({
					type: 'GET',
					dataType: 'json',
					url: wpaam_frontend_js.ajax,
					data: {
						'action' : 'wpaam_remove_file', // Calls the ajax action
						'wpaam_removal_nonce' : wpaam_removal_nonce,
						'field_id' : wpaam_field_id,
						'submitted_form' : wpaam_submitted_form
					},
					beforeSend: function() {
						$( wpaam_removal_button ).find('div.wpaam-message').remove();
						$( wpaam_removal_button ).before('<div class="wpaam-message notice"><p class="the-message">' + wpaam_frontend_js.checking_credentials + '</p></div>');
					},
					success: function(results) {

						// Check the response
						if( results.data.valid === true ) {
							$( wpaam_removal_button ).prev('div').prev().remove();
							$( '#wpaam-form-profile' ).find('div.wpaam-message').removeClass('notice').addClass('success').children('p').text(results.data.message);
							location.reload(true);
						} else {
							$( '#wpaam-form-profile' ).find('div.wpaam-message').removeClass('notice').addClass('error').children('p').text(results.data.message);
						}

					},
					error: function(xhr, status, error) {
					    alert(xhr.responseText);
					}
				});


			});

		},

		// User directory sort function
		directory_sort : function() {

			jQuery("#wpaam-dropdown, #wpaam-amount-dropdown").change(function () {
		        location.href = jQuery(this).val();
		    });

		}

	};

	wpaam_Frontend.init();

	/**
	 * Remove query arguments from pages to prevent multiple message to appear.
	 */
	window.wpaam_removeArguments = function() {
	    function removeParam(key, sourceURL) {
	        var rtn = sourceURL.split("?")[0],
	            param, params_arr = [],
	            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
	        if (queryString !== "") {
	            params_arr = queryString.split("&");
	            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
	                param = params_arr[i].split("=")[0];
	                if ($.inArray(param, key) > -1) {
	                    params_arr.splice(i, 1);
	                }
	            }
	            rtn = rtn + "?" + params_arr.join("&");
	        }
	        return rtn;
	    }

	    var remove_query_args = ['updated'];

	    url = wpaam_location.attr('href');
	    url = removeParam(remove_query_args, url);

	    if (typeof history.replaceState === 'function') {
	        history.replaceState({}, '', url);
	    }
	};

 	// Run the above script only on plugin's pages
 	if( jQuery( 'body' ).hasClass('wpaam-account-page') ) {
 		window.wpaam_removeArguments();
 	}

	// Run pwd meter if enabled
	if( wpaam_frontend_js.pwd_meter == 1 ) {
		$( 'body' ).on( 'keyup', 'input[name=password]',
	        function( event ) {
	            wpaam_Frontend.checkPasswordStrength(
	                $('.wpaam-registration-form-wrapper input[name=password], .wpaam-profile-form-wrapper input[name=password], .wpaam-update-password-form-wrapper input[name=password], .wpaam-password-form input[name=password]'),         // First password field
	                $('.wpaam-registration-form-wrapper #password-strength, .wpaam-profile-form-wrapper #password-strength, .wpaam-update-password-form-wrapper #password-strength, .wpaam-password-form #password-strength'),           // Strength meter
	                $('#submit_wpaam_register, #submit_wpaam_profile, #submit_wpaam_password'),           // Submit button
	                ['admin', 'administrator', 'test', 'user', 'demo']        // Blacklisted words
	            );
	        }
	    );
	}

});
