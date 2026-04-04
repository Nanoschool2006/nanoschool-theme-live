<?php
/**
 * WooCommerce overrides for the Thrive child theme.
 * Enhanced with SEO · SERP · AEO · GEO schema layer.
 *
 * @version 3.0 — Full schema + UI enhancement pass
 */

defined( 'ABSPATH' ) || exit;

/* ── Theme Support ────────────────────────────────────────────── */
add_action( 'after_setup_theme', 'sc_woo_theme_support' );
function sc_woo_theme_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

/* ── Assets ───────────────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', 'sc_woo_enqueue_assets' );
function sc_woo_enqueue_assets() {
    if ( ! function_exists( 'is_woocommerce' ) || ! ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        return;
    }

    /* Preconnect for performance */
    wp_enqueue_style(
        'sc-woo-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&family=Work+Sans:wght@400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'sc-woo-style',
        get_stylesheet_directory_uri() . '/woocommerce/style.css',
        array(),
        filemtime( get_stylesheet_directory() . '/woocommerce/style.css' )
    );
}

/* ── Preconnect Resource Hints ────────────────────────────────── */
add_action( 'wp_head', 'sc_woo_resource_hints', 1 );
function sc_woo_resource_hints() {
    if ( ! function_exists( 'is_woocommerce' ) || ! ( is_woocommerce() || is_cart() || is_checkout() ) ) {
        return;
    }
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="https://nanoschool.in">' . "\n";
}

/* ── Body Classes ─────────────────────────────────────────────── */
add_filter( 'body_class', 'sc_woo_body_classes' );
function sc_woo_body_classes( $classes ) {
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        $classes[] = 'sc-woo-theme';
    }
    return $classes;
}

/* ── Layout Configuration ─────────────────────────────────────── */
add_action( 'wp', 'sc_configure_woo_layout' );
function sc_configure_woo_layout() {
    if ( ! function_exists( 'is_woocommerce' ) || ! ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        return;
    }

    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

    if ( is_shop() || is_product_taxonomy() ) {
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
        remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
        remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
    }
}

/* ── Column Count ─────────────────────────────────────────────── */
add_filter( 'loop_shop_columns', 'sc_woo_loop_columns' );
function sc_woo_loop_columns() {
    return 3;
}

/* Products per page: 21 is divisible by 3 columns */
add_filter( 'loop_shop_per_page', 'sc_woo_products_per_page' );
function sc_woo_products_per_page() {
    return 21;
}

/* ── Related Products ─────────────────────────────────────────── */
add_filter( 'woocommerce_output_related_products_args', 'sc_related_products_args' );
function sc_related_products_args( $args ) {
    $args['posts_per_page'] = 3;
    $args['columns']        = 3;
    return $args;
}

/* ── Content Wrappers ─────────────────────────────────────────── */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'sc_woo_wrapper_start', 10 );
function sc_woo_wrapper_start() {
    echo '<main class="sc-woo-shell"><div class="sc-woo-shell__inner">';
}

add_action( 'woocommerce_after_main_content', 'sc_woo_wrapper_end', 10 );
function sc_woo_wrapper_end() {
    echo '</div></main>';
}

/* ═══════════════════════════════════════════════════════════════
   SEO · SERP · AEO · GEO — Schema & Meta Layer
   ═══════════════════════════════════════════════════════════════ */

