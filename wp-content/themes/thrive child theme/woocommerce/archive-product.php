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

<?php
/* Hero injected by ns_hero_inject() in functions.php - do not add here */
?>


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
