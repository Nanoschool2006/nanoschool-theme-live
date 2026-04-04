<?php
/**
 * Single product content template.
 * Enhanced: sticky CTA bar, social proof, instructor block, AEO FAQ, breadcrumb, Schema microdata.
 *
 * @package WooCommerce\Templates
 * @version 3.0 — SEO/AEO/GEO + UI enhancement pass
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    return;
}

$category_list     = wc_get_product_category_list( $product->get_id(), ' / ' );
$short_copy        = trim( wp_strip_all_tags( $product->get_short_description() ) );
$certificate_image = 'https://nanoschool.in/wp-content/uploads/2024/05/certificate1a.jpg';
$rating            = $product->get_average_rating();
$rating_count      = $product->get_rating_count();
$price             = $product->get_price();
$permalink         = get_permalink();
$name              = $product->get_name();

/* ACF fields */
$instructor   = function_exists( 'get_field' ) ? get_field( 'instructor_name', $product->get_id() ) : '';
$instructor_bio = function_exists( 'get_field' ) ? get_field( 'instructor_bio', $product->get_id() ) : '';
$enroll_count = function_exists( 'get_field' ) ? get_field( 'enrollment_count', $product->get_id() ) : '';
$level        = function_exists( 'get_field' ) ? get_field( 'course_level', $product->get_id() ) : '';
$duration     = function_exists( 'get_field' ) ? get_field( 'course_duration', $product->get_id() ) : '';

/* Course features from ACF / fallback */
$features = array();
if ( function_exists( 'get_field' ) ) {
    $acf_features = get_field( 'course_features', $product->get_id() );
    if ( is_array( $acf_features ) && ! empty( $acf_features ) ) {
        foreach ( $acf_features as $feat ) {
            if ( is_array( $feat ) && ! empty( $feat['feature'] ) ) {
                $features[] = $feat['feature'];
            } elseif ( is_string( $feat ) && '' !== $feat ) {
                $features[] = $feat;
            }
        }
    }
}
if ( empty( $features ) ) {
    $features = array(
        'Self-paced learning with lifetime access to materials',
        'Industry-aligned curriculum built by subject experts',
        'Shareable NanoSchool certificate of completion',
        'Online delivery — learn anywhere, anytime',
    );
}

