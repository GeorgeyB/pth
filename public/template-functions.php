<?php

function pth_resolve_template_path($path)
{
    return "pth/{$path}.php";
}

function pth_get_template($name, $args = array())
{
    $name = (array) $name;
    $pathlist = array_map('pth_resolve_template_path', $name);

    $include_path = locate_template($pathlist, true, false, $args);

    if ($include_path) {
        return;
    }

    return pth_yield(function () use ($name, $args) {
        foreach ($name as $path) {
            $filepath = path_join(dirname(__FILE__), "partials/{$path}.php");

            if (file_exists($filepath)) {
                require($filepath);
                break;
            }
        }
    });
}

function pth_template($name, $args = array())
{
    echo pth_get_template($name, $args);
}

function pth_archive_hero()
{
    pth_template('archive-hero');
}

function pth_archive_hero_bottom()
{
    pth_template('archive-hero-bottom');
}

function pth_archive_filters()
{
    pth_template('archive-filters');
}

function pth_archive_load_more()
{
    pth_template('archive-load-more');
}

function pth_get_archive_cta($post_type = null)
{
    if ($post_type === null) {
        global $wp_query;

        if ($wp_query->is_main_query() && $wp_query->is_archive() && is_string($wp_query->get('post_type'))) {
            $post_type = $wp_query->get('post_type');
        }
    }

    return pth_get_template(array(
        "archive-cta-{$post_type}",
        "archive-cta"
    ));
}

function pth_archive_cta($post_type = null)
{
    echo pth_get_archive_cta($post_type);
}

function pth_get_archive_preheading()
{
    $hero = pth_get_archive_hero();

    if (!isset($hero['preheading'])) {
        return '';
    }

    return $hero['preheading'];
}

function pth_get_archive_heading()
{
    $hero = pth_get_archive_hero();

    if (!isset($hero['heading'])) {
        return '';
    }

    return $hero['heading'];
}


function pth_get_archive_heading_bottom()
{
    $hero = pth_get_archive_hero();

    if (!isset($hero['bottom_heading'])) {
        return '';
    }

    return $hero['bottom_heading'];
}



function pth_get_archive_image()
{
    $hero = pth_get_archive_hero();

    if (!isset($hero['image']) || !$hero['image']) {
        return null;
    }

    return $hero['image'];
}

function pth_get_archive_image_bottom()
{
    $hero = pth_get_archive_hero();

    if (!isset($hero['bottom_image']) || !$hero['bottom_image']) {
        return null;
    }

    return $hero['bottom_image'];
}

function pth_get_archive_description()
{
    $hero = pth_get_archive_hero();

    if (!isset($hero['description'])) {
        return '';
    }

    return $hero['description'];
}

function pth_get_archive_description_bottom()
{
    $hero = pth_get_archive_hero();

    if (!isset($hero['bottom_description'])) {
        return '';
    }

    return $hero['bottom_description'];
}
