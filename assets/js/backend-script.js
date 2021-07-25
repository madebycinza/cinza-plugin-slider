jQuery(document).ready(function( $ ){
    $( '#add-row' ).on('click', function() {
        var row = $( '.empty-row.screen-reader-text' ).clone(true);
        row.removeClass( 'empty-row screen-reader-text' );
        row.insertBefore( '#cslider-fieldset tbody>tr:last' );
        return false;
    });
  
    $( '.remove-row' ).on('click', function() {
        $(this).parents('tr').remove();
        return false;
    });

    $( function() {
        $( "#cslider-fieldset tbody" ).sortable();
        $( "#cslider-fieldset tbody" ).disableSelection();
    } );
});