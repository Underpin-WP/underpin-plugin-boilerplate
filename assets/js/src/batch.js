$( document ).ready( () => {

	const notices = $( '.batch-notice' );

	$( notices ).each( function(){
		const notice = $( this );
		const button = $( this ).find( 'button' );
		const batchId = $( this ).data( 'batch-id' );
		const progress = $( this ).find( '.progress' );
		const progressBar = $( this ).find( '.progress-bar' );
		const message = $( this ).find( '.message' );
		const statusWrap = $( this ).find( '.status-wrap' );
		const data = plugin_name_replace_me_batch[batchId];

		function complete(){
			$( progressBar ).remove();
			$( statusWrap ).remove();
			$( message ).text( 'All done!' );
			setTimeout( function(){
				$( notice ).remove();
			}, 1000 );
		}

		function error(){
			$( message ).text( 'Something went wrong while upgrading.' );
		}

		function task( tally = 0 ){

			$( progress ).width( ( tally / data.total_items ) * 100 + '%' );

			if( tally >= data.total_items ){
				return complete();
			}

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: plugin_name_replace_me_batch.ajaxUrl,
				data: {
					action: batchId,
					currentTally: tally
				},
				success: ( response ) => {
					if( typeof response === 'object' && undefined !== response.error ){
						return error( response );
					}

					task( response );
				},
				error: ( response ) => console.error( response )
			} );
		}

		$( button ).on( 'click', function( e ){
			e.preventDefault();
			task();
		} );

	} );
} );