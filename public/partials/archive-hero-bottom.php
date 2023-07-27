<div class="archive-hero-bottom" style="background: rgba(255,255,255,.5); height: 200px">

    <?php 
    if ($bottom_image = pth_get_archive_image_bottom()) : 
    ?>
        <div style="float: left; width: 200px">
            <strong>Image:</strong>
            <?php echo wp_get_attachment_image($bottom_image['ID'], 'medium', false, array('style' => 'display: block; width: 200px')); ?>
        </div>
    <?php 
    endif; 
    ?>

    <?php if ($bottom_heading = pth_get_archive_heading_bottom()) : ?>
        <div>
            <strong>Heading:</strong>
            <?php echo pth_get_archive_heading_bottom(); ?>
        </div>
    <?php endif; ?>

    <?php if ($bottom_description = pth_get_archive_description_bottom()) : ?>
        <div>
            <strong>Description:</strong>
            <?php echo esc_html($description); ?>
        </div>
    <?php endif; ?>

    </div>