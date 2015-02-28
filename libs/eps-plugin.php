<?php
/**
 * EPS Plugin
 *
 * @author Shawn Wernig, Eggplant Studios, www.eggplantstudios.ca
 * @version 1
 * @copyright 2015 Eggplant Studios
 * @package EPS Plugin
 */

require_once('eps-plugin-options.php');

if( ! class_exists('EPS_Plugin') )
{
class EPS_Plugin {

    protected $config = array(
        'version' => '',
        'option_slug' => '',
        'page_slug' => '',
        'page_title' => '',
        'url' => '',
        'path' => ''
    );

    protected $resources = array(
        'css' => array(
            'admin.css'
        ),
        'js' => array(
            'admin.js'
        )
    );

    protected $tables = array(

    );

    protected $dependencies = array();

    protected $options;

    public $name = '';


    /**
     *
     * Constructor
     *
     * Add some actions.
     *
     */
    public function __construct(){
        $this->config['url'] = plugin_dir_url( dirname(__FILE__) );
        $this->config['path'] = plugin_dir_path( dirname(__FILE__) );
        $this->settings = new EPS_Plugin_Options( $this );

        register_activation_hook(	__FILE__, array($this->name, '_activation'));
        register_deactivation_hook(	__FILE__, array($this->name, '_deactivation'));
        if ( !self::is_current_version() )  self::update_self();
        add_action('admin_enqueue_scripts',                  array($this, 'admin_resources'));
        add_action('init',                  array($this, 'plugin_resources'));
    }


    public function resolve_dependencies()
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        foreach( $this->dependencies as $name => $path_to_plugin )
        {
            if ( ! is_plugin_active( $path_to_plugin ) )
            {
                echo $name . ' IS NOT INSTALLED!';
            }
        }
    }
    /**
     *
     * ENQUEUE_RESOURCES
     *
     * This function will queue up the javascript and CSS for the plugin.
     *
     * @return html string
     * @author epstudios
     *
     */
    public function admin_resources(){
        wp_enqueue_script('jquery');
        foreach( $this->resources as $path => $resource )
        {
            switch( $path )
            {
                case 'css':
                    foreach( $resource as $item )
                    {
                        wp_enqueue_style( $this->resource_name( $resource ), $this->resource_path($path, $item ) );
                    }
                    break;
                case 'js':
                    foreach( $resource as $item )
                    {
                        wp_enqueue_script( $this->resource_name( $resource ), $this->resource_path($path, $item ) );
                    }
                    break;
            }
        }
    }

    public static function plugin_resources()
    {

    }
    private function resource_path( $path, $resource )
    {
        return strtolower(
            $this->config['url']
            . $path . '/'
            . $resource );
    }
    private function resource_name( $resource )
    {
        return strtolower( $this->name . '_' . key( $resource ) );
    }

    /**
     *
     *
     * Activation and Deactivation Handlers.
     *
     * @return nothing
     * @author epstudios
     */
    public function activation_error() {
        file_put_contents($this->config('path'). '/error_activation.html', ob_get_contents());
    }

    public function _activation() {
        if ( !self::is_current_version() )  self::update_self();
    }

    public function _deactivation() {}

    function is_current_version(){
        $version = get_option( $this->config['option_slug'] . '_version' );
        return version_compare($version, $this->config['version'], '=') ? true : false;
    }

    /**
     *
     * CHECK VERSION
     *
     * This function will check the current version and do any fixes required
     *
     * @return string - version number.
     * @author epstudios
     *
     */
    public function update_self() {
        $version = get_option( $this->config['option_slug'] . '_version' );
        update_option( $this->config['option_slug'] . '_version', $this->config['version'] );
        return $this->config['version'];
    }

    public function config($name)
    {
        return ( isset($this->config[ $name ]) ) ? $this->config[ $name ] : false;
    }

    /**
     *
     * CREATE TABLES
     *
     * Creates the new database architecture
     *
     * TODO This could be more elegant - and check for syntax errors too.
     *
     * @return nothing
     * @author epstudios
     *
     */
    public function _create_tables() {
        global $wpdb;

        $sql = '';

        foreach( $this->tables as $name => $data )
        {
            $sql .= sprintf("CREATE TABLE `%s` (\n", $wpdb->prefix . $name );

            foreach($data['columns'] as $name => $attr )
            {
                $sql .= sprintf( "`%s` %s, \n", $name, $attr );
            }

            $sql .= "PRIMARY KEY (`ID`), \n";

            if( isset($data['foreign_keys']) && !empty($data['foreign_keys']) )
            {
                foreach( $data['foreign_keys'] as $name => $reference )
                {
                    $sql .= sprintf( "FOREIGN KEY (`%s`) REFERENCES %s%s ON DELETE CASCADE ON UPDATE CASCADE, \n", $name, $wpdb->prefix, $reference );
                }
            }

            $sql = substr($sql, 0, -3);
            $sql .= "\n";
            $sql .= ");\n\n";
        }

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

}

?>