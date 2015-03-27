jQuery(document).ready(function ($) {

    $('.widget_eps_tagcloudwidget button.eps-tag-cloud-item').on('click', function(){
        $( this ).toggleClass('active').promise().done(function(){
            var terms = eps_get_terms( this );
            if( terms.length )
            {
                $(this).closest('.widget_eps_tagcloudwidget')
                    .find('input.eps-tag-cloud-submit')
                    .removeAttr('disabled');
            }
            else
            {
                // $('input.eps-tag-cloud-submit').attr('disabled','disabled');
                //$('.eps-tag-cloud-form').attr('action');
                //eps-tag-cloud-tax-url
            }
        });

        return false;
    });

    $('.eps-tag-cloud-form').on('submit', function(){
        var terms = eps_get_terms( this ).join(',');
        $(this).find('.eps-tag-cloud-terms').val(terms);
    });

    function eps_get_terms( element )
    {
        return $(element).closest('.widget_eps_tagcloudwidget')
            .find('button.eps-tag-cloud-item.active')
            .map(function() {
                return $(this).data('term');
            })
            .toArray()
    }
});