jQuery(document).ready(function( $ ){

    // Add new slide
    $( '#add-slide' ).on('click', function() {
        var row = $( '.empty-row.screen-reader-text' ).clone(true);
        row.removeClass( 'empty-row screen-reader-text' );
        row.insertBefore( '#cslider-fieldset tbody>tr:last' );
        return false;
    });
  
    // Delete slide
    $( '.delete-slide' ).on('click', function() {
        if (confirm('Are you sure?')) {
            $(this).parents('tr').remove();
            return false;
        }
    });

    // Sorting
    $( function() {
        $( "#cslider-fieldset tbody" ).sortable();
        $( "#cslider-fieldset tbody" ).disableSelection();
    } );

    // Image previews
    //$(".cslider-img").keyup(function() {
    //    $(".cslider-preview").html($(".cslider-img").val());
    //});
});