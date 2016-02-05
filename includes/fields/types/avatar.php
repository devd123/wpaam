<?php
/**
 * Registers the avatar type field.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * wpaam_Field_Type_Avatar Class
 *
 * @since 1.0.0
 */
class wpaam_Field_Type_Avatar extends wpaam_Field_Type {

	/**
	 * Constructor for the field type
	 *
	 * @since 1.0.0
 	 */
	public function __construct() {

		// DO NOT DELETE
		parent::__construct();

		// Label of this field type
		$this->name              = _x( 'Avatar', 'field type name', 'wpaam' );
		// Field type name
		$this->type              = 'avatar';
		// Class of this field
		$this->class             = __CLASS__;
		// Set registration
		$this->set_registration  = true;
		// Set requirement
		$this->set_requirement   = true;
		// Cannot be used multiple times.
		$this->supports_multiple = false;

	}

}

new wpaam_Field_Type_Avatar;
