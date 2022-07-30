<?php
/**
* Plugin Name: BC Menus System
* Plugin URI: https://github.com/kwilson1337
* Description: Display restaurant menus in style
* Version: 1.0
* Author: Kyle Wilson
*/

defined('ABSPATH') or die('naaaaah');

class bc_menus
{
    public function __construct()
    {
        add_action('init', [ $this, 'acf_create' ]);//Create ACF Settings
        add_action('init', [ $this, 'permalinks' ]);
        add_action('init', [ $this, 'register_shortcode' ]);//Register the ShortCodes
        add_action('init', [$this, 'stylesAndJS']); //Load in styles and js
    }

    // function used when plugin is activated
    public function activate()
    {
        $this -> acf_create();
        $this -> register_shortcode();
    }

    // creates cpt Menus
    public function cpt_create()
    {
        include_once(get_template_directory() . '/inc/classes/CreateCPT.php');
        include_once(get_template_directory() . '/inc/classes/TaxonomyManager.php');

        if (class_exists('CreateCPT')) {
            (new CreateCPT('Menus', 'Menu', 'menus'))
            ->set('menu_icon', 'dashicons-carrot')
            ->set('has_archive', true)
            ->set('taxonomies', [''])
            ->create();
        }

        TaxonomyManager::addNew('Menu Categories', 'menus', true, false);
        // Create Default Category For Custom Post Type
        function bc_menus_set_default_category($post_id, $post)
        {
            if ('publish' === $post->post_status && $post->post_type === 'menus') {
                $defaults = array(
                    'menu_categories' => array( 'Uncategorized' )
                );
                $taxonomies = get_object_taxonomies($post->post_type);
        
                foreach ((array) $taxonomies as $taxonomy) {
                    $terms = wp_get_post_terms($post_id, $taxonomy);
                    if (empty($terms) && array_key_exists($taxonomy, $defaults)) {
                        wp_set_object_terms($post_id, $defaults[$taxonomy], $taxonomy);
                    }
                }
            }
        }
        add_action('save_post', 'bc_menus_set_default_category', 100, 2);
    }

    public function register_shortcode()
    {
        include_once(__DIR__ . '/shortcode/bc-menu-shortcode.php');
        // Shortcode to copy and paste
        function bc_menus_short_code_meta()
        {
            echo "<strong>[bc-menus]</strong> <br>";
            echo "<strong>[bc-menus] takes two arguments, category and id</strong><br>";
            echo "<br><strong>Category lets you filter between menu categories</strong> <br>";
            echo "<strong>You can Either use the Category Name or you can use the Category Slug</strong> <br>";
            echo "<strong>Example: [bc-menus category='texas']</strong> <br> <br>";
            echo "<strong>id lets you filter between the post id</strong><br>";
            echo "<strong>If you want to show a specific menu you can use the ID</strong><br>";
            echo "<strong>Example: [bc-menus id='1163]'</strong>";
        }
        
        function bc_menus_add_meta_box()
        {
            add_meta_box("bc-menu-shortcode", "BC Menu Shortcode", "bc_menus_short_code_meta", "menus", "normal", "low", null);
        }
        
        add_action("add_meta_boxes", "bc_menus_add_meta_box");
    }

    public function acf_create()
    {
        include_once(__DIR__ . '/settings/settings.php');
        
        if (class_exists('ACF_Settings')) {
            $settings = new ACF_Settings();
        }
    }

    // Refreshes Permalinks on activation
    public function permalinks()
    {
        flush_rewrite_rules();
    }

    /**
    * Load in Styles
    */
    public function stylesAndJS()
    {
        wp_register_style('bc-menu-style', plugins_url('dist/style.min.css', __FILE__));
        wp_enqueue_style('bc-menu-style');
        wp_register_script('bc-menu-js', plugins_url('dist/main.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('bc-menu-js');
    }
}
//End BC MENUS


// checks to make sure class exists
if (class_exists('bc_menus')) {
    $bc_menu = new bc_menus();
    $bc_menu -> cpt_create();
}


// activates plugin
register_activation_hook(__FILE__, [$bc_menu, 'activate']);


// SINGLE AND ARCHIVE PATH FOR  BC-MENUS
add_filter('archive_template', 'get_bc_menus_archive_template');
function get_bc_menus_archive_template($archive_template)
{
    global $post;
    if ($post->post_type == 'menus') {
        $archive_template = dirname(__FILE__) . '/archive-menus.php';
    }
    return $archive_template;
}
add_filter('single_template', 'get_bc_menus_single_template');
function get_bc_menus_single_template($single_template)
{
    global $post;
    if ($post->post_type == 'menus') {
        $single_template = dirname(__FILE__) . '/single-menus.php';
    }
    return $single_template;
}


// Webpack
add_action('plugins_loaded', function () {
    if (class_exists('BCWebpackCompile') && !is_admin()) {
        (new BCWebpackCompile([
            'plugin_name' => 'bc-menus',
            'source_dir'  => plugin_dir_path(__FILE__) . 'src',
            'dist_dir'    => get_template_directory() . '/dist/plugins'
        ]));
    }
});



// deactivates the plugin
register_deactivation_hook(__FILE__, function () {
    // UNREGISTER THE CPT SO IT IS NO LONGER IN THE MEMORY
    unregister_post_type('bc-menus');

    // CLEAR THE PERMALINKS TO REMOVE CPT FROM THE DATABASE
    flush_rewrite_rules();
});
