$( document ).ready( () => {
	const debugTarget = $( '#wp-admin-bar-plugin_name_replace_me_debugger' );
	const debugTabs = $( '#plugin-name-replace-me-debug-bar-tabs a' );
	const debugBarWrap = $( '#debug-bar-wrap' );
	const debugClose = $( debugBarWrap ).find( '.debug-bar-close' );
	const debugEventLogs = $( debugBarWrap ).find( 'pre' );

	debugTarget.on( 'click', ( e ) => {
		e.preventDefault();
		debugBarWrap.toggle();
	} );

	debugClose.on( 'click', ( e ) => {
		e.preventDefault();
		debugBarWrap.hide();
	} );

	debugTabs.on( 'click', function( e ){
		e.preventDefault();
		const event = $( this ).data( 'event' );
		debugEventLogs.hide();
		debugTabs.removeClass( 'nav-tab-active' );
		$( this ).addClass( 'nav-tab-active' );
		$( '#' + event ).show();
	} );

	$( document ).keyup( ( e ) => {
		if( e.key === 'Escape' ){
			debugBarWrap.hide();
		}
	} );
} );