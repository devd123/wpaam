<?php
/**
 * wpaam Template: Account page tabs.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

?>

<div id="wpaam-account-forms-tabs" class="wpaam-account-forms-tabs">

	<?php if( $tabs && is_array( $tabs ) ) : ?>

		<ul>

		<?php foreach ( $tabs as $key => $tab ) : ?>
			<li class="wpaam-form-tab tab-<?php echo $key; ?> <?php echo $current_tab == $key || $current_tab == null && $all_tabs[0] == $key ? 'active' : ''?>">
				<a href="<?php echo esc_url( wpaam_get_account_tab_url( $tab['id'] ) ); ?>"><?php echo $tab['title']; ?></a>
			</li>
		<?php endforeach; ?>

		</ul>

		<div class="wpaam-clearfix"></div>

	<?php endif; ?>

</div>