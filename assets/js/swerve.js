jQuery( document ).ready( function( $ ) {

/*------------------------------------*\
	CONTENTS
\*------------------------------------*/
/*
	META BOX PERMALINK LIST
*/


/*------------------------------------*\
	$META BOX PERMALINK LIST
\*------------------------------------*/

  	$( '.mkdo-swerve-permalink-list label input' ).click( function() { 
	    if( $( this ).is( ':checked' ) ) {
	         $( this ).parent().css( 'color', '#333' ).css( 'text-decoration', 'none' );
	    }
	    else
	    {
	    	$( this ).parent().css( 'color', '#aaa' ).css( 'text-decoration', 'line-through' );
	    }
	}); 



}); //End jQuery