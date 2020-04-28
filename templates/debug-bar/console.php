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

$items     = $template->get_param( 'items', [] );
$item_type = $template->get_param( 'item_type', '' );


if ( empty( $items ) ) {
	$items = [ 'Nothing to show for ' . $item_type . ' type.' ];
}

?>
<pre id="<?= $item_type ?>">
<?php foreach ( (array) $items as $item ): ?>
<?= $item . "\n\n" ?>
<?php endforeach; ?>
</pre>