/* ── Open Graph + Twitter Card (Archive / Shop page) ─────────── */
add_action( 'wp_head', 'sc_woo_archive_meta', 5 );
function sc_woo_archive_meta() {
    if ( ! function_exists( 'is_shop' ) ) return;

    if ( is_shop() || is_product_taxonomy() ) {
        $title       = is_shop() ? 'Online Courses in AI, Biotechnology & Nanotechnology | NanoSchool' : single_term_title( '', false ) . ' Courses | NanoSchool';
        $description = is_shop()
            ? 'Browse 750+ industry-aligned micro-credential courses in Artificial Intelligence, Biotechnology & Nanotechnology. Self-paced, certified, and built for working professionals. Start learning today.'
            : 'Explore ' . single_term_title( '', false ) . ' micro-credential courses from NanoSchool — certified, self-paced, and built for industry professionals.';
        $url         = is_shop() ? wc_get_page_permalink( 'shop' ) : get_term_link( get_queried_object() );
        $image       = 'https://nanoschool.in/wp-content/uploads/2024/05/nanoschool-og-courses.jpg';

        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";

        /* Open Graph */
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
        echo '<meta property="og:image:width" content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
        echo '<meta property="og:site_name" content="NanoSchool">' . "\n";
        echo '<meta property="og:locale" content="en_IN">' . "\n";

        /* Twitter Card */
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
        echo '<meta name="twitter:site" content="@nanoschool_in">' . "\n";
    }

    if ( is_product() ) {
        global $product;
        if ( ! $product ) return;

        $title       = $product->get_name() . ' — NanoSchool Online Course';
        $description = wp_trim_words( wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() ), 30, '…' );
        $url         = get_permalink();
        $image_id    = $product->get_image_id();
        $image       = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : 'https://nanoschool.in/wp-content/uploads/2024/05/nanoschool-og-courses.jpg';
        $price       = $product->get_price();

        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";

        echo '<meta property="og:type" content="product">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
        echo '<meta property="og:site_name" content="NanoSchool">' . "\n";
        echo '<meta property="product:price:amount" content="' . esc_attr( $price ) . '">' . "\n";
        echo '<meta property="product:price:currency" content="USD">' . "\n";

        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
    }
}

