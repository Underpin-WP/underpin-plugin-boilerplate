<?php
/**
 * Debug bar event tab listing
 *
 * @since   1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $template ) || ! $template instanceof Plugin_Name_Replace_Me\Core\Utilities\Debug_Bar ) {
	return;
}

// Bail early if we dont have any events to display.
if ( empty( $template->get_param( 'items' ) ) ) {
	return;
}

?>
<nav id="plugin-name-replace-me-debug-bar-tabs" class="nav-tab-wrapper">
	<?php foreach ( $template->get_param( 'items', [] ) as $key => $item ): ?>
		<a class="nav-tab<?= $key === 0 ? ' nav-tab-active' : '' ?>" href="#" data-event="<?= $item ?>"><?= $item ?></a>
	<?php endforeach; ?>
</nav>