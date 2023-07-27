<?php

$depth = isset($depth) ? $depth : 0;

?>
<ul style="<?php echo 'padding-left: ' . (20 * $depth) . 'px'; ?>">
    <?php foreach ($terms as $term) : ?>
        <li style="<?php echo pth_term_is_usable($term) ? '' : 'opacity: 0.5; pointer-events: none;'; ?>">
            <label>
                <input class="filter" type="checkbox" name="<?php esc_attr_e($filter->name); ?>" value="<?php esc_attr_e($term->slug); ?>" <?php checked(pth_is_term($term)); ?> />
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