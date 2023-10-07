<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.matebyamate.co.uk
 * @since             1.0.0
 * @package           Post_Type_Helper
 *
 * @wordpress-plugin
 * Plugin Name:       Post Type Helper
 * Plugin URI:        https://www.matebyamate.co.uk
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.2
 * Author:            George Batt
 * Author URI:        https://www.matebyamate.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-type-helper
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
	die;
}

define('POST_TYPE_HELPER_VERSION', '1.0.2');

define('POST_TYPE_HELPER_ROOT', __FILE__);

function activate_post_type_helper()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-post-type-helper-activator.php';
	Post_Type_Helper_Activator::activate();
}

function deactivate_post_type_helper()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-post-type-helper-deactivator.php';
	Post_Type_Helper_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_post_type_helper');
register_deactivation_hook(__FILE__, 'deactivate_post_type_helper');

require plugin_dir_path(__FILE__) . 'includes/class-post-type-helper.php';

function run_post_type_helper()
{

	$plugin = new Post_Type_Helper();

	$plugin->run();
}
run_post_type_helper();
