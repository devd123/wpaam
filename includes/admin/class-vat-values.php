<?php

/**
 * WPAAM: Vat Editor
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPAAM_Vat_List  
{

	/** Class constructor */
	public static function init() {

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'wpaam_vat_values';
		$this->primary_key = 'id';
		$this->version     = '1.0';

		self::create_table();

	}

	public static function add_vat_values () {
		
	}

	
	/***
	** Create vat tax table
	**/
	public static function create_table() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE IF NOT EXISTS" . $this->table_name . " (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`user_id` int(20) NOT NULL,
			`vat_name` varchar(255) NOT NULL,
			`vat_value` int(10) NOT NULL,
			PRIMARY KEY (`id`)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

}

new WPAAM_Vat_List;