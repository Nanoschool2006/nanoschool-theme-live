<?php
/**
 * Product card template for archive loops.
 * Enhanced with Schema.org microdata, star rating, level badge, and hover overlay.
 *
 * @package WooCommerce\Templates
 * @version 3.0 — SEO + UI enhancement pass
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$image_id      = $product->get_image_id();
$image_html    = $image_id
    ? wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'loading' => 'lazy', 'itemprop' => 'image' ) )
    : wc_placeholder_img( 'woocommerce_thumbnail' );
$category_list = wc_get_product_category_list( $product->get_id(), ' / ' );
$rating        = $product->get_average_rating();
$rating_count  = $product->get_rating_count();
$permalink     = get_permalink();
$name          = $product->get_name();

/* ACF fields */
$level    = function_exists( 'get_field' ) ? get_field( 'course_level' ) : '';
$duration = function_exists( 'get_field' ) ? get_field( 'course_duration' ) : '';
?>

<li <?php wc_product_class( 'sc-product-card', $product ); ?> itemprop="itemListElement" itemscope itemtype="https://schema.org/Course">
    <meta itemprop="url" content="<?php echo esc_url( $permalink ); ?>">
    <meta itemprop="provider" content="NanoSchool Technology Center">

    <!-- Image + Badge + Hover Overlay -->
    <a href="<?php echo esc_url( $permalink ); ?>" class="sc-product-card__image" aria-hidden="true" tabindex="-1">
        <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

        <?php if ( $level ) : ?>
            <span class="sc-product-card__badge"><?php echo esc_html( $level ); ?></span>
        <?php endif; ?>

        <div class="sc-product-card__overlay">
            <span class="sc-product-card__overlay-text"><?php esc_html_e( 'View Details', 'woocommerce' ); ?></span>
        </div>
    </a>

    <div class="sc-product-card__body">
        <!-- Star Rating -->
        <?php if ( $rating > 0 ) : ?>
            <div class="sc-product-card__rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                <meta itemprop="ratingValue" content="<?php echo esc_attr( $rating ); ?>">
                <meta itemprop="reviewCount" content="<?php echo esc_attr( $rating_count ); ?>">
                <?php
                $full_stars = floor( $rating );
                echo str_repeat( '<span class="sc-star sc-star--full">★</span>', $full_stars );
                echo str_repeat( '<span class="sc-star sc-star--empty">☆</span>', 5 - $full_stars );
                ?>
                <span class="sc-product-card__rating-count">(<?php echo esc_html( $rating_count ); ?>)</span>
            </div>
        <?php endif; ?>

        <!-- Level / Duration Pills -->
        <div class="sc-product-card__pills">
            <?php if ( $level ) : ?>
                <span class="sc-pill sc-pill--level"><?php echo esc_html( $level ); ?></span>
            <?php endif; ?>
            <?php if ( $duration ) : ?>
                <span class="sc-pill sc-pill--duration"><?php echo esc_html( $duration ); ?></span>
            <?php endif; ?>
        </div>

        <?php if ( $category_list ) : ?>
            <div class="sc-product-card__meta"><?php echo wp_kses_post( $category_list ); ?></div>
        <?php endif; ?>

        <h2 class="sc-product-card__title" itemprop="name">
            <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $name ); ?></a>
        </h2>

        <div class="sc-product-card__excerpt" itemprop="description">
            <?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?>
            <a href="<?php echo esc_url( $permalink ); ?>" class="sc-product-card__readmore"><?php esc_html_e( 'Learn more', 'woocommerce' ); ?></a>
        </div>

        <div class="sc-product-card__footer">
            <div class="sc-product-card__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>">
                <meta itemprop="price" content="<?php echo esc_attr( $product->get_price() ); ?>">
                <meta itemprop="availability" content="https://schema.org/InStock">
                <?php woocommerce_template_loop_price(); ?>
            </div>

            <div class="sc-product-card__actions">
                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                   data-quantity="1"
                   class="button product_type_simple add_to_cart_button ajax_add_to_cart sc-product-card__button"
                   data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                   aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
                   rel="nofollow">
                    <?php esc_html_e( 'Enroll Now', 'woocommerce' ); ?>
                </a>
            </div>
        </div>
    </div>
</li>