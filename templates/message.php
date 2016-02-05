<?php
/**
 * wpaam Template: Message.
 * Displays a given message and sets the class to the div for styling purposes.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
?>

<?php do_action( "wpaam_before_message_{$id}", $id, $type, $text ); ?>

<div id="<?php echo esc_attr( $id ); ?>" class="wpaam-message <?php echo esc_attr( $type ); ?>">

	<p class="the-message"><?php echo $text ?></p>

</div>

<?php do_action( "wpaam_after_message_{$id}", $id, $type, $text ); ?>