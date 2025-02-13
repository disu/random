<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    random
 * @subpackage random/admin
 * @author     Scribit <wordpress@scribit.it>
 */
class Random_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     * @access   public
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Random_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Random_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (isset($_GET['page']) && ($_GET['page'] == RANDOM_PLUGIN_SLUG)) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/random-admin.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @access   public
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Random_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Random_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (isset($_GET['page']) && ($_GET['page'] == RANDOM_PLUGIN_SLUG)) {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/random-admin.js', array( 'jquery' ), $this->version, false);
        }
    }

    /**
     * Define menu items for tools menu.
     *
     * @since    1.0.0
     * @access   public
     */
    public function management_page()
    {
        require_once plugin_dir_path(__FILE__) . 'partials/random-admin-display.php';

        add_management_page(
            'Random',
            'Random',
            'manage_options',
            RANDOM_PLUGIN_SLUG,
            'random_admin_page_handler'
        );
    }

    /**
     * Manage actions on plugin load
     *
     * @since    1.0.0
     * @access   public
     */
    public function load_plugin()
    {
        // Manage redirection after plugin activation
        // See Wordpress tip: https://developer.wordpress.org/reference/functions/register_activation_hook/
        if ( is_admin() && get_option('activated_plugin') == RANDOM_PLUGIN_SLUG ) {
            delete_option('activated_plugin');
            wp_redirect( esc_url( admin_url('tools.php?page='. RANDOM_PLUGIN_SLUG) ) );
            exit();
        }
    }

    /**
     * Manage admin notices for admin pages
     *
     * @since    1.0.0
     * @access   public
     */
    public function random_admin_notices()
    {
        $current_page = get_current_screen()->base;
        if ('tools_page_random' == $current_page) {
        }
    }
}
