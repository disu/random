<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    random
 * @subpackage random/includes
 * @author     Scribit <wordpress@scribit.it>
 */
class Random_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        // To redirect to plugin page, after its activation, the plugin use an option that will be deleted on admin_init hook.
		add_option( 'activated_plugin', RANDOM_PLUGIN_SLUG );
    }
}
