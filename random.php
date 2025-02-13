<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.scribit.it/
 * @since             1.0.0
 * @package           random
 *
 * @wordpress-plugin
 * Plugin Name:       Random
 * Plugin URI:        https://www.scribit.it/en/wordpress-plugins/get-random-contents/
 * Description:       Random, a plugin designed to insert random contents, coming from pages, posts and other types, into your website.
 * Version:           1.3
 * Author:            Scribit
 * Author URI:        https://www.scribit.it/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       random
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

require_once plugin_dir_path(__FILE__) . 'random-consts.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-random-activator.php
 */
function activate_random()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-random-activator.php';
    Random_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-random-deactivator.php
 */
function deactivate_random()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-random-deactivator.php';
    Random_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_random');
register_deactivation_hook(__FILE__, 'deactivate_random');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-random.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_random()
{
    $plugin = new ScribitRandom();
    $plugin->run();
}
run_random();

function random_actions_links($links)
{
    $settings_link = '<a href="tools.php?page='. RANDOM_PLUGIN_SLUG .'"><span>' . esc_html__('Get random contents', 'random') . '</span></a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_'. plugin_basename(__FILE__), 'random_actions_links');

function random_footer_text()
{
    // Show footer only in plugin pages
    if (!strpos(get_current_screen()->id, RANDOM_PLUGIN_SLUG)) {
        return;
    }

    $url = 'https://www.scribit.it';
    echo '<span class="scribit_credit">'.sprintf('%s <a href="%s" target="_blank">Scribit</a>', esc_html(__('Random is powered by', 'random')), esc_url($url)).'</span>';
}
add_filter('admin_footer_text', 'random_footer_text');
