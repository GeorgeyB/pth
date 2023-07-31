<div class="filters-container pr-4">
    <button class="filter-btn cursor-pointer lg:cursor-auto min-w-full lg:w-auto border-2 border-primary lg:border-0 px-10 py-2 lg:px-0 lg:py-0 rounded-lg lg:rounded-none text-primary text-xl uppercase tracking-widest font-semibold mb-8 text-center lg:text-left flex items-center">
        <img src="<?php echo get_template_directory_uri() . '/images/filter-icon.png' ?>" alt="" class="mr-2 lg:hidden">
        Filter Awards
    </button>
    <!-- <h2
        class="min-w-full lg:w-auto border-2 border-primary lg:border-0 px-10 py-2 lg:px-0 lg:py-0 rounded-lg lg:rounded-none text-primary text-xl uppercase tracking-widest font-semibold mb-8 text-center lg:text-left flex items-center">
        Filter Awards</h2> -->
    <?php foreach (pth_get_archive_filters(0) as $filter) : ?>
        <div class="filters hidden lg:block mb-6">
            <h4 class="filter-label text-primary uppercase tracking-widest font-bold mb-2 cursor-pointer filter-arrow-down text-center lg:text-left">
                <?php echo $filter->label; ?>
            </h4>
            <?php pth_template('archive-filter-terms', array(
                'terms'  => $filter->terms,
                'filter' => $filter
            )); ?>
        </div>
    <?php endforeach; ?>

    <!-- <div class="clearFilters hidden justify-center lg:flex lg:justify-start">
        <a href="<?php
                    // get_site_url() . '/awards/' 
                    ?>">Clear Filters</a>
    </div> -->

    <div class="filter-label text-primary uppercase tracking-widest font-bold mb-2 cursor-pointer text-center lg:text-left">
        <a href="<?php echo get_site_url() . '/?post_type=award' ?>">Clear Filters</a>
    </div>
</div>