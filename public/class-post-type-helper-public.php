<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.matebyamate.co.uk
 * @since      1.0.0
 *
 * @package    Post_Type_Helper
 * @subpackage Post_Type_Helper/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Post_Type_Helper
 * @subpackage Post_Type_Helper/public
 * @author     George Batt <gb5510@gmail.com>
 */
class Post_Type_Helper_Public
{
	private $taxonomies = array();

	private $post_types = array();

	public $unused_term_query = null;

	private function curl_get_file_contents($URL)
	{
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_URL, $URL);
		$contents = curl_exec($c);
		curl_close($c);

		if ($contents) return $contents;
		else return FALSE;
	}

	public function enqueue_scripts()
	{
		$dist_url = path_join(plugin_dir_url(POST_TYPE_HELPER_ROOT), 'dist');

		$manifest = json_decode($this->curl_get_file_contents(path_join($dist_url, 'manifest.json')), true);

		// $manifest = json_decode(file_get_contents(path_join($dist_url, 'manifest.json')), true);

		if (isset($manifest['post-type-archive.js']) && is_archive()) {
			$post_type = get_post_type_object(get_post_type());

			if ($post_type) {
				wp_register_script(
					'post-type-archive',
					path_join($dist_url, $manifest['post-type-archive.js']),
					array('jquery'),
					false,
					true
				);
				wp_localize_script(
					'post-type-archive',
					'PTH',
					array(
						'ajaxUrl'  => admin_url('/admin-ajax.php'),
						'postType' => $post_type->name,
						'postTypeSlug' => isset($post_type->rewrite['slug']) ? $post_type->rewrite['slug'] : '',
						'homeUrl'  => home_url(),
					)
				);
				wp_enqueue_script('post-type-archive');
			}
		}
	}

	public function post_types()
	{
		return $this->post_types;
	}

	public function instantiate()
	{
		$this->unused_term_query = Post_Type_Helper_Empty_Term_Query::instance();
	}

	public function register_taxonomies()
	{
		$taxonomy_configs = apply_filters('pt_helper_taxonomies', array());

		foreach ($taxonomy_configs as $taxonomy => $taxonomy_config) {
			register_taxonomy($taxonomy, array(), $taxonomy_config);

			$this->taxonomies[] = $taxonomy;
		}
	}

	public function register_post_types()
	{
		$post_type_configs = apply_filters('pt_helper_post_types', array());

		foreach ($post_type_configs as $post_type => $post_type_config) {
			register_post_type($post_type, $post_type_config);

			$this->post_types[] = $post_type;

			if (isset($post_type_config['taxonomies'])) {
				foreach ($post_type_config['taxonomies'] as $taxonomy) {
					register_taxonomy_for_object_type($taxonomy, $post_type);
				}
			}
		}
	}

	public function add_rewrite_rules()
	{
		foreach ($this->post_types as $post_type) {
			$post_type_object = get_post_type_object($post_type);
			$post_type_slug = isset($post_type_object->rewrite['slug']) ? $post_type_object->rewrite['slug'] : str_replace('_', '-', $post_type);

			add_rewrite_rule(
				"{$post_type_slug}\/([^\/]+)\/(.+)",
				"index.php?post_type={$post_type}&q=\$matches[1]/\$matches[2]",
				"top"
			);
		}
	}

	public function register_query_vars($query_vars)
	{
		$query_vars[] = 'q';
		return $query_vars;
	}

	public function inject_custom_tax_query(WP_Query $query)
	{
		if (!$query->is_main_query() || !$query->get('q')) {
			return;
		}

		$post_type = $query->get('post_type');

		if (!in_array($query->get('post_type'), $this->post_types, true)) {
			return;
		}

		$taxonomy_names = get_object_taxonomies($post_type);

		$taxonomy_slug_map = array_reduce($taxonomy_names, function ($acc, $curr) {
			$object = get_taxonomy($curr);
			return array_merge($acc, array($object->rewrite['slug'] => $curr));
		}, array());

		$valid_keys = array_merge(
			array_keys($taxonomy_slug_map),
			array('page')
		);

		$parts = explode('/', $query->get('q'));
		$query_properties = array();
		$current_key = null;

		foreach ($parts as $part) {
			if (in_array($part, $valid_keys, true)) {
				if (!isset($query_properties[$part])) {
					$query_properties[$part] = array();
				}
				$current_key = $part;
			} elseif ($current_key !== null) {
				$query_properties[$current_key][] = $part;
			}
		}

		$tax_query = $query->get('tax_query', array());

		foreach ($query_properties as $query_key => $query_value) {
			if ($query_key === 'page') {
				$query->set('paged', $query_value[0]);
			} else {
				$tax_query[] = array(
					'taxonomy' => $taxonomy_slug_map[$query_key],
					'field'	   => 'slug',
					'terms'	   => $query_value
				);
			}
		}

		$query->set('tax_query', $tax_query);

		return;
	}

	public function get_custom_hero_by_query()
	{
		$post_type = get_query_var('post_type');
		$q = get_query_var('q');

		if (!$q) {
			return null;
		}

		$post_type_options = get_field("{$post_type}_options", 'options');
		if (empty($post_type_options['custom_rules'])) {
			return null;
		}

		foreach ($post_type_options['custom_rules'] as $custom_rule) {
			if (empty($custom_rule['paths'])) {
				continue;
			}

			foreach ($custom_rule['paths'] as $path) {
				if (!empty($custom_rule['hero']) && !empty($path['path']) && pth_query_is_the_same($q, $path['path'])) {
					return $custom_rule['hero'];
				}
			}
		}

		return null;
	}

	public function get_pth_term_link($termlink, $term, $taxonomy_name)
	{
		$post_type_names = $this->post_types();
		$linked_post_type = null;

		foreach ($post_type_names as $post_type_name) {
			$taxonomies = get_object_taxonomies($post_type_name);
			$post_type = get_post_type_object($post_type_name);

			if (in_array($taxonomy_name, $taxonomies, true)) {
				$linked_post_type = $post_type;
				break;
			}
		}

		if (!$linked_post_type) {
			return $termlink;
		}

		$taxonomy = get_taxonomy($taxonomy_name);
		return home_url("/{$post_type->rewrite["slug"]}/{$taxonomy->rewrite["slug"]}/{$term->slug}/");
	}

	public function register_acf_fields()
	{
		if (!function_exists('acf_add_options_page')) {
			return;
		}

		$options_page = array(
			'page_title' 	=> 'Post Type Heroes',
			'menu_title'	=> 'Post Type Heroes',
			'menu_slug' 	=> 'pth-post-type-heroes'
		);

		$archive_hero_post_type = array(
			'key' => 'archive_options_post_types',
			'title' => 'Archive Options - Post Types',
			'fields' => array_map(__CLASS__ . '::archive_post_type_hero_field_args', $this->post_types),
			'location' => array(
				array(
					array(
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'pth-post-type-heroes',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		);

		$archive_hero_term = array(
			'key' => 'archive_options_term',
			'title' => 'Archive Options - Term',
			'fields' => array(
				self::archive_hero_field_args("term"),
			),
			'location' => array(
				array(
					array(
						'param' => 'taxonomy',
						'operator' => '==',
						'value' => 'all',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		);

		acf_add_options_page($options_page);
		acf_add_local_field_group($archive_hero_post_type);
		acf_add_local_field_group($archive_hero_term);
	}

	static private function archive_post_type_hero_field_args($post_type)
	{
		$post_type_object = get_post_type_object($post_type);
		$post_type_link = get_post_type_archive_link($post_type);

		return array(
			'key' => 'archive_post_type_hero_' . $post_type,
			'label' => $post_type_object->label,
			'name' => "{$post_type}_options",
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'row',
			'sub_fields' => array(
				self::archive_hero_field_args("post_type_{$post_type}"),
				array(
					'key' => 'archive_post_type_custom_rule_hero_' . $post_type,
					'label' => 'Custom Rules',
					'name' => 'custom_rules',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Rule',
					'sub_fields' => array(
						array(
							'key' => 'archive_post_type_custom_rule_hero_' . $post_type . '_query_tab',
							'label' => 'Query',
							'name' => '',
							'type' => 'tab',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'placement' => 'top',
							'endpoint' => 0,
						),
						array(
							'key' => 'archive_post_type_custom_rule_hero_' . $post_type . '_paths',
							'label' => 'Paths',
							'name' => 'paths',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 1,
							'max' => 0,
							'layout' => 'row',
							'button_label' => 'Add Path',
							'sub_fields' => array(
								array(
									'key' => 'archive_post_type_custom_rule_hero_' . $post_type . '_path',
									'label' => 'Path',
									'name' => 'path',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => $post_type_link,
									'append' => '',
									'maxlength' => '',
								)
							)
						),
						array(
							'key' => 'archive_post_type_custom_rule_hero_' . $post_type . '_options_tab',
							'label' => 'Options',
							'name' => '',
							'type' => 'tab',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'placement' => 'top',
							'endpoint' => 0,
						),
						self::archive_hero_field_args("post_type_custom_rule")
					),
				)
			),
		);
	}

	static public function archive_custom_rule_tax_args(\WP_Taxonomy $taxonomy)
	{
		return array(
			'key' => "archive_post_type_custom_rule_hero_taxonomy_{$taxonomy->name}",
			'label' => $taxonomy->labels->singular_name,
			'name' => $taxonomy->name,
			'type' => 'select',
			'instructions' => '',
			'choices' => wp_list_pluck(get_terms(array('taxonomy' => $taxonomy->name, 'number' => -1)), 'name', 'term_id'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => array(),
			'allow_null' => 0,
			'multiple' => 1,
			'ui' => 1,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		);
	}

	static public function archive_hero_field_args($suffix)
	{
		return array(
			'key' => "archive_hero_{$suffix}",
			'label' => 'Hero',
			'name' => 'hero',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'row',
			'sub_fields' => array(
				array(
					'key' => "archive_hero_preheading_{$suffix}",
					'label' => 'Preheading',
					'name' => 'preheading',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => "archive_hero_heading_{$suffix}",
					'label' => 'Heading',
					'name' => 'heading',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => "archive_hero_image_{$suffix}",
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => "archive_hero_description_{$suffix}",
					'label' => 'Description',
					'name' => 'description',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => '',
					'new_lines' => '',
				),
				array(
					'key' => "archive_hero_bottom_image_{$suffix}",
					'label' => 'Bottom Image',
					'name' => 'bottom_image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => "archive_hero_bottom_heading_{$suffix}",
					'label' => 'Bottom Heading',
					'name' => 'bottom_heading',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => "archive_hero_bottom_description_{$suffix}",
					'label' => 'Bottom Description',
					'name' => 'bottom_description',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => '',
					'new_lines' => '',
				),
			),
		);
	}

	private static $instance = null;

	public static function instance()
	{
		if (self::$instance == null) {
			self::$instance = new Post_Type_Helper_Public();
		}

		return self::$instance;
	}
}
