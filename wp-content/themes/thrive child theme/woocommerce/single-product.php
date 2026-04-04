<?php
/**
 * Single product template override — with Mentors & Feedback.
 *
 * @package WooCommerce\Templates
 * @version 3.0 — Formidable Views integration
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
?>

<section class="sc-single-page" aria-label="<?php esc_attr_e( 'Course details', 'woocommerce' ); ?>">
    <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>
        <?php wc_get_template_part( 'content', 'single-product' ); ?>
    <?php endwhile; ?>
</section>

<?php
/* ── Mentors Section (Formidable View 92529) ──────────────── */
if ( function_exists( 'nanoschool_render_mentors_section' ) ) {
    echo nanoschool_render_mentors_section( 92529 );
}

/* ── Feedback Section (Formidable View 87412) ─────────────── */
if ( function_exists( 'nanoschool_render_feedback_section' ) ) {
    echo nanoschool_render_feedback_section( 87412 );
}

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
