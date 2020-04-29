<?php
/**
 * Debug bar wrapper template.
 *
 * @since   1.0.0
 */

use Plugin_Name_Replace_Me\Core\Factories\Log_Item;

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

if ( ! is_array( $items ) ) {
	$items = [ $items ];
}

?>
<pre id="<?= $item_type ?>">
<?php foreach ( $items as $item ): ?>
<?php if($item instanceof Log_Item):?>
<?= $item->format() . "\n\n" ?>
<?php else: ?>
<?= $item; ?>
<?php endif; ?>
<?php endforeach; ?>
</pre>