/* ── JSON-LD Schema — Organization (GEO Entity) ──────────────── */
add_action( 'wp_head', 'sc_schema_organization', 6 );
function sc_schema_organization() {
    if ( ! function_exists( 'is_woocommerce' ) || ! ( is_woocommerce() || is_front_page() ) ) return;

    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => array( 'EducationalOrganization', 'Organization' ),
        '@id'      => 'https://nanoschool.in/#organization',
        'name'     => 'NanoSchool Technology Center',
        'alternateName' => array( 'NSTC', 'NanoSchool' ),
        'url'      => 'https://nanoschool.in',
        'logo'     => array(
            '@type'  => 'ImageObject',
            'url'    => 'https://nanoschool.in/wp-content/uploads/nstc-logo.png',
            'width'  => 200,
            'height' => 60,
        ),
        'contactPoint' => array(
            '@type'       => 'ContactPoint',
            'email'       => 'support@nanoschool.in',
            'contactType' => 'customer support',
        ),
        'sameAs' => array(
            'https://www.linkedin.com/company/nstc-nanoschool',
        ),
        'description' => 'NanoSchool is an online education platform offering industry-aligned micro-credential courses in Artificial Intelligence, Biotechnology, and Nanotechnology. Certified by NSTC and built for working professionals.',
        'knowsAbout' => array(
            'Artificial Intelligence',
            'Biotechnology',
            'Nanotechnology',
            'Machine Learning',
            'Industry 4.0',
        ),
        'numberOfEmployees' => array(
            '@type' => 'QuantitativeValue',
            'value' => 50,
        ),
        'foundingDate' => '2020',
        'areaServed'   => 'IN',
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

/* ── JSON-LD Schema — BreadcrumbList (SERP) ──────────────────── */
add_action( 'wp_head', 'sc_schema_breadcrumb', 7 );
function sc_schema_breadcrumb() {
    if ( ! function_exists( 'is_woocommerce' ) ) return;

    $items = array(
        array(
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'Home',
            'item'     => home_url( '/' ),
        ),
    );

    if ( is_shop() ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => 'Courses',
            'item'     => wc_get_page_permalink( 'shop' ),
        );
    } elseif ( is_product_taxonomy() ) {
        $term    = get_queried_object();
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => 'Courses',
            'item'     => wc_get_page_permalink( 'shop' ),
        );
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => 3,
            'name'     => esc_html( $term->name ),
            'item'     => get_term_link( $term ),
        );
    } elseif ( is_product() ) {
        global $product;
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => 'Courses',
            'item'     => wc_get_page_permalink( 'shop' ),
        );
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => 3,
            'name'     => esc_html( $product ? $product->get_name() : get_the_title() ),
            'item'     => get_permalink(),
        );
    } else {
        return;
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

/* ── JSON-LD Schema — FAQPage (AEO) — Archive Only ───────────── */
add_action( 'wp_head', 'sc_schema_faq_archive', 8 );
function sc_schema_faq_archive() {
    if ( ! function_exists( 'is_shop' ) || ! is_shop() ) return;

    $faqs = array(
        array(
            'q' => 'What courses does NanoSchool offer?',
            'a' => 'NanoSchool offers 750+ micro-credential courses across three domains: Artificial Intelligence, Biotechnology, and Nanotechnology. All courses are self-paced, industry-aligned, and come with a shareable certificate of completion.',
        ),
        array(
            'q' => 'Are NanoSchool certificates recognized by industry?',
            'a' => 'Yes. NanoSchool certificates are issued by the NanoSchool Technology Center (NSTC) and are recognized by industry partners. Each certificate is shareable on LinkedIn and includes a verification link.',
        ),
        array(
            'q' => 'How long does it take to complete a NanoSchool course?',
            'a' => 'Most NanoSchool courses are self-paced with a recommended completion time of 4–12 weeks. You get lifetime access to all course materials, so you can learn at your own speed.',
        ),
        array(
            'q' => 'What is the cost of NanoSchool courses?',
            'a' => 'NanoSchool courses are priced at ₹4,900 (≈ USD $59) per course, with the standard value at USD $112. Bundle discounts and institutional pricing are available on request.',
        ),
        array(
            'q' => 'Can I enroll in NanoSchool courses from outside India?',
            'a' => 'Yes. NanoSchool accepts international enrollments. All courses are delivered online in English and are accessible globally. Payment is accepted in USD and INR.',
        ),
        array(
            'q' => 'What domains are covered in NanoSchool AI courses?',
            'a' => 'NanoSchool AI courses cover Machine Learning, Deep Learning, Natural Language Processing, Computer Vision, AI Ethics, Robotics, Generative AI, AI in Healthcare, AI for Manufacturing, and more.',
        ),
    );

    $faq_items = array_map( function( $faq ) {
        return array(
            '@type'          => 'Question',
            'name'           => $faq['q'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => $faq['a'],
            ),
        );
    }, $faqs );

    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $faq_items,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

/* ── JSON-LD Schema — ItemList (SERP Rich List) — Archive ─────── */
add_action( 'wp_head', 'sc_schema_item_list', 9 );
function sc_schema_item_list() {
    if ( ! function_exists( 'is_shop' ) || ! ( is_shop() || is_product_taxonomy() ) ) return;

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
        'orderby'        => 'popularity',
    );

    if ( is_product_taxonomy() ) {
        $term = get_queried_object();
        $args['tax_query'] = array(
            array(
                'taxonomy' => $term->taxonomy,
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ),
        );
    }

    $courses = get_posts( $args );
    if ( empty( $courses ) ) return;

    $items = array();
    foreach ( $courses as $i => $course ) {
        $product = wc_get_product( $course->ID );
        if ( ! $product ) continue;

        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'item'     => array(
                '@type'       => 'Course',
                '@id'         => get_permalink( $course->ID ),
                'name'        => $product->get_name(),
                'description' => wp_trim_words( wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() ), 25, '...' ),
                'url'         => get_permalink( $course->ID ),
                'provider'    => array(
                    '@type' => 'EducationalOrganization',
                    '@id'   => 'https://nanoschool.in/#organization',
                    'name'  => 'NanoSchool Technology Center',
                ),
                'offers' => array(
                    '@type'         => 'Offer',
                    'price'         => $product->get_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'availability'  => 'https://schema.org/InStock',
                    'url'           => get_permalink( $course->ID ),
                ),
                'hasCourseInstance' => array(
                    '@type'              => 'CourseInstance',
                    'courseMode'         => 'Online',
                    'courseWorkload'     => 'PT4H',
                    'instructor'         => array(
                        '@type' => 'Person',
                        'name'  => 'NanoSchool Faculty',
                    ),
                ),
            ),
        );
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => is_shop() ? 'NanoSchool Online Courses' : single_term_title( '', false ) . ' Courses',
        'description'     => 'Industry-aligned micro-credential courses in AI, Biotechnology & Nanotechnology.',
        'itemListElement' => $items,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

/* ── JSON-LD Schema — Course + Product (Single) ──────────────── */
add_action( 'wp_head', 'sc_schema_single_course', 9 );
function sc_schema_single_course() {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) return;

    global $product;
    if ( ! $product ) return;

    $name        = $product->get_name();
    $description = wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() );
    $price       = $product->get_price();
    $reg_price   = $product->get_regular_price();
    $image_id    = $product->get_image_id();
    $image_url   = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
    $url         = get_permalink();
    $rating      = $product->get_average_rating();
    $rating_count = $product->get_rating_count();

    /* Course categories for educationalLevel */
    $cats = wc_get_product_category_list( $product->get_id(), ', ' );
    $cats = wp_strip_all_tags( $cats );

    $course_schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Course',
        '@id'         => $url . '#course',
        'name'        => $name,
        'description' => $description,
        'url'         => $url,
        'image'       => $image_url,
        'provider'    => array(
            '@type' => 'EducationalOrganization',
            '@id'   => 'https://nanoschool.in/#organization',
            'name'  => 'NanoSchool Technology Center',
            'url'   => 'https://nanoschool.in',
        ),
        'educationalLevel'         => 'Beginner to Advanced',
        'teaches'                  => $cats,
        'inLanguage'               => 'en',
        'isAccessibleForFree'      => false,
        'availableLanguage'        => 'English',
        'coursePrerequisites'      => 'Basic internet access and curiosity',
        'educationalCredentialAwarded' => 'Certificate of Completion',
        'offers' => array(
            '@type'           => 'Offer',
            'url'             => $url,
            'price'           => $price,
            'priceCurrency'   => get_woocommerce_currency(),
            'priceValidUntil' => date( 'Y-12-31' ),
            'availability'    => 'https://schema.org/InStock',
            'seller'          => array(
                '@type' => 'Organization',
                'name'  => 'NanoSchool',
            ),
        ),
        'hasCourseInstance' => array(
            '@type'          => 'CourseInstance',
            'courseMode'     => 'Online',
            'courseWorkload' => 'PT8H',
            'instructor'     => array(
                '@type' => 'Person',
                'name'  => 'NanoSchool Faculty',
            ),
        ),
    );

    if ( $rating > 0 && $rating_count > 0 ) {
        $course_schema['aggregateRating'] = array(
            '@type'       => 'AggregateRating',
            'ratingValue' => number_format( (float) $rating, 1 ),
            'ratingCount' => $rating_count,
            'bestRating'  => 5,
            'worstRating' => 1,
        );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $course_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

/* ── Inject FAQ Schema onto single product from WC tabs (AEO) ── */
add_action( 'wp_head', 'sc_schema_product_faq', 10 );
function sc_schema_product_faq() {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) return;

    global $product;
    if ( ! $product ) return;

    /* Pull FAQ from ACF "course_faq" repeater if available */
    $faq_items = array();
    if ( function_exists( 'get_field' ) ) {
        $acf_faqs = get_field( 'course_faq', $product->get_id() );
        if ( is_array( $acf_faqs ) && ! empty( $acf_faqs ) ) {
            foreach ( $acf_faqs as $row ) {
                if ( ! empty( $row['question'] ) && ! empty( $row['answer'] ) ) {
                    $faq_items[] = array(
                        '@type'          => 'Question',
                        'name'           => wp_strip_all_tags( $row['question'] ),
                        'acceptedAnswer' => array(
                            '@type' => 'Answer',
                            'text'  => wp_strip_all_tags( $row['answer'] ),
                        ),
                    );
                }
            }
        }
    }

    /* Fallback: generic course FAQ for AEO */
    if ( empty( $faq_items ) ) {
        $name = $product->get_name();
        $faq_items = array(
            array(
                '@type' => 'Question',
                'name'  => 'What will I learn in this course?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'In this course — ' . esc_html( $name ) . ' — you will gain industry-relevant knowledge and practical skills in the subject area, delivered through self-paced video modules, reading materials, and assessments.',
                ),
            ),
            array(
                '@type' => 'Question',
                'name'  => 'Do I get a certificate after completing this course?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Yes. Upon completing ' . esc_html( $name ) . ', you will receive a NanoSchool verified certificate issued by the NanoSchool Technology Center (NSTC), which is shareable on LinkedIn.',
                ),
            ),
            array(
                '@type' => 'Question',
                'name'  => 'How long does it take to complete this course?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'This course is self-paced. Most learners complete it within 4–8 weeks by dedicating 2–3 hours per week. You have lifetime access, so there is no deadline.',
                ),
            ),
        );
    }

    if ( ! empty( $faq_items ) ) {
        $schema = array(
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $faq_items,
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
    }
}

