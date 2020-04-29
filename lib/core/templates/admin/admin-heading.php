<?php
/**
 * Admin Heading Template
 * Default template to render an admin page.
 *
 * @since 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $template ) || ! $template instanceof \Plugin_Name_Replace_Me\Core\Abstracts\Admin_Page ) {
	return;
}

$current = $template->get_param( 'section', '' );
?>
<nav class="nav-tab-wrapper">
	<?php foreach ( $template->get_param( 'sections' ) as $id => $section ): ?>
		<a class="nav-tab<?= $current === $id ? ' nav-tab-active' : '' ?>" href="<?= $template->get_section_url( $id ) ?>"><?= $section['name'] ?></a>
	<?php endforeach; ?>
</nav>