<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    random
 * @subpackage random/public
 * @author     Scribit <wordpress@scribit.it>
 */
class Random_Public
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
     * @param      string    $plugin_name   The name of the plugin.
     * @param      string    $version    	The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/random-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/random-public.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Manage random_post shortcode handler.
     *
     * @since    1.0.0
     */
    public function random_post_handler($atts, $content, $tag)
    {
        $result = '';

        $atts = shortcode_atts(array(
            'post_types' => 'post',
            'post_number' => 1,
            'content_type' => 'title',
            'included_ids' => '',
            'excluded_ids' => '',
        ), $atts, $tag);

        $args = array(
            'post_type' => explode(',', $atts['post_types']),
            'numberposts' => $atts['post_number'],
            'orderby' => 'rand'
        );
        
        if ( isset($atts['included_ids']) && strlen($atts['included_ids']) ){
            // Sanitize integer comma separated list
            $atts['included_ids'] = array_filter( explode( ",", str_replace( ' ', '', $atts['included_ids'] ) ), function($e) {
                return ctype_digit($e);
            });
						
            // Clean single list values
            $atts['included_ids'] = array_map( function($e) {
                return ltrim( $e, '0' );
            }, $atts['included_ids']);

            // Use "post__in" instead of "include" parameter because it ignores numberposts parameter and random order.
            $args['post__in'] = $atts['included_ids'];
        }
        
        if ( isset($atts['excluded_ids']) && strlen($atts['excluded_ids']) ){
            // Sanitize integer comma separated list
            $atts['excluded_ids'] = array_filter( explode( ",", str_replace( ' ', '', $atts['excluded_ids'] ) ), function($e) {
                return ctype_digit($e);
            });
						
            // Clean single list values
            $atts['excluded_ids'] = array_map( function($e) {
                return ltrim( $e, '0' );
            }, $atts['excluded_ids']);

            // Use "post__not_in" instead of "exclude" parameter because it ignores numberposts parameter and random order.
            $args['post__not_in'] = $atts['excluded_ids'];
        }

        $random_contents = get_posts($args);

        if (count($random_contents)) {

            // One post
            if (count($random_contents) == 1) {
                $result = $this->build_post_link($random_contents[0], $atts['content_type']);
            }

            // Many posts
            else {
                foreach ($random_contents as $random_content) {
                    $result .= $this->build_post_link($random_content, $atts['content_type']);
                }
            }
        }

        return $result;
    }

    /**
     * Get content text to be shown with shortcode.
     *
     * @since    1.0.0
     * @param      post		$post   		Post WordPress instance to get information from.
     * @param      string	$content_type	Type of content to show (Possible values: url, title, title_content, title_excerpt, content, excerpt).
     */
    private function build_post_link($post, $content_type = 'title')
    {
        if (in_array($content_type, array('url'))) {
            return get_permalink($post->ID);
        }

        $result = '<span class="random_post">';

        if (in_array($content_type, array('url', 'title', 'title_content', 'title_excerpt'))) {
            $result .= '<a class="random_post_link" href="'. get_permalink($post->ID) .'">'. $post->post_title .'</a>';
        }

        if (in_array($content_type, array('title_content', 'content'))) {
            $content = apply_filters('the_content', get_post_field('post_content', $post->ID));
            $result .= '<div class="random_post_content">'. $content .'</div>';
        } elseif (in_array($content_type, array('title_excerpt', 'excerpt'))) {
            $result .= '<div class="random_post_content">'. get_the_excerpt($post) .'</div>';
        }

        $result .= '</span>';

        return $result;
    }
}
