/**
 * WP User Manager
 * http://wp-user-manager.com
 *
 * Copyright (c) 2015 Alessandro Tesoro
 * Licensed under the GPLv2+ license.
 */

jQuery(document).ready(function ($) {

	/**
	 * Frontend Scripts
	 */
	var wpaam_Admin = {

		init : function() {
			this.general();
			this.restore_emails();
			this.drag_and_drop_fields_table();
			this.confirm_dialog();
			this.admin_upload();
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

		// Ajax Function to restore emails
		restore_emails : function() {

			$('#wpaam-restore-emails').on('click', function(e) {

				e.preventDefault();

				if( confirm( wpaam_admin_js.confirm ) ) {

					var wpaam_nonce = $('#wpaam_backend_security').val();

					$.ajax({
						type: 'GET',
						dataType: 'json',
						url: wpaam_admin_js.ajax,
						data: {
							'action' : 'wpaam_restore_emails', // Calls the ajax action
							'wpaam_backend_security' : wpaam_nonce
						},
						beforeSend: function() {
							$( '.wpaam-spinner' ).remove();
							$( '.wpaam-ajax-done-message' ).remove();
							$( '#wpaam-restore-emails' ).after('<span id="wpaam-spinner" class="spinner wpaam-spinner is-active"></span>');
						},
						success: function(results) {
							$( '#wpaam-restore-emails' ).after( '<p class="wpaam-ajax-done-message"> <span class="dashicons dashicons-yes"></span> ' + results.data.message + '</p>' );
							$( '.wpaam-spinner' ).remove();
						},
						error: function(xhr, status, error) {
						    alert(xhr.responseText);
						}

					});

			    } else {

			        return false;

			    }

			});

		},

		// Ajax Function to restore emails
		remove_roles : function() {

			$('#wpaam-remove-roles').on('click', function(e) {

				e.preventDefault();

				if( confirm( wpaam_admin_js.confirm ) ) {

					var wpaam_nonce = $('#wpaam_user_roles_security').val();

					$.ajax({
						type: 'GET',
						dataType: 'json',
						url: wpaam_admin_js.ajax,
						data: {
							'action' : 'wpaam_remove_userroles', // Calls the ajax action
							'wpaam_user_roles_security' : wpaam_nonce
						},
						beforeSend: function() {
							$( '.wpaam-spinner' ).remove();
							$( '.wpaam-ajax-done-message' ).remove();
							$( '#wpaam-remove-roles' ).after('<span id="wpaam-spinner" class="spinner wpaam-spinner is-active"></span>');
						},
						success: function(results) {
							$( '#wpaam-remove-roles' ).after( '<p class="wpaam-ajax-done-message"> <span class="dashicons dashicons-yes"></span> ' + results.data.message + '</p>' );
							$( '.wpaam-spinner' ).remove();
						},
						error: function(xhr, status, error) {
						    alert(xhr.responseText);
						}

					});

			    } else {

			        return false;

			    }

			});

		},

		// Re-order the fields into the admin panel
		drag_and_drop_fields_table : function() {

			if ( $.isFunction($.fn.sortable) ) {

				$(".users_page_wpaam-profile-fields tbody").sortable({
					helper: this.sortable_table_fix,
					axis: "y",
					cursor: 'pointer',
					opacity: 0.5,
					placeholder: "row-dragging",
					delay: 150,
					handle: ".column-order, .move-field",
					update: function(event, ui) {

		        // Update TR data
						$(this).children('tr').each(function() {
							$(this).data('priority',$(this).index());
				    });

						// Prepare field data
						dataArray = $.map($(this).children('tr'), function(el){
							return { 'priority':$(el).data('priority'), 'field_id':$(el).data('field-id') };
						});

					  // Get nonce
					  var wpaam_editor_nonce = $('#wpaam_fields_editor_nonce').val();

		        $.ajax({
							type: 'POST',
							dataType: 'json',
							url: wpaam_admin_js.ajax,
							data: {
								'action' : 'wpaam_update_fields_order', // Calls the ajax action
								'items' : dataArray,
								'wpaam_editor_nonce': wpaam_editor_nonce
							},
							beforeSend: function() {
								wpaam_Admin.display_loader();
								wpaam_Admin.remove_message();
							},
							success: function(results) {
								// Update odd even table classes
								$('.users_page_wpaam-profile-fields').find("tr").removeClass('alternate');
								$('.users_page_wpaam-profile-fields').find("tr:even").addClass('alternate');
								// Hide loading indicator
								wpaam_Admin.hide_loader();
								// Show message
								wpaam_Admin.display_success_message( '.wpaam-page-title', results.data.message );
							},
							error: function(xhr, status, error) {
							    alert(xhr.responseText);
							}
						});

		      }

				}).disableSelection();
			}

		},

		// Adjust table width when dragging
		sortable_table_fix : function( e, tr ) {
			var $originals = tr.children();
		    var $helper = tr.clone();
		    $helper.children().each(function(index){
		      $(this).width($originals.eq(index).width())
		    });
		    return $helper;
		},

		// Display spinner
		display_loader : function() {

			// Set height of loader indicator the same as the
			// editor table.
			var table_height = $( '.wp-list-table' ).height();
			$('.wpaam-table-loader').css('display','table');
			$('.wpaam-table-loader').css('height', table_height );
			$('.wpaam-table-loader #wpaam-spinner').addClass('is-active');

		},
		// Hide the spinner
		hide_loader : function() {
			$('.wpaam-table-loader').hide();
			$('.wpaam-table-loader #wpaam-spinner').removeClass('is-active');
		},
		// Display a success message
		display_success_message : function( after, message, status, scroll ) {
			status = status || "updated";
			scroll = scroll || true;

			$( after ).after( '<div class="wpaam-message '+ status +' notice is-dismissible"><p>' + message + '</p></div>' );

			if( scroll ) {
				// scroll back
				$("html, body").animate({ scrollTop: 0 }, "slow");
  				return false;
			}

		},

		remove_message : function() {
			$( '.wpaam-message' ).remove();
		},

		// Ask to confirm before deleting field group
		confirm_dialog : function() {

			$('#wpaam-group-settings-edit a.submitdelete, .wpaam-confirm-dialog').click(function(e){
			    return confirm( wpaam_admin_js.confirm );
			})

		},

		// Handles files upload in admin panel
		admin_upload : function() {

			// Uploading files
			var file_frame;
			window.formfield = '';

			$( document.body ).on('click', '.wpaam_settings_upload_button', function(e) {

					e.preventDefault();

					var button = $(this);

					window.formfield = $(this).parent().prev();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
			    file_frame = wp.media.frames.file_frame = wp.media({
			      title: wpaam_admin_js.upload_title,
			      button: {
			        text: wpaam_admin_js.use_this_file,
			      },
			      multiple: false  // Set to true to allow multiple files to be selected
			    });

					// When an image is selected, run a callback.
			    file_frame.on( 'select', function() {
			      // We set multiple to false so only get one image from the uploader
			      attachment = file_frame.state().get('selection').first().toJSON();

						// Send file url to text field
						window.formfield.val( attachment.url );
			    });

			    // Finally, open the modal
			    file_frame.open();

			});

		}

	};

	wpaam_Admin.init();

	// Load dashboard widget via ajax
	if( jQuery( '#wpaam_dashboard_users' ).length ) {
		$.ajax({
			type: "GET",
			data: {
				action: 'wpaam_load_dashboard_users_overview'
			},
			url: wpaam_admin_js.ajax,
			success: function ( response ) {
				$('#wpaam_dashboard_users .inside').html( response );
			}
		});
	}

});