/* ── SEO: WooCommerce title tag enhancement ───────────────────── */
add_filter( 'woocommerce_page_title', 'sc_woo_archive_title' );
function sc_woo_archive_title( $title ) {
    if ( is_shop() ) {
        return 'Online Courses — AI, Biotechnology & Nanotechnology';
    }
    return $title;
}

/* NOTE: No template_include override — let Thrive Theme Builder
   handle the page chrome (header/footer). WooCommerce's built-in
   template hierarchy already finds our child theme overrides in
   the woocommerce/ directory. */


/* FORMIDABLE VIEWS Integration */
/* Mentor View: 92529, Feedback View: 87412 */

function nanoschool_render_mentors_section( $view_id = 92529 ) {
    ob_start();
    echo '<div class="ns-section mentors-section" id="course-mentors">';
    echo '<div class="ns-section-header"><h2 class="ns-section-title">Learn from Expert Mentors</h2>';
    echo '<p class="ns-section-subtitle">Connect with industry leaders and academic experts.</p></div>';
    echo '<div class="frm-mentors-container">' . do_shortcode('[display-frm-data id="' . intval($view_id) . '"]') . '</div>';
    echo '</div>';
    return ob_get_clean();
}

function nanoschool_render_feedback_section( $view_id = 87412 ) {
    ob_start();
    echo '<div class="ns-section testimonials-section" id="course-testimonials">';
    echo '<div class="ns-section-header"><h2 class="ns-section-title">What Our Learners Say</h2>';
    echo '<p class="ns-section-subtitle">Hear from researchers and professionals.</p></div>';
    echo '<div class="frm-feedback-container">' . do_shortcode('[display-frm-data id="' . intval($view_id) . '"]') . '</div>';
    echo '</div>';
    return ob_get_clean();
}

