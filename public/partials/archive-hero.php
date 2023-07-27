<header class="hero" style="background: rgba(255,255,255,.5); height: 200px">

    <?php if ($image = pth_get_archive_image()) : ?>
        <div style="float: left; width: 200px">
            <strong>Image:</strong>
            <?php echo wp_get_attachment_image($image['ID'], 'medium', false, array('style' => 'display: block; width: 200px')); ?>
        </div>
    <?php endif; ?>

    <?php if ($preheading = pth_get_archive_preheading()) : ?>
        <div>
            <strong>Preheading:</strong>
            <?php echo $preheading; ?>
        </div>
    <?php endif; ?>

    <?php if ($heading = pth_get_archive_heading()) : ?>
        <div>
            <strong>Heading:</strong>
            <?php echo pth_get_archive_heading(); ?>
        </div>
    <?php endif; ?>

    <?php if ($description = pth_get_archive_description()) : ?>
        <div>
            <strong>Description:</strong>
            <?php echo esc_html($description); ?>
        </div>
    <?php endif; ?>

</header>