/* FAQ from ACF / fallback */
$faqs = array();
if ( function_exists( 'get_field' ) ) {
    $acf_faqs = get_field( 'course_faq', $product->get_id() );
    if ( is_array( $acf_faqs ) && ! empty( $acf_faqs ) ) {
        foreach ( $acf_faqs as $row ) {
            if ( ! empty( $row['question'] ) && ! empty( $row['answer'] ) ) {
                $faqs[] = $row;
            }
        }
    }
}
if ( empty( $faqs ) ) {
    $faqs = array(
        array(
            'question' => 'What will I learn in this course?',
            'answer'   => 'You will gain industry-relevant knowledge and practical skills delivered through self-paced video modules, reading materials, and structured assessments.',
        ),
        array(
            'question' => 'Do I get a certificate after completing this course?',
            'answer'   => 'Yes. You will receive a NanoSchool verified certificate issued by NSTC, shareable on LinkedIn with a verification link.',
        ),
        array(
            'question' => 'How long does it take to finish this course?',
            'answer'   => 'Most learners finish in 4–8 weeks. You have lifetime access with no deadline.',
        ),
    );
}
?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'sc-single-product', $product ); ?> itemscope itemtype="https://schema.org/Course">
    <meta itemprop="name" content="<?php echo esc_attr( $name ); ?>">
    <meta itemprop="url" content="<?php echo esc_url( $permalink ); ?>">

    <!-- ══ Breadcrumb ══ -->
    <nav class="sc-breadcrumb sc-breadcrumb--single" aria-label="<?php esc_attr_e( 'Breadcrumb', 'woocommerce' ); ?>">
        <ol class="sc-breadcrumb__list">
            <li class="sc-breadcrumb__item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'woocommerce' ); ?></a></li>
            <li class="sc-breadcrumb__sep" aria-hidden="true">›</li>
            <li class="sc-breadcrumb__item"><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Courses', 'woocommerce' ); ?></a></li>
            <li class="sc-breadcrumb__sep" aria-hidden="true">›</li>
            <li class="sc-breadcrumb__item sc-breadcrumb__item--current" aria-current="page"><?php the_title(); ?></li>
        </ol>
    </nav>

    <div class="sc-single-product__hero">
        <div class="sc-single-product__gallery">
            <?php do_action( 'woocommerce_before_single_product_summary' ); ?>
        </div>

        <div class="summary entry-summary sc-single-product__summary">
            <?php if ( $category_list ) : ?>
                <div class="sc-single-product__categories" itemprop="about"><?php echo wp_kses_post( $category_list ); ?></div>
            <?php endif; ?>

            <?php the_title( '<h1 class="product_title entry-title sc-single-product__title" itemprop="name">', '</h1>' ); ?>

            <!-- Social proof bar -->
            <div class="sc-single-product__social-proof">
                <?php if ( $rating > 0 && $rating_count > 0 ) : ?>
                    <div class="sc-sp-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                        <meta itemprop="ratingValue" content="<?php echo esc_attr( $rating ); ?>">
                        <meta itemprop="reviewCount" content="<?php echo esc_attr( $rating_count ); ?>">
                        <meta itemprop="bestRating" content="5">
                        <?php
                        $full_stars = floor( $rating );
                        $half_star  = ( $rating - $full_stars ) >= 0.5;
                        echo str_repeat( '<span class="sc-star sc-star--full" aria-hidden="true">★</span>', $full_stars );
                        if ( $half_star ) echo '<span class="sc-star sc-star--half" aria-hidden="true">★</span>';
                        echo str_repeat( '<span class="sc-star sc-star--empty" aria-hidden="true">☆</span>', 5 - $full_stars - ( $half_star ? 1 : 0 ) );
                        ?>
                        <span class="sc-sp-rating__label"><?php echo esc_html( number_format( (float) $rating, 1 ) ); ?> (<?php echo esc_html( $rating_count ); ?> <?php esc_html_e( 'reviews', 'woocommerce' ); ?>)</span>
                    </div>
                <?php endif; ?>
                <?php if ( $enroll_count ) : ?>
                    <span class="sc-sp-enrolled">
                        <svg aria-hidden="true" viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        <?php echo esc_html( $enroll_count ); ?> <?php esc_html_e( 'enrolled', 'woocommerce' ); ?>
                    </span>
                <?php endif; ?>
                <?php if ( $level ) : ?>
                    <span class="sc-pill sc-pill--level"><?php echo esc_html( $level ); ?></span>
                <?php endif; ?>
                <?php if ( $duration ) : ?>
                    <span class="sc-pill sc-pill--duration"><?php echo esc_html( $duration ); ?></span>
                <?php endif; ?>
            </div>

            <div class="sc-single-product__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>">
                <meta itemprop="price" content="<?php echo esc_attr( $price ); ?>">
                <meta itemprop="availability" content="https://schema.org/InStock">
                <meta itemprop="url" content="<?php echo esc_url( $permalink ); ?>">
                <?php woocommerce_template_single_price(); ?>
            </div>

            <?php if ( $short_copy ) : ?>
                <div class="sc-single-product__excerpt" itemprop="description">
                    <?php woocommerce_template_single_excerpt(); ?>
                </div>
            <?php endif; ?>

            <!-- Course feature checklist -->
            <ul class="sc-single-product__features" aria-label="<?php esc_attr_e( 'Course features', 'woocommerce' ); ?>">
                <?php foreach ( $features as $feature ) : ?>
                    <li itemprop="coursePrerequisites"><?php echo esc_html( $feature ); ?></li>
                <?php endforeach; ?>
            </ul>

            <!-- Instructor block -->
            <?php if ( $instructor ) : ?>
            <div class="sc-instructor" itemscope itemtype="https://schema.org/Person">
                <div class="sc-instructor__avatar" aria-hidden="true">
                    <?php echo esc_html( mb_substr( $instructor, 0, 1 ) ); ?>
                </div>
                <div class="sc-instructor__info">
                    <span class="sc-instructor__label"><?php esc_html_e( 'Instructor', 'woocommerce' ); ?></span>
                    <span class="sc-instructor__name" itemprop="name"><?php echo esc_html( $instructor ); ?></span>
                    <?php if ( $instructor_bio ) : ?>
                        <p class="sc-instructor__bio" itemprop="description"><?php echo esc_html( $instructor_bio ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php else : ?>
            <div class="sc-instructor">
                <div class="sc-instructor__avatar" aria-hidden="true">N</div>
                <div class="sc-instructor__info">
                    <span class="sc-instructor__label"><?php esc_html_e( 'Instructor', 'woocommerce' ); ?></span>
                    <span class="sc-instructor__name">NanoSchool Faculty</span>
                    <p class="sc-instructor__bio"><?php esc_html_e( 'Our courses are designed and maintained by industry experts and academic practitioners at NanoSchool Technology Center (NSTC).', 'woocommerce' ); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Delivery + certification cards -->
            <div class="sc-single-product__support">
                <div class="sc-single-product__support-card">
                    <svg aria-hidden="true" class="sc-support-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="sc-single-product__support-label"><?php esc_html_e( 'Delivery', 'woocommerce' ); ?></span>
                    <strong><?php esc_html_e( 'Instant online access upon enrollment', 'woocommerce' ); ?></strong>
                </div>
                <div class="sc-single-product__support-card">
                    <svg aria-hidden="true" class="sc-support-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <span class="sc-single-product__support-label"><?php esc_html_e( 'Certification', 'woocommerce' ); ?></span>
                    <strong><?php esc_html_e( 'NanoSchool verified certificate included', 'woocommerce' ); ?></strong>
                </div>
            </div>

            <div class="sc-single-product__cart">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>

            <div class="sc-single-product__meta">
                <?php woocommerce_template_single_meta(); ?>
            </div>
        </div>
    </div>

    <!-- ══ Certificate + Tabs ══ -->
    <div class="sc-single-product__details">
        <aside class="sc-single-product__certificate">
            <div class="sc-certificate-card">
                <div class="sc-certificate-card__image-wrap">
                    <img class="sc-certificate-card__image" src="<?php echo esc_url( $certificate_image ); ?>" alt="<?php esc_attr_e( 'NanoSchool course certificate preview — shareable on LinkedIn', 'woocommerce' ); ?>" loading="lazy" width="400" height="280">
                </div>
                <h3 class="sc-certificate-card__title"><?php esc_html_e( "What You'll Gain", 'woocommerce' ); ?></h3>
                <ul class="sc-certificate-card__list">
                    <li><?php esc_html_e( 'Shareable course certificate', 'woocommerce' ); ?></li>
                    <li><?php esc_html_e( 'Focused modules with guided progression', 'woocommerce' ); ?></li>
                    <li><?php esc_html_e( 'Lifetime access to all course materials', 'woocommerce' ); ?></li>
                    <li><?php esc_html_e( 'LinkedIn-shareable with verification link', 'woocommerce' ); ?></li>
                </ul>
                <a class="sc-certificate-card__link" href="<?php echo esc_url( $certificate_image ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Preview Certificate', 'woocommerce' ); ?></a>
            </div>
        </aside>

        <div class="sc-single-product__tabs-wrap">
            <?php do_action( 'woocommerce_after_single_product_summary' ); ?>
        </div>
    </div>

    <!-- ══ AEO/GEO — FAQ Section ══ -->
    <?php if ( ! empty( $faqs ) ) : ?>
    <section class="sc-single-product__faq" aria-labelledby="sc-faq-title">
        <div class="sc-faq-container">
            <h2 class="sc-faq-title" id="sc-faq-title"><?php esc_html_e( 'Frequently Asked Questions', 'woocommerce' ); ?></h2>
            <div class="sc-faq-list">
                <?php foreach ( $faqs as $faq ) : ?>
                <details class="sc-faq-item">
                    <summary class="sc-faq-item__q">
                        <?php echo esc_html( $faq['question'] ); ?>
                        <span class="sc-faq-item__chevron" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16"><path d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <p class="sc-faq-item__a"><?php echo esc_html( $faq['answer'] ); ?></p>
                </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</article>

<!-- ══ Sticky Enroll CTA (appears after scrolling) ══ -->
<div class="sc-sticky-cta" id="sc-sticky-cta" aria-hidden="true">
    <div class="sc-sticky-cta__inner">
        <span class="sc-sticky-cta__title"><?php the_title(); ?></span>
        <div class="sc-sticky-cta__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
        <?php woocommerce_template_single_add_to_cart(); ?>
    </div>
</div>

<script>
(function() {
    var cta    = document.getElementById('sc-sticky-cta');
    var anchor = document.querySelector('.sc-single-product__cart');
    if (!cta || !anchor) return;
    var io = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            cta.classList.toggle('is-visible', !e.isIntersecting);
            cta.setAttribute('aria-hidden', e.isIntersecting ? 'true' : 'false');
        });
    }, { threshold: 0 });
    io.observe(anchor);
})();
</script>


<!-- Formidable Views: Mentors & Feedback -->
<?php
if ( function_exists( 'nanoschool_render_mentors_section' ) ) {
    echo nanoschool_render_mentors_section( 92529 );
}
if ( function_exists( 'nanoschool_render_feedback_section' ) ) {
    echo nanoschool_render_feedback_section( 87412 );
}
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
