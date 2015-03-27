<?php
$post_types = get_post_types( array(
        'public' => true,
        'hierarchical' => false
        //'capability_type' => 'post'
    ),
    'objects');
?>

<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'eps-tag-cloud' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo isset($title) ? $title : ''; ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id( 'post_taxonomy' ); ?>"><?php _e( 'Choose your Taxonomy:', 'eps-tag-cloud' ); ?></label>
    <select class="widefat eps-post-taxonomy-select" id="<?php echo $this->get_field_id( 'post_taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'post_taxonomy' ); ?>">
        <option value="" default="Default"><?php _e("Choose a Taxonomy", 'eps-tag-cloud'); ?></option>
        <?php
        foreach( $post_types as $key => $cur_post_type )
        {
            $taxonomies = get_object_taxonomies( $cur_post_type->name, 'objects' );

            if( empty( $taxonomies ) ) continue;
            printf('<optgroup id="optgroup-%s" data-post_type="%s" label="%s">',
                __("Taxonomies for ", 'eps-tag-cloud') . $cur_post_type->name,
                $cur_post_type->name,
                $cur_post_type->label
            );
            foreach( $taxonomies as $tax )
            {
                printf('<option value="%s" %s>%s</option>',
                    $tax->name,
                    ( isset($post_taxonomy) && $tax->name == $post_taxonomy ) ? 'selected="selected"' : null,
                    $tax->label
                );
            }
            echo '</optgroup>';

        }
        ?>
    </select>
</p>
<input readonly="readonly" class="widefat eps-post-type" type="text" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" value="<?php echo $post_type; ?>">

<p>
    <label for="<?php echo $this->get_field_id( 'method' ); ?>"><?php _e( 'Method of search:', 'eps-tag-cloud' ); ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id( 'method' ); ?>" name="<?php echo $this->get_field_name( 'method' ); ?>">
        <option value="and" <?php echo ( isset($method) && $method == 'and' ) ? 'selected="selected"' : null; ?>><?php _e("AND", 'eps-tag-cloud'); ?></option>
        <option value="or" <?php echo ( isset($method) && $method == 'or' ) ? 'selected="selected"' : null; ?>><?php _e("OR", 'eps-tag-cloud'); ?></option>
    </select>
</p>
<p>
    <input id="<?php echo $this->get_field_id('show_counts'); ?>" name="<?php echo $this->get_field_name('show_counts'); ?>" value="on" type="checkbox" <?php checked($show_counts, 'on'); ?> />&nbsp;
    <label for="<?php echo $this->get_field_id('show_counts'); ?>"><?php _e("Show post counts", 'eps-tag-cloud'); ?></label>
</p>
