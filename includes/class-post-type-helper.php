<?php

/**
 * @link       https://www.matebyamate.co.uk
 * @since      1.0.0
 *
 * @package    Post_Type_Helper
 * @subpackage Post_Type_Helper/includes
 */

/**
 * @since      1.0.0
 * @package    Post_Type_Helper
 * @subpackage Post_Type_Helper/includes
 * @author     George Batt <gb5510@gmail.com>
 */
class Post_Type_Helper
{

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Post_Type_Helper_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('POST_TYPE_HELPER_VERSION')) {
			$this->version = POST_TYPE_HELPER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'post-type-helper';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ajax_hooks();
	}

	/**
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-post-type-helper-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-post-type-helper-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-post-type-helper-public.php';

		/**
		 * The class responsible for defining all actions that occur AJAX requests
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-post-type-helper-ajax.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-post-type-helper-term-query.php';

		/**
		 * Template functions
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/template-functions.php';

		/**
		 * Helper functions
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/helper-functions.php';

		$this->loader = new Post_Type_Helper_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Post_Type_Helper_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = Post_Type_Helper_Public::instance($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$this->loader->add_action('init', $plugin_public, 'register_taxonomies');
		$this->loader->add_action('init', $plugin_public, 'register_post_types');
		$this->loader->add_action('init', $plugin_public, 'add_rewrite_rules');
		$this->loader->add_action('init', $plugin_public, 'register_acf_fields');

		$this->loader->add_action('wp', $plugin_public, 'instantiate');
		$this->loader->add_action('query_vars', $plugin_public, 'register_query_vars');
		$this->loader->add_action('pre_get_posts', $plugin_public, 'inject_custom_tax_query');

		$this->loader->add_filter('pth_get_archive_hero', $plugin_public, 'get_custom_hero_by_query');
	}

	private function define_ajax_hooks()
	{
		$plugin_ajax = new Post_Type_Helper_Ajax();

		$this->loader->add_action('wp_ajax_get-posts', $plugin_ajax, 'get_posts');
		$this->loader->add_action('wp_ajax_nopriv_get-posts', $plugin_ajax, 'get_posts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Post_Type_Helper_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
