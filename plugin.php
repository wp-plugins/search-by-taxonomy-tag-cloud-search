<?php

class EPS_TagCloud_Plugin extends EPS_Plugin {

    protected $config = array(
        'version' => '1.0.1',
        'option_slug' => 'eps_tagcloud',
        'page_slug' => 'eps_tagcloud',
        'page_title' => 'EPS Term Tag Cloud Search',
        'menu_location' => 'options',
        'page_permission' => 'edit_posts'
    );

    protected $dependancies = array();

    protected $tables = array();

    public $name = 'EPS Term Tag Cloud Search';

    public function __construct()
    {
        parent::__construct();
        $this->config['path'] = plugin_dir_path(__FILE__);
        $this->config['url'] = plugin_dir_url(__FILE__);
        add_action('pre_get_posts',     array( $this, 'search_filter'));
        add_filter('get_search_query',  array( $this, 'get_search_query'));

    }

    public static function plugin_resources()
    {
        // front end resources.
        wp_enqueue_style( 'eps-tag-cloud-styles', plugin_dir_url(__FILE__) . "css/styles.css" );
        wp_enqueue_script( 'eps-tag-cloud-scripts', plugin_dir_url(__FILE__) . "js/scripts.js", array('jquery'));

    }

    public static function search_filter($query) {
        $data = filter_input_array(INPUT_GET, array(
            'tag-search' => FILTER_SANITIZE_STRING,
            'terms'      => FILTER_SANITIZE_STRING,
            'method'     => FILTER_SANITIZE_STRING,
        ));



        if( $query->is_main_query() && isset($_GET['tag-search']) && isset($_GET['terms']) ) {
            // reset all vars
            $query->is_search = true;
            $query->is_single = $query->is_singular = $query->is_page = $query->is_home = $query->is_archive = false;
            // set tax query array
            $tax_query = array(
                'taxonomy' => $data['tag-search'],
                'field'    => 'slug',
                'terms'    => explode(',', $data['terms']),
            );
            // is AND method?
            if( strtolower( $data['method'] ) === 'and' ) {
                $tax_query['operator'] = 'AND';
            }
            // set tax query
            $query->set( 'tax_query', array($tax_query) );
            $query->set( 'page_id', false );
        }
    }


    public static function get_search_query( $query )
    {
        $data = filter_input_array(INPUT_GET, array(
            'tag-search' => FILTER_SANITIZE_STRING,
            'terms'      => FILTER_SANITIZE_STRING,
            'method'     => FILTER_SANITIZE_STRING,
        ));

        if( empty($data['terms']) || empty($data['tag-search'])   ) return $query;

        $terms = explode(',', $data['terms']);
        $term_names = array();
        foreach( $terms as $term_slug )
        {
            $term = get_term_by('slug', $term_slug, $data['tag-search'] );
            $term_names[] = $term->name;
        }
        return sprintf( implode( ', ', $term_names ) );
    }
}

// Init the plugin.
$EPS_TagCloud_Plugin = new EPS_TagCloud_Plugin();
?>