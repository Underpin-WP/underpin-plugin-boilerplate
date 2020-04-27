<?php
/**
 * Debug bar wrapper template.
 *
 * @since   1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $template ) || ! $template instanceof Plugin_Name_Replace_Me\Core\Utilities\Debug_Bar ) {
	return;
}

$event_listing = $template->get_param( 'events', [] );
?>
<div id="debug-bar-wrap">
	<span class="debug-bar-close">X</span>
	<h2>Session Events</h2>
	<p>Here's what was logged during this session.</p>

	<?php if ( empty( $event_listing ) ): ?>
		<em>Well, that's boring (or perhaps exciting!). Nothing was logged.</em>
	<?php endif; ?>

	<?= $template->get_template( 'event-tabs', [ 'events' => array_keys( $event_listing ) ] ) ?>

	<div class="event-listing">
		<?php foreach ( $event_listing as $event_type => $events ): ?>
			<?= $template->get_template( 'event-listing', [ 'event_type' => $event_type, 'events' => $events ] ); ?>
		<?php endforeach; ?>
	</div>
</div>