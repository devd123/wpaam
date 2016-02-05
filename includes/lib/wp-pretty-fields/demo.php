<?php
/**
 * Demo Metabox for Pretty_Fields
 */

if (is_admin()) {

  	/* 
	 * configure your meta box
	 */
	$config = array(
		'id'    => 'demo_meta_box',
		'title' => 'Demo Fields',
		'pages' => array('page'),
		'fields' => array(
			array(
				'id'   => 'text',
				'name' => __( 'Text Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'text'
			),
			array(
				'id'   => 'textarea',
				'name' => __( 'Textarea Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'textarea'
			),
			array(
				'id'   => 'url',
				'name' => __( 'URL Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'url'
			),
			array(
				'id'   => 'number',
				'name' => __( 'Number Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'number'
			),
			array(
				'id'   => 'email',
				'name' => __( 'Email Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'email'
			),
			array(
				'id'   => 'button',
				'name' => __( 'Button Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'url' => 'http://google.com',
				'type' => 'button'
			),
			array(
				'id'   => 'color',
				'name' => __( 'Color Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'color'
			),
			array(
				'id'   => 'image',
				'name' => __( 'Image Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'image'
			),
			array(
				'id'   => 'select',
				'name' => __( 'Select Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'select',
				'options' => array('value' => 'label', 'value1' => 'Another label')
			),
			array(
				'id'   => 'radio',
				'name' => __( 'Radio Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'radio',
				'options' => array('value' => 'label', 'value1' => 'Another label')
			),
			array(
				'id'   => 'multiselect',
				'name' => __( 'Multiselect Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'multiselect',
				'options' => array('value' => 'label', 'value1' => 'Another label')
			),
			array(
				'id'   => 'checkbox_list',
				'name' => __( 'Checkbox list Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'checkbox_list',
				'options' => array('value' => 'label', 'value1' => 'Another label')
			),
			array(
				'id'   => 'checkbox',
				'name' => __( 'Checkbox Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'checkbox'
			),
			array(
				'id'   => 'editor',
				'name' => __( 'Editor Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'editor'
			),
			array(
				'id'   => 'gallery',
				'name' => __( 'Gallery Field', 'wpaam' ),
				'sub' => __( 'Description goes here', 'wpaam' ),
				'desc' => __( 'Field Description goes here', 'wpaam' ),
				'type' => 'gallery'
			),
		),
	);

	/*
	 * Initiate your meta box
	 */
  	$demo_meta_box =  new Pretty_Metabox($config);

}