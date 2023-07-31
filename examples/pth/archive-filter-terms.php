<?php

$depth = isset($args['depth']) ? $args['depth'] : 0;
$terms = isset($args['terms']) ? $args['terms'] : array();
$filter = isset($args['filter']) ? $args['filter'] : null;

if (empty($terms) || !$filter) {
    return;
}

?>
<ul style="<?php echo 'padding-left: ' . ($depth * 20) . 'px'; ?>">
    <?php foreach ($terms as $term) : ?>
        <li class="filter-option mb-2 text-center lg:text-left" style="<?php echo pth_term_is_usable($term) ? '' : 'opacity: 0.5; pointer-events: none;'; ?>">
            <label>
                <input class="filter" type="checkbox" name="<?php esc_attr_e(isset($filter->rewrite['slug']) ? $filter->rewrite['slug'] : $filter->name); ?>" value="<?php esc_attr_e($term->slug); ?>" <?php checked(pth_is_term($term)); ?> />
                <?php echo $term->name; ?>
            </label>
            <?php $children = get_term_children($term->term_id, $term->taxonomy); ?>
            <?php if (count($children) > 0) : ?>
                <?php pth_template('archive-filter-terms', array(
                    'terms'  => array_map('get_term', $children),
                    'filter' => $filter,
                    'depth'  => $depth + 1
                )); ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>