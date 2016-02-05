<?php
/**
 * Registers the checkbox type field.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * wpaam_Field_Type_Checkbox Class
 *
 * @since 1.0.0
 */
class wpaam_Field_Type_Checkbox extends wpaam_Field_Type {

	/**
	 * Constructor for the field type
	 *
	 * @since 1.0.0
	*/
	public function __construct() {

		// DO NOT DELETE
		parent::__construct();

		// Label of this field type
		$this->name             = _x( 'Checkbox', 'field type name', 'wpaam' );
		// Field type name
		$this->type             = 'checkbox';
		// Class of this field
		$this->class            = __CLASS__;
		// Set registration
		$this->set_registration = true;
		// Set requirement
		$this->set_requirement  = false;

	}

	/**
	 * Method to register options for fields.
	 *
	 * @since 1.2.0
	 * @access public
	 * @return array list of options.
	 */
	public static function options() {

		$options = array();

		$options[] = array(
			'name'  => 'checked',
			'label' => esc_html__( 'Enabled by default', 'wpaam' ),
			'desc'  => esc_html__( 'Enable this option to set this checkbox as enabeld by default.', 'wpaam' ),
			'type'  => 'checkbox',
		);

		return $options;

	}

	/**
	 * Modify the output of the field on the fronted profile.
	 *
	 * @since 1.2.0
	 * @param  string $value the value of the field.
	 * @param  object $field field details.
	 * @return string        the formatted field value.
	 */
	public static function output_html( $value, $field ) {

		if( $value == '1' ) {
			$value = esc_html_x( 'Yes', 'Used when displaying the value of a checkbox field within the profile page.', 'wpaam' );
		}

		return $value;

	}

}

new wpaam_Field_Type_Checkbox;
