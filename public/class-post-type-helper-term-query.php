<?php

class Post_Type_Helper_Empty_Term_Query
{
    private static $instance = null;

    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new Post_Type_Helper_Empty_Term_Query();
        }

        return self::$instance;
    }

    private $terms_in_use = array();

    private $terms_with_posts = array();

    public function __construct()
    {
        $taxonomies = get_taxonomies();

        foreach ($taxonomies as $taxonomy) {
            $taxonomy_terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'count'    => -1
            ));

            foreach ($taxonomy_terms as $taxonomy_term) {
                if (!pth_is_term($taxonomy_term)) {
                    continue;
                }

                if (!isset($this->terms_in_use[$taxonomy])) {
                    $this->terms_in_use[$taxonomy] = array();
                }

                $this->terms_in_use[$taxonomy][] = $taxonomy_term;
            }
        }

        $this->terms_with_posts = array_map('absint', $this->get_terms_with_posts());
    }

    public function get_terms_with_posts()
    {
        if (empty($this->terms_in_use)) {
            return array();
        }

        global $wpdb;

        $sql = $this->create_term_query_sql();
        $terms_with_posts = $wpdb->get_col($sql);
        return $terms_with_posts;
    }

    public function create_term_query_sql()
    {
        $sub_queries = $this->create_term_sub_query_sql();
        $where = 'WHERE ' . join(" AND ", array_map(function ($q) {
            return "p.ID IN ({$q})";
        }, $sub_queries));

        $sql = "SELECT DISTINCT(t.term_id)
                FROM `wp_posts` p
                LEFT JOIN `wp_term_relationships` r
                ON p.ID = r.object_id
                LEFT JOIN `wp_terms` t
                ON r.term_taxonomy_id = t.term_id
                LEFT JOIN `wp_term_taxonomy` x
                ON t.term_id = x.term_id
                {$where}";

        return $sql;
    }

    public function create_term_sub_query_sql()
    {
        global $wpdb;

        $sql = array();
        $i = 0;

        foreach ($this->terms_in_use as $taxonomy => $terms) {
            $placeholders = implode(',', array_fill(0, count($terms), '%d'));
            $term_ids = array_map(function ($t) {
                return $t->term_id;
            }, $terms);

            $sql[] = $wpdb->prepare("SELECT
                p{$i}.ID
            FROM
                `wp_posts` p{$i}
            LEFT JOIN `wp_term_relationships` r{$i} ON
                p{$i}.ID = r{$i}.object_id
            LEFT JOIN `wp_terms` t{$i} ON
                r{$i}.term_taxonomy_id = t{$i}.term_id
            LEFT JOIN `wp_term_taxonomy` x{$i} ON
                t{$i}.term_id = x{$i}.term_id
            WHERE
                x{$i}.taxonomy = '%s' AND p{$i}.post_status = 'publish' AND t{$i}.term_id IN({$placeholders})
            ", array_merge(array($taxonomy), $term_ids));

            $i++;
        }

        return $sql;
    }

    public function filter_is_applied($taxonomy)
    {
        if (!isset($this->terms_in_use[$taxonomy])) {
            return false;
        }

        if (!count($this->terms_in_use[$taxonomy])) {
            return false;
        }

        return true;
    }

    public function has_posts($term_id)
    {
        $term = get_term($term_id);

        if ($this->filter_is_applied($term->taxonomy)) {
            return true;
        }

        if (empty($this->terms_with_posts)) {
            return true;
        }

        return in_array($term_id, $this->terms_with_posts, true);
    }
}
