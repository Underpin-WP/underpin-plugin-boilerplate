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

$events = $template->get_param( 'events', [] );

if ( empty( $events ) ) {
	$events = [ 'No events were logged for this event type.' ];
}

?>
<pre id="<?= $template->get_param( 'event_type', '' ) ?>">
<?php foreach ( $events as $event ): ?>
<?= $event . "\n\n" ?>
<?php endforeach; ?>
</pre>