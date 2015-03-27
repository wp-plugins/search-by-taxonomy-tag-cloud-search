<form class="eps-tag-cloud-form" role="search" method="get" action="<?php echo home_url(); ?>">
    <?php
    $terms = get_terms( $post_taxonomy );
    $has_active_term = false;
    $queried_terms = ( isset( $_GET['terms'] ) ) ?  explode( ',', $_GET['terms'] ) : array();
    if( ! empty( $terms ) )
    {
        echo '<ul class="eps-tag-cloud">';
        foreach( $terms as $term )
        {
            $active_term = in_array($term->slug, $queried_terms ) ? true : false;
            $has_active_term = ( $has_active_term === false && $active_term === true ) ? true : $has_active_term;
            ?>
            <li>
                <button class="eps-tag-cloud-item <?php echo ( $active_term  ) ? 'active' : null; ?>" data-term="<?php echo $term->slug; ?>">
                <?php
                if( $show_counts )
                {
                    printf('<span class="eps-tag-cloud-count">%s</span>', $term->count );
                }
                echo $term->name;
                ?>

                </button>
            </li>
            <?php
        }
        echo '</ul>';
    }
    ?>
    <input type="hidden" class="eps-tag-cloud-taxonomy" name="tag-search" value="<?php echo $post_taxonomy; ?>">
    <input type="hidden" name="method" value="<?php echo $method; ?>">
    <input type="hidden" class="eps-tag-cloud-terms" name="terms" value="">
    <input type="hidden" class="eps-tag-cloud-tax-url" value="<?php echo esc_attr(get_term_link($term, $post_taxonomy)) ?>">
    <input type="submit" class="eps-tag-cloud-submit" <?php echo $has_active_term ? null : 'disabled="disabled"'; ?> value="<?php _e("Search", 'eps-tag-cloud'); ?>">
</form>