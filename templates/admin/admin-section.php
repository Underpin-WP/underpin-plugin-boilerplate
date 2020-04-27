<?php
/**
 * Admin Page Template
 *
 * @author: Alex Standiford
 * @date  : 12/21/19
 */

use Plugin_Name_Replace_Me\Core\Abstracts\Settings_Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $template ) || ! $template instanceof \Plugin_Name_Replace_Me\Core\Abstracts\Admin_Page ) {
	return;
}

$section = $template->get_param( 'section', [ 'fields' => [], 'name' => '' ] );

?>
	<tr>
		<td colspan="2">
			<h3><?= $section['name']; ?></h3>
			<hr>
		</td>
	</tr>
<?php
foreach ( $section['fields'] as $field ) {
	if ( $field instanceof Settings_Field ) {
		echo $field->place( true );
	}
}
?>