add_shortcode( 'ns_mentors', function( $atts ) {
    $atts = shortcode_atts( array( 'view_id' => 92529 ), $atts );
    return nanoschool_render_mentors_section( $atts['view_id'] );
});

add_shortcode( 'ns_feedback', function( $atts ) {
    $atts = shortcode_atts( array( 'view_id' => 87412 ), $atts );
    return nanoschool_render_feedback_section( $atts['view_id'] );
});

add_action( 'wp_enqueue_scripts', 'nanoschool_optimize_wc_scripts', 99 );
function nanoschool_optimize_wc_scripts() {
    if ( function_exists( 'is_woocommerce' ) ) {
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_script( 'wc-cart-fragments' );
        }
    }
}


/* ── Hook: Inject Mentors & Feedback on Single Product Pages ── */
add_action( 'woocommerce_after_single_product', 'nanoschool_inject_mentors_feedback', 30 );
function nanoschool_inject_mentors_feedback() {
    if ( function_exists( 'nanoschool_render_mentors_section' ) ) {
        echo nanoschool_render_mentors_section( 92529 );
    }
    if ( function_exists( 'nanoschool_render_feedback_section' ) ) {
        echo nanoschool_render_feedback_section( 87412 );
    }
}


/* ── Wave 3/4: "Add to Cart" → "Enroll Now" ── */
add_filter( 'woocommerce_product_single_add_to_cart_text', 'nanoschool_enroll_text' );
add_filter( 'woocommerce_product_add_to_cart_text', 'nanoschool_enroll_text' );
function nanoschool_enroll_text( $text ) {
    return 'Enroll Now';
}

/* ── Wave 1: Sidebar toggle JS for mobile ── */
add_action( 'wp_footer', 'nanoschool_sidebar_toggle_js' );
function nanoschool_sidebar_toggle_js() {
    if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) return;
    ?>
    <script>
    (function(){
        var sidebar = document.querySelector('.widget-area, aside.widget-area');
        if (!sidebar) return;
        var btn = document.createElement('button');
        btn.className = 'ns-filter-toggle';
        btn.textContent = 'Filter by Domain';
        btn.setAttribute('aria-expanded', 'false');
        btn.addEventListener('click', function(){
            sidebar.classList.toggle('ns-sidebar-open');
            var open = sidebar.classList.contains('ns-sidebar-open');
            btn.classList.toggle('ns-open', open);
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        sidebar.parentNode.insertBefore(btn, sidebar);
    })();
    </script>
    <?php
}

/* ── Wave 3 B27: Hide mentors section if View returns empty ── */
add_action( 'wp_footer', 'nanoschool_hide_empty_sections_js' );
function nanoschool_hide_empty_sections_js() {
    if ( ! is_product() ) return;
    ?>
    <script>
    (function(){
        var mentors = document.getElementById('course-mentors');
        if (mentors) {
            var container = mentors.querySelector('.frm-mentors-container');
            if (container) {
                var text = container.textContent.trim();
                if (text.length < 10) {
                    mentors.style.display = 'none';
                }
            }
        }
    })();
    </script>
    <?php
}
