/*! WP User Manager - v1.1.0
 * http://wpusermanager.com
 * Copyright (c) 2015; * Licensed GPLv2+ */
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
			this.general();
			this.ajax_remove_file();
			this.directory_sort();
			this.aam_user_product();
			
			
		},

		// General Functions
		general : function() {

			if ( $.isFunction($.fn.select2) ) {

				jQuery("select.select2").select2({
					width: 'resolve'
				});

				jQuery(".wppf-multiselect, select.select2_multiselect").select2();

			}

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

		},

	

		// get the product for aam user's
		aam_user_product : function () {
			
			
			jQuery("#search_product").keyup(function (){
				var search_product = $(this).val();
				$.ajax({
					type: 'POST',
					url: wpaam_frontend_js.ajax,
					data: {
						'action' : 'wpaam_get_autocomplete_product', // Calls the ajax action
						'keyword' : search_product
					},	
					beforeSend: function(){
						$("#search_product").css("background","#FFF");
					},
					success: function(data){
						$("#suggesstion-box").show();
						$("#suggesstion-box").html(data);
						$("#search_product").css("background","#FFF");
					}
				});

			});

		},

		// get the product for aam user's
		// mutliple_product_input : function () {
		
		//   	var maxField = 10; //Input fields increment limitation
		//     var addButton = $('.add_button'); //Add button selector
		//     var wrapper = $('.field_wrapper'); //Input field wrapper
		//     var fieldHTML = '<div><input type="text" name="multi_products[]" id="search_product" value=""/><a href="javascript:void(0);" class="remove_button" title="Remove field"><img src="remove-icon.png"/></a><div id="suggesstion-box"></div></div>'; //New input field html 
		//     var x = 1; //Initial field counter is 1
		//     $(addButton).click(function(){ //Once add button is clicked
		//         if(x < maxField){ //Check maximum number of input fields
		//             x++; //Increment field counter
		//             $(wrapper).append(fieldHTML); // Add field html
		//         }
		//     });
		//     $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
		//         e.preventDefault();
		//         $(this).parent('div').remove(); //Remove field html
		//         x--; //Decrement field counter
		//     });


		// }



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

	// Product autocomplete filter suggestion
	jQuery(function() {

		jQuery(document).on('keyup' , '.select_product' , function (){
			//var search_term = $(this).val();
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: wpaam_frontend_js.ajax,
				data: {
					'action' : 'wpaam_get_product_by_aamuser', // Calls the ajax action
					//'keyword' : search_term
				},	
				success: function(res){
					var availableTags = res;
					
					function split( val ) {
				      return val.split( /,\s*/ );
				    }
				    function extractLast( term ) {
				      return split( term ).pop();
				    }
				 
					    jQuery( "#multi_products" )
					      // don't navigate away from the field on tab when selecting an item
					      .bind( "keydown", function( event ) {
					        if ( event.keyCode === $.ui.keyCode.TAB &&
					            $( this ).autocomplete( "instance" ).menu.active ) {
					          event.preventDefault();
					        }
				      	})
				      	.autocomplete({
					        minLength: 0,
					        source: function( request, response ) {
					          // delegate back to autocomplete, but extract the last term
					          response( jQuery.ui.autocomplete.filter(
					            availableTags, extractLast( request.term ) ) );
					        },
					        focus: function() {
					          // prevent value inserted on focus
					          return false;
					        },
					        select: function( event, ui ) {
					          var terms = split( this.value );
					          // remove the current input
					          terms.pop();
					          // add the selected item
					          terms.push( ui.item.value );
					          // add placeholder to get the comma-and-space at the end
					          terms.push( "" );
					          this.value = terms.join( ", " );
					          return false;
					        }
				      	});
			
				}
			});

		});
	});

	// Date Picker Function
	jQuery( function() {
    	jQuery( "#datepicker" ).datepicker();
  	});

	// Delete aam product by ajax call 
	$(document).on( 'click', '.delete-product', function() {
		if(confirm("Are you sure you want to delete this?")){
			var id = $(this).data('id');
			var nonce = $(this).data('nonce');
			var post = $(this).parents('.post:first');
			
			
			$.ajax({
				type: 'post',
				url: wpaam_frontend_js.ajax,
				data: {
					action: 'wpaam_my_delete_post',
					nonce: nonce,
					id: id
				},
				success: function( result ) {

					if( result == 'success' ) {
						post.fadeOut( function(){
							post.remove();
							
						});
						location.reload();
					}
				}
			});
			return false;
		}else
			return false;
	});

	// Quotation Preview ajax callback 
	$(document).on( 'click', '.qt_preview', function() {
		
			var id = $(this).attr('qid');
			$.ajax({
				type: 'post',
				url: wpaam_frontend_js.ajax,
				data: {
					action: 'wpaam_quotation_preview',
					id: id
				},
				success: function( result ) {

					$("#dialog_box").html(result);
					$( "#dialog_box" ).dialog();
				}
			});
			return false;
		
	}); 

	// quotatoin copy to invoice ajax callback 
	$(document).on( 'click', '#quotation_copy', function() {
		
			var qtid = $(this).data('id');
			$.ajax({
				type: 'post',
				url: wpaam_frontend_js.ajax,
				data: {
					action: 'wpaam_quotation_copy_invoice',
					qtid  : qtid
				},
				success: function( res , error ) {
					if(res){
						alert("you have successfully create copy of quotation to invoice");
						location.reload();	
					}else
						return false;
				}
			});
			return false;
		
	});

	// Invoice Preview ajax callback 
	$(document).on( 'click', '.inv_preview', function() {
		
			var id = $(this).attr('invid');
			$.ajax({
				type: 'post',
				url: wpaam_frontend_js.ajax,
				data: {
					action: 'wpaam_invoice_preview',
					id: id
				},
				success: function( result ) {

					$("#invoice_box").html(result);
					$( "#invoice_box" ).dialog();
				}
			});
			return false;
		
	});
	

// ended of document.root    
});

//To select product name from auto search list
function selectProduct(product_name) {
//console.log(product_name);
jQuery("#search_product").val(product_name);
jQuery("#suggesstion-box").hide();

	jQuery.ajax({
		type: 'POST',
		url: wpaam_frontend_js.ajax,
		data: {
			'action' : 'wpaam_get_product_price', // Calls the ajax action
			'product_name' : product_name
		},	
		success: function(data){
			jQuery("#quotation_price").val(data);
		}
	});
}



