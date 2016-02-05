<?php
/**
 * Registers the dropdown type field.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * wpaam_Field_Type_Username Class
 *
 * @since 1.0.0
 */
class wpaam_Field_Type_Dropdown extends wpaam_Field_Type {

	/**
	 * Constructor for the field type
	 *
	 * @since 1.0.0
 	 */
	public function __construct() {

		// DO NOT DELETE
		parent::__construct();

		// Label of this field type
		$this->name             = _x( 'Dropdown', 'field type name', 'wpaam' );

		// Field type name
		$this->type             = 'select';

		// Class of this field
		$this->class            = __CLASS__;

		// Set registration
		$this->set_registration = true;

		// Set requirement
		$this->set_requirement  = true;

		// Add repeater to this field type.
		$this->has_repeater     = true;

	}

}

new wpaam_Field_Type_Dropdown;
