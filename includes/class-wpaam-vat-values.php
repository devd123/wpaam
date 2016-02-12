<?php
/**
 * WPAAM: Vat Settings page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * wpaam_Form_Password Class
 *
 * @since 1.0.0
 */

class WPAAM_Vat_Values  
{

	
	
	public function __construct() {


	}

	// Add vat values form for admin settings
	public function add_vat_values() {
	if(isset($_POST['vat_submit']) && $_POST['vat_submit'] != ''  && $_POST["vat_name"] != '' && $_POST["vat_value"] != '' ){
		global $wpdb;
		$user_id = get_current_user_id();
		$vat_name = $_POST["vat_name"];
        $vat_value = $_POST["vat_value"];

	  	$vat_table = $wpdb->prefix."wpaam_vat_values";
	        //error with the query 
	        $sql = "INSERT INTO $vat_table (user_id , vat_name, vat_value) VALUES ('$user_id', '$vat_name', '$vat_value')";

	               if($wpdb->query($sql)) 
	               {
	              		echo "you have successfully added vat values";
	               }
	}else {
		echo "please enter the required values";
	} ?>
	
	<div class="wrap" id="wpaam-settings-panel">
		<h2 class="wpaam-page-title"><?php printf( __( 'Add Your Vat values', 'wpaam' ), WPAAM_VERSION ); ?></h2>
		<div style="margin-top:20px;">
			<form name="vat_values" method="post" action="">
				<p><label>Vat Name</label>
				<input type="text" name="vat_name" id="vat_name" class="intput"></p>
				<p><label>Vat Value</label>
				<input type="text" name="vat_value" id="vat_value" class="intput"></p>

				<p><input type="submit" name="vat_submit" value="Add"></p>
			</form>
		</div><!-- #tab_container-->
	</div><!-- .wrap -->
	<?php } 

	public function get_vat_values_list() {

	}

}
