<?php
/**
 * Archive Product — Nanoschool Custom Override
 * Clean grid layout for course listings
 *
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
?>

<div class="ns-hero-section" id="ns-hero" style="display: none;">
    <div class="ns-container">
        <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
            <div class="ns-hero-breadcrumb">
                <?php woocommerce_breadcrumb(); ?>
            </div>
        <?php endif; ?>

        <div class="ns-hero-content">
            <div class="ns-hero-badge">Expert Research Education Since 2006</div>
            <h1 class="ns-hero-title"><?php woocommerce_page_title(); ?></h1>
            <p class="ns-hero-subtitle">
                Master advanced domains in AI, Biotechnology, and Nanotechnology with specialized courses 
                designed for researchers, academics, and industry leaders.
            </p>
            
            <div class="ns-hero-search">
                <?php get_product_search_form(); ?>
            </div>
        </div>

        <div class="ns-hero-stats">
            <div class="ns-stat-item">
                <span class="ns-stat-num">400+</span>
                <span class="ns-stat-label">Specialized Courses</span>
            </div>
            <div class="ns-stat-item">
                <span class="ns-stat-num">70,000+</span>
                <span class="ns-stat-label">Learners Worldwide</span>
            </div>
            <div class="ns-stat-item">
                <span class="ns-stat-num">95+</span>
                <span class="ns-stat-label">Countries Represented</span>
            </div>
            <div class="ns-stat-item">
                <span class="ns-stat-num">AEO-Ready</span>
                <span class="ns-stat-label">Search-Optimized</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var hero = document.getElementById("ns-hero");
    var topSection = document.getElementById("theme-top-section");
    if (hero && topSection) {
        topSection.appendChild(hero);
        hero.style.display = "block";
    } else if (hero) {
        hero.style.display = "block"; // fallback if thrive layout is stripped
    }
});
</script>

<?php
/**
 * Hide default notices/description if needed since we added them above
 * but we still need the notices to display if there are any errors.
 */
?>
<div class="ns-notices-container">
    <?php wc_print_notices(); ?>
</div>

<?php
if ( woocommerce_product_loop() ) {

    /**
     * Before shop loop hooks
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    do_action( 'woocommerce_before_shop_loop' );

    woocommerce_product_loop_start();

    if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();
            do_action( 'woocommerce_shop_loop' );
            wc_get_template_part( 'content', 'product' );
        }
    }

    woocommerce_product_loop_end();

    /**
     * After shop loop hooks
     * @hooked woocommerce_pagination - 10
     */
    do_action( 'woocommerce_after_shop_loop' );

} else {
    do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );

// No sidebar — full-width modern layout
// do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
