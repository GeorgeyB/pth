<?php

function pth_yield($func)
{
    if (!is_callable($func)) {
        return '';
    }

    ob_start();
    call_user_func($func);
    return ob_get_clean();
}

function pth_get_archive_filters($parent = '')
{
    global $wp_query;

    if (!$wp_query->is_archive()) {
        return;
    }

    $post_types = array();
    $result = array();

    switch (true) {
        case $wp_query->is_tax(): {
                $taxonomy = get_taxonomy($wp_query->get('taxonomy'));
                $post_types = array();

                foreach ($taxonomy->object_type as $tax_post_type) {
                    foreach (get_object_taxonomies($tax_post_type) as $_taxonomy) {
                        $post_types = array_unique(array_merge($post_types, $taxonomy->object_type));
                    }
                }

                break;
            }
        default: {
                $post_types = $wp_query->get('post_type');
                $post_types = is_string($post_types) ? array($post_types) : $post_types;
            }
    }

    foreach ($post_types as $post_type) {
        $taxonomies = get_object_taxonomies($post_type);

        foreach ($taxonomies as $taxonomy) {
            if (isset($result[$taxonomy])) {
                continue;
            }

            $taxonomy_object = get_taxonomy($taxonomy);
            $taxonomy_object->terms = get_terms(array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
                'order'      => 'DESC',
                'parent'     => $parent
            ));
            $result[$taxonomy] = $taxonomy_object;
        }
    }

    return $result;
}

function pth_is_query_var_term($term)
{
    $terms = array_map('trim', explode(',', isset($_REQUEST[$term->taxonomy]) ? $_REQUEST[$term->taxonomy] : ''));
    return in_array($term->slug, $terms, true);
}

function pth_is_queried_object_term($term)
{
    global $wp_query;

    $current = $wp_query->get_queried_object();

    if (!is_a($term, 'WP_Term') || !is_a($current, 'WP_Term')) {
        return false;
    }

    return $current->taxonomy === $term->taxonomy && $current->term_id === $term->term_id;
}

function pth_get_annoying_props()
{
    if (wp_doing_ajax()) {
        return array(
            'q'         => $_REQUEST['q'],
            'post_type' => $_REQUEST['type']
        );
    }

    return array(
        'q'         => get_query_var('q'),
        'post_type' => get_post_type()
    );
}

function pth_is_term($term)
{
    $props = pth_get_annoying_props();
    $taxonomy_names = get_object_taxonomies($props['post_type']);

    $taxonomy_slug_map = array_reduce($taxonomy_names, function ($acc, $curr) {
        $object = get_taxonomy($curr);
        return array_merge($acc, array($curr => $object->rewrite['slug']));
    }, array());

    $valid_keys = array_merge(
        array_values($taxonomy_slug_map),
        array('page')
    );

    $parts = explode('/', $props['q']);
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

    if (!isset($taxonomy_slug_map[$term->taxonomy])) {
        return false;
    }

    if (!isset($query_properties[$taxonomy_slug_map[$term->taxonomy]])) {
        return false;
    }

    return in_array($term->slug, $query_properties[$taxonomy_slug_map[$term->taxonomy]], true);
}

function pth_get_archive_object()
{
    global $wp_query;

    $object = get_queried_object();

    if (is_a($object, 'WP_Term')) {
        // If there is more than 1 term query, get_queried_object
        // will return one of them, so fall back to the post type
        // if possible
        $tax_query = $wp_query->get('tax_query');

        if ($tax_query) {
            unset($tax_query['relation']);

            $first = reset($tax_query);

            if (count($tax_query) > 1 || (isset($first['terms']) && count($first['terms']) > 1)) {
                $post_type = $wp_query->get('post_type');
                $post_type_object = get_post_type_object($post_type);

                if (!$post_type_object) {
                    return null;
                }

                return $post_type_object;;
            }
        }

        return $object;
    }

    if (is_a($object, 'WP_Post_Type')) {
        return $object;
    }

    return null;
}

function pth_get_archive_hero_default()
{
    return array(
        'preheading'  => '',
        'heading'     => '',
        'image'       => null,
        'description' => ''
    );
}

function pth_get_archive_hero()
{
    $plugin_hero = apply_filters('pth_get_archive_hero', null);

    if ($plugin_hero) {
        return $plugin_hero;
    }

    $object = pth_get_archive_object();

    if (!$object) {
        return pth_get_archive_hero_default();
    }

    if (is_a($object, 'WP_Term')) {
        $hero = get_field('hero', $object);

        if (!$hero) {
            return pth_get_archive_hero_default();
        }

        return $hero;
    } else if (is_a($object, 'WP_Post_Type')) {
        $post_type_options = get_field('award_options', 'options');

        if (!$post_type_options || !isset($post_type_options['hero'])) {
            return pth_get_archive_hero_default();
        }

        return $post_type_options['hero'];
    }

    return pth_get_archive_hero_default();
}



function pth_get_archive_hero_bottom_default()
{
    return array(
        'preheading'  => '',
        'heading'     => '',
        'image'       => null,
        'description' => ''
    );
}


function pth_get_archive_hero_bottom()
{
    $object = pth_get_archive_object();

    if (!$object) {
        return pth_get_archive_hero_bottom_default();
    }

    if (is_a($object, 'WP_Term')) {
        $hero_bottom = get_field('hero_bottom', $object);

        if (!$hero_bottom) {
            return pth_get_archive_hero_bottom_default();
        }

        return $hero_bottom;
    } else if (is_a($object, 'WP_Post_Type')) {
        $post_type_options = get_field('award_options', 'options');

        if (!$post_type_options || !isset($post_type_options['hero_bottom'])) {
            return pth_get_archive_hero_bottom_default();
        }

        return $post_type_options['hero_bottom'];
    }

    return pth_get_archive_hero_bottom_default();
}


function pth_term_is_usable($term)
{
    return Post_Type_Helper_Empty_Term_Query::instance()->has_posts($term->term_id);
}

function pth_query_is_the_same($query1, $query2)
{
    return trim($query1, "/") === trim($query2, "/");
}
