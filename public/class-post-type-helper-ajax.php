<?php

class Post_Type_Helper_Ajax
{
    public function get_posts()
    {
        $post_type = isset($_GET['type']) ? $_GET['type'] : '';
        $page      = isset($_GET['page']) ? absint($_GET['page']) : 1;

        $query     = isset($_GET['q']) ? $_GET['q'] : '';

        $taxonomy_names = get_object_taxonomies($post_type);

        $taxonomy_slug_map = array_reduce($taxonomy_names, function ($acc, $curr) {
            $object = get_taxonomy($curr);
            return array_merge($acc, array($object->rewrite['slug'] => $curr));
        }, array());

        $valid_keys = array_merge(
            array_keys($taxonomy_slug_map),
            array('page')
        );

        $parts = explode('/', $query);
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

        if (!in_array($post_type, Post_Type_Helper_Public::instance()->post_types(), true)) {
            return;
        }

        $query_args = array(
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => get_option('posts_per_page'),
            'paged'          => $page,
            'q'              => $query
        );

        //
        $tax_query = array();

        foreach ($query_properties as $query_key => $query_value) {
            if ($query_key === 'page') {
                $query_args['paged'] = $query_value[0];
            } else {
                $tax_query[] = array(
                    'taxonomy' => $taxonomy_slug_map[$query_key],
                    'field'       => 'slug',
                    'terms'       => $query_value
                );
            }
        }

        // $query->set('tax_query', $tax_query);
        //

        // $taxonomies = get_object_taxonomies($post_type);
        // $tax_query  = array();

        // foreach ($taxonomies as $taxonomy) {
        //     if (!isset($_GET[$taxonomy])) {
        //         continue;
        //     }

        //     $tax_query[] = array(
        //         'taxonomy' => $taxonomy,
        //         'terms'    => array_map('trim', explode(',', $_GET[$taxonomy])),
        //         'field'    => 'slug',
        //     );
        // }

        $query_args['tax_query'] = $tax_query + array('relation' => 'AND');

        $query = new WP_Query($query_args);
        $items = array();

        query_posts($query_args);

        $filters_markup = pth_yield('pth_archive_filters');
        $cta_markup = pth_yield('pth_archive_cta');
        $hero_markup = pth_yield('pth_archive_hero');
        $hero_bottom_markup = pth_yield('pth_archive_hero_bottom');

        while (have_posts()) {
            the_post();

            $items[] = array(
                'id'     => get_the_id(),
                'markup' => pth_yield(function () {
                    get_template_part('template-parts/content/content', get_post_type());
                })
            );
        }

        wp_send_json(array(
            'filtersContainer' => array(
                'markup' => $filters_markup
            ),
            'cta'        => array(
                'markup' => $cta_markup
            ),
            'hero'       => array(
                'markup' => $hero_markup
            ),
            'heroBottom'       => array(
                'markup' => $hero_bottom_markup
            ),
            'items'      => $items,
            'total'      => $query->found_posts,
            'totalPages' => $query->max_num_pages
        ));
    }
}
