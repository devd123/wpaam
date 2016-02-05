<?php
/*
Plugin Name: Advance Account Manager
Plugin URI:  https://neerusite.wordpress.com/
Description: This plugin is developed for user accountablility management with their respective cleints
Author:      Neeru Sharma
Author URI:  https://neerusite.wordpress.com/
Text Domain: wpaam
**
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_Adavance_Account_Manager' ) ) :

/**
 * Main WP_Adavance_Account_Manager Class
 *
 * @since 1.0.0
 */
class WP_Adavance_Account_Manager {

	/** Singleton *************************************************************/
	/**
	 * @var WP_Adavance_Account_Manager.
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Forms Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $forms;

	/**
	 * WPAAM Emails Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $emails;

	/**
	 * WPAAM Email Template Tags Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $email_tags;

	/**
	 * HTML Element Helper Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $html;

	/**
	 * Field Groups DB Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $field_groups;

	/**
	 * Fields DB Object
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $fields;

	/**
	 * Main WP_Adavance_Account_Manager Instance
	 *
	 * Insures that only one instance of WP_Adavance_Account_Manager exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0.0
	 * @uses WP_Adavance_Account_Manager::setup_constants() Setup the constants needed
	 * @uses WP_Adavance_Account_Manager::includes() Include the required files
	 * @uses WP_Adavance_Account_Manager::load_textdomain() load the language files
	 * @see WPAAM()
	 * @return WP_Adavance_Account_Manager
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Adavance_Account_Manager ) ) {

			self::$instance = new WP_Adavance_Account_Manager;
			self::$instance->setup_constants();
			self::$instance->includes();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->emails       = new WPAAM_Emails();
			self::$instance->email_tags   = new WPAAM_Email_Template_Tags();
			self::$instance->forms        = new WPAAM_Forms();
			self::$instance->html         = new WPAAM_HTML_Elements();
			self::$instance->field_groups = new WPAAM_DB_Field_Groups();
			self::$instance->fields       = new WPAAM_DB_Fields();

		}
	
		return self::$instance;

	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpaam' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpaam' ), '1.0.0' );
	}

	/**
	 * Setup plugin constants
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function setup_constants() {

		// Plugin version
		if ( ! defined( 'WPAAM_VERSION' ) ) {
			define( 'WPAAM_VERSION', '1.2.5' );
		}

		// Plugin Folder Path
		if ( ! defined( 'WPAAM_PLUGIN_DIR' ) ) {
			define( 'WPAAM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'WPAAM_PLUGIN_URL' ) ) {
			define( 'WPAAM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File
		if ( ! defined( 'WPAAM_PLUGIN_FILE' ) ) {
			define( 'WPAAM_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Slug
		if ( ! defined( 'WPAAM_SLUG' ) ) {
			define( 'WPAAM_SLUG', plugin_basename( __FILE__ ) );
		}

	}

	/**
	 * Include required files
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {

		global $wpaam_options;

		require_once WPAAM_PLUGIN_DIR . 'includes/admin/settings/register-settings.php';
		$wpaam_options = wpaam_get_settings();
		

		// Load Assets Files
		require_once WPAAM_PLUGIN_DIR . 'includes/assets.php';
		// Load General Functions
		require_once WPAAM_PLUGIN_DIR . 'includes/functions.php';
		// Load Misc Functions
		require_once WPAAM_PLUGIN_DIR . 'includes/misc-functions.php';
		// Templates
		require_once WPAAM_PLUGIN_DIR . 'includes/templates-loader.php';
		// Plugin's filters
		require_once WPAAM_PLUGIN_DIR . 'includes/filters.php';
		// Plugin's actions
		require_once WPAAM_PLUGIN_DIR . 'includes/actions.php';
		// Shortcodes
		require_once WPAAM_PLUGIN_DIR . 'includes/classes/class-wpaam-shortcodes.php';
		// Emails
		require_once WPAAM_PLUGIN_DIR . 'includes/emails/class-wpaam-emails.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/emails/class-wpaam-emails-tags.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/emails/functions.php';
		// Load html helper class
		require_once WPAAM_PLUGIN_DIR . 'includes/classes/class-wpaam-html-helper.php';
		// Load db helper class
		require_once WPAAM_PLUGIN_DIR . 'includes/abstracts/abstract-wpaam-db.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/class-wpaam-db-field-groups.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/class-wpaam-db-fields.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/class-wpaam-fields-data-template.php';
		// Load fields helpers
		require_once WPAAM_PLUGIN_DIR . 'includes/abstracts/abstract-wpaam-field-type.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/avatar.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/checkbox.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/checkboxes.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/display_name.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/email.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/file.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/multiselect.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/nickname.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/number.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/password.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/radio.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/select.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/text.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/textarea.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/url.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/types/username.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/functions.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/fields/filters.php';
		// Forms
		require_once WPAAM_PLUGIN_DIR . 'includes/classes/class-wpaam-forms.php';

		// Files loaded only on the admin side.
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

			require_once WPAAM_PLUGIN_DIR . 'includes/admin/welcome.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/admin-pages.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/admin-notices.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/admin-actions.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/settings/display-settings.php';
			
			// Load Emails
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/emails/class-wpaam-emails-editor.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/emails/class-wpaam-emails-list.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/emails/registration-email.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/emails/password-recovery-email.php';

			// Load Custom Fields Editor
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/fields/class-wpaam-fields-editor.php';

			// Load admin menu manager functionalities
			require_once WPAAM_PLUGIN_DIR . 'includes/classes/class-wpaam-walker-nav-menu-checklist.php';
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/menu-functions.php';
			// Load dashboard widget
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/dashboard-widget.php';

			// Custom Fields Framework
			if ( ! class_exists( 'Pretty_Metabox' ) )
				require_once WPAAM_PLUGIN_DIR . 'includes/lib/wp-pretty-fields/wp-pretty-fields.php';

			// Load Tools Page
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/tools.php';
			// Load Addons Page
			require_once WPAAM_PLUGIN_DIR . 'includes/admin/addons.php';

			// License Handler.
			require_once WPAAM_PLUGIN_DIR . 'includes/updater/class-wpaam-license.php';

		}

		// AAM Product for WPAAM
		include( WPAAM_PLUGIN_DIR . 'AAM/product-registrations.php' );   
		include( WPAAM_PLUGIN_DIR . 'AAM/quotation-registrations.php' );   
		// Ajax Handler
		require_once WPAAM_PLUGIN_DIR . 'includes/classes/class-wpaam-ajax-handler.php';
		// Permalinks for WPAAM
		require_once WPAAM_PLUGIN_DIR . 'includes/classes/class-wpaam-permalinks.php';
		// Template actions
		require_once WPAAM_PLUGIN_DIR . 'includes/template-actions.php';
		// Load Profiles
		require_once WPAAM_PLUGIN_DIR . 'includes/profiles/functions.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/profiles/actions.php';
		require_once WPAAM_PLUGIN_DIR . 'includes/profiles/tabs.php';
		// Installation Hook
		require_once WPAAM_PLUGIN_DIR . 'includes/install.php';

	}

	/**
	 * Load the language files for translation
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_textdomain() {
		// Set filter for plugin's languages directory
		$wpaam_lang_dir = dirname( plugin_basename( WPAAM_PLUGIN_FILE ) ) . '/languages/';
		$wpaam_lang_dir = apply_filters( 'wpaam_languages_directory', $wpaam_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale',  get_locale(), 'wpaam' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'wpaam', $locale );

		// Setup paths to current locale file
		$mofile_local  = $wpaam_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/wpaam/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/wpaam folder
			load_textdomain( 'wpaam', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/wp-user-manager/languages/ folder
			load_textdomain( 'wpaam', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'wpaam', false, $wpaam_lang_dir );
		}
	}

}

endif;

/**
 * The main function responsible for returning WP_Adavance_Account_Manager
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wpaam = WPAAM(); ?>
 *
 * @since 1.0.0
 * @return object WP_Adavance_Account_Manager Instance
 */
function WPAAM() {
	return WP_Adavance_Account_Manager::instance();
}

// Get WPAAM Running
WPAAM();

 