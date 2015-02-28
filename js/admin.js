jQuery(document).ready(function ($) {
    $('body').on('change', '.widget .eps-post-taxonomy-select', function(){
        var group = $(this).find("option:selected").closest('optgroup');
        $('.widget .eps-post-type').val( group.data('post_type') );
    });
});



