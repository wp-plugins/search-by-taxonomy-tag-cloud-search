<?php
/**
 * EPS Tag Cloud
 * 
 * A Handy Widget for filtering posts by tags.
 * 
 * @package 	EPS Tag Cloud
 * @author		Shawn Wernig ( shawn@eggplantstudios.ca )
 * @version 	1.0.0
 * 
 */


 
/*
Plugin Name: Term/Tag Cloud Search
Plugin URI: http://www.eggplantstudios.ca
Description: A Handy widget for filtering posts by tags.
Version: 1.0.1
Author: Shawn Wernig http://www.eggplantstudios.ca
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

require_once(plugin_dir_path(__FILE__) .'functions.php');
require_once(plugin_dir_path(__FILE__) .'libs/eps-plugin.php');
require_once(plugin_dir_path(__FILE__) .'plugin.php');

class EPS_TagCloudWidget extends WP_Widget
{

    public $widget = "eps-tag-cloud";
    public $widget_title = "Term Tag Cloud Search";
    public $widget_description = "Filter post types by tags.";
    public $options = array(
        'title' => '',
        'post_type' => 'post',
        'post_taxonomy' => 'tags',
        'show_counts' => 'on'
    );

    function __construct() {
        parent::__construct( get_class($this), $this->widget_title, array(
            'description' => $this->widget_description
        ));
        add_action('widgets_init', create_function('', 'return register_widget("'.get_class($this).'");'));
    }


    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        foreach( $new_instance as $k => $v ) {
            $instance[$k] = sanitize_text_field($new_instance[$k]);
        }

        if( !isset($new_instance['show_counts']) && empty($new_instance['show_counts']) )  $instance['show_counts'] = 0;

        return $instance;
    }


    /** @see WP_Widget::form */
    function form($instance) {

        foreach( $instance as $key => $value )
            esc_attr($instance[$key]);

        // This too, using the defaults from the json file
        $instance = wp_parse_args(
            (array) $instance,
            $this->options
        );
        extract( $instance );
        include( plugin_dir_path(__FILE__) . 'templates/admin.widget.form.php');
    }





    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $instance );
        extract( $args );

        echo $before_widget;
        echo ($title) ? $before_title . $title . $after_title : null;
        if( isset($post_taxonomy) && isset($post_type) )
            include( plugin_dir_path(__FILE__) . 'templates/widget.php');
        echo $after_widget;
    }

}



$EPS_TagCloudWidget = new EPS_TagCloudWidget;

?>