<div>
    <?php foreach (pth_get_archive_filters() as $filter) : ?>
        <h3><?php echo $filter->label; ?></h3>
        <?php pth_template('archive-filter-terms', array(
            'terms'  => $filter->terms,
            'filter' => $filter
        )); ?>
    <?php endforeach; ?>
</div>