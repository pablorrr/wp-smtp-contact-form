(function( $ ){
	$( document ).ready( function() {
		/* 
		 *add notice about changing in the settings page 
		 */
		$( '#swpsmtpcf-mail input' ).bind( "change select", function() {
			if ( $( this ).attr( 'type' ) != 'submit' ) {
				$( '.updated.fade' ).css( 'display', 'none' );
				$( '#swpsmtpcf-settings-notice' ).css( 'display', 'block' );
			};
		});
	});
})(jQuery);
