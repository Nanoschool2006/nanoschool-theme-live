<?php
function thrive_child_enqueue_styles() {
    $parent_style = 'thrive-theme-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'thrive-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

/**
 * Load WooCommerce "Scholarly Edge" overrides
 * Scoped to WooCommerce pages only - zero impact on rest of the site
 */
if ( class_exists( 'WooCommerce' ) ) {
    require_once get_stylesheet_directory() . '/woocommerce/functions-woo.php';
}

add_filter('woocommerce_currency_symbol','ns_fix_usd_sym',99,2);
function ns_fix_usd_sym($s,$c){return $c==='USD'?'$':$s;}


add_action('wp_body_open','ns_hero_inject',5);
function ns_hero_inject(){
if(!is_shop()&&!is_product_category()&&!is_product_tag())return;
echo '<div class="ns-hero-section" id="ns-hero"><div class="ns-container">';
echo '<div class="ns-hero-breadcrumb">';
woocommerce_breadcrumb();
echo '</div><div class="ns-hero-content">';
echo '<div class="ns-hero-badge">Expert Research Education Since 2006</div>';
echo '<h1 class="ns-hero-title">';woocommerce_page_title();echo '</h1>';
echo '<p class="ns-hero-subtitle">Master cutting-edge domains in AI, Biotechnology &amp; Nanotechnology with research-grade courses for academics, researchers &amp; industry professionals.</p>';
echo '<div class="ns-hero-search">';get_product_search_form();echo '</div>';
echo '</div><div class="ns-hero-stats">';
echo '<div class="ns-stat-item"><span class="ns-stat-num">400+</span><span class="ns-stat-label">Specialized Courses</span></div>';
echo '<div class="ns-stat-item"><span class="ns-stat-num">70,000+</span><span class="ns-stat-label">Learners Worldwide</span></div>';
echo '<div class="ns-stat-item"><span class="ns-stat-num">95+</span><span class="ns-stat-label">Countries Represented</span></div>';
echo '<div class="ns-stat-item"><span class="ns-stat-num">NSTC</span><span class="ns-stat-label">Certified Programs</span></div>';
echo '</div></div></div>';
}


add_action('wp_head','ns_thrive_suppress',999);
function ns_thrive_suppress(){
if(!function_exists('is_woocommerce')||!is_woocommerce())return;
echo '<style id="ns-suppress">';
echo 'body.woocommerce-shop #theme-top-section,';
echo 'body.post-type-archive-product #theme-top-section,';
echo 'body.tax-product_cat #theme-top-section{display:none!important}';
echo '</style>';
}

add_filter('woocommerce_show_page_title','ns_hide_title');
function ns_hide_title($s){
if(is_shop()||is_product_category()||is_product_tag())return false;
return $s;
}


// SEO GEO Additions
add_filter('woocommerce_structured_data_product', '__return_empty_array');
add_filter('woocommerce_single_product_image_thumbnail_html', 'ns_force_alt_text', 10, 2);
function ns_force_alt_text($html, $post_thumbnail_id) {
    if (strpos($html, 'alt=""') !== false || strpos($html, "alt=''") !== false || !strpos($html, 'alt=')) {
        $product_title = get_the_title();
        if ($product_title) {
            $html = preg_replace('/alt=["\'][^"\']*["\']/', 'alt="' . esc_attr($product_title) . '"', $html);
            if (strpos($html, 'alt=') === false) $html = str_replace('<img', '<img alt="' . esc_attr($product_title) . '"', $html);
        }
    }
    return $html;
}
add_filter('post_thumbnail_html', 'ns_force_alt_text_archive', 10, 5);
function ns_force_alt_text_archive($html, $post_id, $post_thumbnail_id, $size, $attr) {
    if (is_woocommerce() && (strpos($html, 'alt=""') !== false || strpos($html, "alt=''") !== false || !strpos($html, 'alt='))) {
        $title = get_the_title($post_id);
        if ($title) {
            $html = preg_replace('/alt=["\'][^"\']*["\']/', 'alt="' . esc_attr($title) . '"', $html);
            if (strpos($html, 'alt=') === false) $html = str_replace('<img', '<img alt="' . esc_attr($title) . '"', $html);
        }
    }
    return $html;
}


// Inline AEO Styles to bypass caching
add_action('wp_head', 'ns_aeo_styles', 999);
function ns_aeo_styles() {
    if (!is_product()) return;
    echo '<style id="ns-aeo-styles">';
    echo '.woocommerce-Tabs-panel--description ul { list-style: none !important; padding-left: 0 !important; margin-bottom: 24px !important; }';
    echo '.woocommerce-Tabs-panel--description ul li { position: relative !important; padding-left: 28px !important; margin-bottom: 12px !important; line-height: 1.6 !important; color: #334155 !important; }';
    echo '.woocommerce-Tabs-panel--description ul li::before { content: "✓" !important; position: absolute !important; left: 0 !important; top: 0 !important; color: #D4A017 !important; font-weight: bold !important; font-size: 1.1em !important; }';
    echo '.woocommerce-Tabs-panel--description h2 { margin-top: 40px !important; margin-bottom: 16px !important; font-size: 1.5rem !important; color: #1A365D !important; border-bottom: 2px solid #F1F5F9 !important; padding-bottom: 8px !important; }';
    echo '.woocommerce-Tabs-panel--description h3 { margin-top: 32px !important; margin-bottom: 12px !important; font-size: 1.25rem !important; color: #334155 !important; }';
    echo '</style>';
}

// Archive H1 cleanup for SEO score (removes hidden duplicate H1)
add_action('wp_footer', 'ns_remove_duplicate_h1', 100);
function ns_remove_duplicate_h1() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) return;
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        var topSec = document.getElementById('theme-top-section');
        if (topSec) {
            var h1s = topSec.querySelectorAll('h1');
            h1s.forEach(function(h) { h.remove(); });
        }
    });
    </script>";
}



// ============================================================
// India Hub — Dynamic Course Page via the_content filter
// ============================================================

add_filter('the_content', 'ns_india_hub_page_content', 999);
function ns_india_hub_page_content($content) {
    if (!is_page('best-certification-courses-india')) return $content;

    $filter_domain = isset($_GET['domain']) ? sanitize_text_field($_GET['domain']) : '';
    $filter_level  = isset($_GET['level'])  ? sanitize_text_field($_GET['level'])  : '';
    $paged = max(1, (int) max(get_query_var('paged'), get_query_var('page'), 1));

    $meta_query = ['relation' => 'AND'];
    if ($filter_domain) $meta_query[] = ['key' => 'domain', 'value' => $filter_domain, 'compare' => 'LIKE'];
    if ($filter_level)  $meta_query[] = ['key' => 'level',  'value' => $filter_level,  'compare' => '='];

    $args = ['post_type' => 'product', 'posts_per_page' => 12, 'paged' => $paged, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC'];
    if (count($meta_query) > 1) $args['meta_query'] = $meta_query;
    $q = new WP_Query($args);

    $top_domains = ['' => 'All Domains', 'MACHINE LEARNING' => '🤖 ML & Deep Learning', 'AI IN HEALTHCARE' => '🧬 AI in Biotech & Pharma', 'Nanotechnology' => '⚛️ Nanotechnology', 'Genomics' => '🔬 Genomics', 'QUANTUM' => '🛰️ Quantum & Emerging', 'NLP' => '💬 NLP & Language AI', 'ESG' => '🌱 Sustainability & ESG', 'Life Sciences' => '💉 Life Sciences'];
    $level_options = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
    $page_url = get_permalink();
    $domain_grid = [
        ['icon' => '🤖', 'title' => 'Artificial Intelligence & ML', 'desc' => 'ML, Deep Learning, NLP, GenAI, Computer Vision', 'key' => 'MACHINE LEARNING'],
        ['icon' => '🧬', 'title' => 'Biotechnology & Pharma', 'desc' => 'Genomics, Drug Discovery, Clinical Research, Bioprocessing', 'key' => 'AI IN HEALTHCARE'],
        ['icon' => '⚛️', 'title' => 'Nanotechnology', 'desc' => 'Nanomaterials, Nanoelectronics, Nanoscience', 'key' => 'Nanotechnology'],
        ['icon' => '🛰️', 'title' => 'Quantum Computing', 'desc' => 'Quantum AI, QAOA, Quantum Hardware, Simulation', 'key' => 'QUANTUM'],
        ['icon' => '🌱', 'title' => 'Sustainability & ESG', 'desc' => 'LCA, Carbon Analytics, Green Tech, ESG Reporting', 'key' => 'ESG'],
        ['icon' => '💉', 'title' => 'Life Sciences & Health', 'desc' => 'Epidemiology, Digital Health, Bioinformatics', 'key' => 'Life Sciences'],
    ];

    ob_start();
    echo '<div id="ns-india-hub">';

    // HERO
    echo '<section class="ns-india-hero">';
    echo '<div class="ns-india-hero-bg"></div>';
    echo '<div class="ns-container ns-india-hero-inner">';
    echo '<div class="ns-india-hero-badge">🇮🇳 India\'s Leading Micro-Credential Platform · Est. 2006</div>';
    echo '<div class="ns-india-hero-h1-wrap"><h2 class="ns-india-hero-h1">Best Certification Courses in India<br><span class="ns-india-hero-accent">for AI, Biotech &amp; Nanotechnology Professionals</span></h2></div>';
    echo '<p class="ns-india-hero-sub">Join 70,000+ Indian learners mastering futuristic technologies through NSTC-certified programs. Taught by global experts, accessible from anywhere.</p>';
    echo '<div class="ns-india-hero-tags"><span>✓ INR Pricing Available</span><span>✓ Lifetime Access</span><span>✓ NSTC Certified Since 2006</span><span>✓ Industry Recognized</span></div>';
    echo '<div class="ns-india-stats">';
    echo '<div class="ns-india-stat"><span class="ns-india-stat-num">400+</span><span class="ns-india-stat-lbl">Specialized Courses</span></div>';
    echo '<div class="ns-india-stat"><span class="ns-india-stat-num">70,000+</span><span class="ns-india-stat-lbl">Enrolled Learners</span></div>';
    echo '<div class="ns-india-stat"><span class="ns-india-stat-num">95+</span><span class="ns-india-stat-lbl">Countries Reached</span></div>';
    echo '<div class="ns-india-stat"><span class="ns-india-stat-num">Since 2006</span><span class="ns-india-stat-lbl">Research Institution</span></div>';
    echo '</div></div></section>';

    // DOMAIN CARDS
    echo '<section class="ns-india-cat-section"><div class="ns-container">';
    echo '<h2 class="ns-india-section-title">Explore by Specialization</h2>';
    echo '<p class="ns-india-section-sub">India\'s fastest-growing career sectors — choose your domain and start today.</p>';
    echo '<div class="ns-india-categories-grid">';
    foreach ($domain_grid as $cat) {
        $active = ($filter_domain && strpos($cat['key'], $filter_domain) !== false) ? ' active' : '';
        $url    = esc_url(add_query_arg(['domain' => $cat['key']], $page_url)) . '#courses';
        echo '<a href="' . $url . '" class="ns-india-cat-card' . $active . '">';
        echo '<div class="ns-india-cat-icon">' . $cat['icon'] . '</div>';
        echo '<h3 class="ns-india-cat-title">' . esc_html($cat['title']) . '</h3>';
        echo '<p class="ns-india-cat-desc">' . esc_html($cat['desc']) . '</p>';
        echo '<span class="ns-india-cat-cta">Explore →</span>';
        echo '</a>';
    }
    echo '</div></div></section>';

    // COURSES GRID + FILTER
    echo '<section class="ns-india-courses-section" id="courses">';
    echo '<div class="ns-container">';
    echo '<div class="ns-india-courses-header">';
    echo '<h2 class="ns-india-section-title">' . (($filter_domain || $filter_level) ? 'Filtered Courses' : 'All Certification Courses for Indian Professionals') . '</h2>';
    echo '<div class="ns-india-result-count">' . $q->found_posts . ' courses found</div>';
    echo '</div>';

    // Filter form
    echo '<form method="GET" action="' . esc_url($page_url) . '#courses" class="ns-india-filter-form">';
    echo '<div class="ns-india-filter-group"><label>Domain / Specialization</label><select name="domain" onchange="this.form.submit()">';
    foreach ($top_domains as $val => $label) echo '<option value="' . esc_attr($val) . '"' . selected($filter_domain, $val, false) . '>' . esc_html($label) . '</option>';
    echo '</select></div>';
    echo '<div class="ns-india-filter-group"><label>Level</label><select name="level" onchange="this.form.submit()"><option value="">All Levels</option>';
    foreach ($level_options as $lv) echo '<option value="' . esc_attr($lv) . '"' . selected($filter_level, $lv, false) . '>' . esc_html($lv) . '</option>';
    echo '</select></div>';
    if ($filter_domain || $filter_level) echo '<a href="' . esc_url($page_url) . '#courses" class="ns-india-clear-btn">✕ Clear Filters</a>';
    echo '</form>';

    // Cards
    if ($q->have_posts()) {
        echo '<ul class="ns-india-course-grid">';
        while ($q->have_posts()) {
            $q->the_post();
            global $product;
            $product = wc_get_product(get_the_ID());
            if (!$product) continue;
            $acf_level    = get_post_meta($product->get_id(), 'level', true);
            $acf_duration = get_post_meta($product->get_id(), 'duration', true);
            $acf_domain   = get_post_meta($product->get_id(), 'domain', true);
            $acf_rating   = get_post_meta($product->get_id(), 'rating', true);
            $terms        = get_the_terms($product->get_id(), 'product_cat');
            $cat_name     = ($terms && !is_wp_error($terms)) ? $terms[0]->name : '';

            echo '<li class="ns-course-card ns-india-card" data-level="' . esc_attr($acf_level) . '">';
            echo '<a href="' . esc_url($product->get_permalink()) . '" class="ns-card-link">';
            echo '<div class="ns-card-image">';
            echo has_post_thumbnail() ? get_the_post_thumbnail($product->get_id(), 'woocommerce_thumbnail', ['loading' => 'lazy', 'alt' => esc_attr($product->get_name())]) : wc_placeholder_img('woocommerce_thumbnail');
            if ($cat_name) echo '<span class="ns-card-badge">' . esc_html($cat_name) . '</span>';
            if ($product->is_on_sale()) echo '<span class="ns-card-sale">SALE</span>';
            if ($acf_level) echo '<span class="ns-india-card-level level-' . esc_attr(strtolower($acf_level)) . '">' . esc_html($acf_level) . '</span>';
            echo '</div>';
            echo '<div class="ns-card-body">';
            echo '<h3 class="ns-card-title">' . esc_html($product->get_name()) . '</h3>';
            echo '<div class="ns-india-card-meta">';
            if ($acf_duration) echo '<span class="ns-meta-item"><span class="ns-meta-icon">⏱</span> ' . esc_html($acf_duration) . ' Wks</span>';
            if ($acf_level)    echo '<span class="ns-meta-item"><span class="ns-meta-icon">📊</span> ' . esc_html($acf_level) . '</span>';
            if ($acf_rating)   echo '<span class="ns-meta-item"><span class="ns-meta-icon">⭐</span> ' . esc_html($acf_rating) . '/5</span>';
            echo '</div>';
            if ($acf_domain) echo '<div class="ns-india-card-domain">' . esc_html(mb_strimwidth($acf_domain, 0, 45, '…')) . '</div>';
            echo '<p class="ns-card-excerpt">' . wp_trim_words($product->get_short_description(), 14, '…') . '</p>';
            echo '<div class="ns-card-footer"><div class="ns-card-price">' . $product->get_price_html() . '</div><span class="ns-card-cta">Enroll Now →</span></div>';
            echo '</div></a></li>';
        }
        wp_reset_postdata();
        echo '</ul>';
        $big = 999999999;
        $links = paginate_links(['base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))), 'format' => '?paged=%#%', 'current' => $paged, 'total' => $q->max_num_pages, 'prev_text' => '← Prev', 'next_text' => 'Next →']);
        if ($links) echo '<nav class="ns-india-pagination">' . $links . '</nav>';
    } else {
        echo '<div class="ns-india-no-results"><h3>No courses found.</h3><a href="' . esc_url($page_url) . '" class="ns-india-reset-btn">← View All Courses</a></div>';
    }
    echo '</div></section>';

    // WHY
    echo '<section class="ns-india-why-section"><div class="ns-container ns-india-why-inner">';
    echo '<div class="ns-india-why-content"><h2>Why Indian Professionals Choose NSTC Certifications</h2><ul class="ns-india-why-list">';
    echo '<li><strong>Globally Valid, India-Priced:</strong> Research-grade credentials. Pay in INR via UPI, Net Banking, cards.</li>';
    echo '<li><strong>Industry-Aligned:</strong> AI in Manufacturing, Pharma R&D, Biotech Startups, Government Digital Initiatives.</li>';
    echo '<li><strong>Self-Paced with Lifetime Access:</strong> Learn alongside your job — no deadlines, no pressure.</li>';
    echo '<li><strong>Established Since 2006:</strong> 70,000+ alumni from DRDO, AIIMS, IITs, and top Indian tech companies.</li>';
    echo '<li><strong>Shareable NSTC Certificate:</strong> LinkedIn-ready with global employer verification.</li>';
    echo '</ul></div>';
    echo '<div class="ns-india-why-box"><div class="ns-india-why-badge">🇮🇳</div><h3>India\'s Research Education Network Since 2006</h3><p>Recognized by professionals across India\'s top enterprises and research institutions.</p>';
    echo '<div class="ns-india-why-stats"><div><strong>₹4,900</strong><span>Approx. Price</span></div><div><strong>NSTC</strong><span>Certification</span></div><div><strong>English</strong><span>Language</span></div></div>';
    echo '</div></div></section>';

    // FAQ
    $faqs = [
        ['q' => 'Are NanoSchool certificates recognized for jobs in India?', 'a' => 'Yes. NSTC certificates are recognized by recruiters across India\'s IT, pharma, and research sectors since 2006. Shareable on LinkedIn.'],
        ['q' => 'Can I pay for NanoSchool courses in INR?', 'a' => 'Yes. Checkout accepts INR via UPI, Net Banking, and major cards.'],
        ['q' => 'What is the approximate cost of a NanoSchool course in India?', 'a' => 'Courses are approximately ₹4,900 INR (USD $59). Bundle discounts available on request.'],
        ['q' => 'What are the best AI courses for professionals in India?', 'a' => 'Popular picks: AI/ML Engineering, Generative AI, NLP & Language Models, AI Governance — all NSTC-certified.'],
        ['q' => 'Do I need an advanced degree to enroll?', 'a' => 'No. Most programs are for working professionals and students with curiosity and basic internet access.'],
        ['q' => 'Is lifetime access provided?', 'a' => 'Yes. All enrolled learners receive lifetime access including content updates — study at your own pace.'],
    ];
    echo '<section class="ns-india-faq-section" id="faq"><div class="ns-container ns-india-faq-inner">';
    echo '<h2 class="ns-india-section-title" style="text-align:center;">Frequently Asked Questions</h2>';
    echo '<p class="ns-india-section-sub" style="text-align:center;margin-bottom:40px;">Everything Indian learners ask before enrolling.</p>';
    echo '<div class="ns-india-faq-list">';
    foreach ($faqs as $faq) {
        echo '<div class="ns-india-faq-item">';
        echo '<h3 class="ns-india-faq-q">' . esc_html($faq['q']) . '</h3>';
        echo '<div class="ns-india-faq-a"><p>' . esc_html($faq['a']) . '</p></div>';
        echo '</div>';
    }
    echo '</div></div></section>';

    // CTA
    echo '<section class="ns-india-cta-section"><div class="ns-container ns-india-cta-inner">';
    echo '<h2>Start Your Certification Journey Today</h2>';
    echo '<p>Join 70,000+ Indian professionals building expertise in AI, Biotechnology, and Nanotechnology.</p>';
    echo '<div class="ns-india-cta-btns"><a href="/course/" class="ns-india-cta-primary">Browse All Courses</a><a href="#faq" class="ns-india-cta-secondary">Learn More →</a></div>';
    echo '</div></section>';

    echo '<script type="application/ld+json">{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"Are NanoSchool certificates recognized for jobs in India?","acceptedAnswer":{"@type":"Answer","text":"Yes. NSTC certificates are recognized by recruiters across India since 2006. Shareable on LinkedIn with verification."}},{"@type":"Question","name":"Can I pay for NanoSchool courses in INR?","acceptedAnswer":{"@type":"Answer","text":"Yes. Checkout accepts INR via UPI, Net Banking, and major cards."}},{"@type":"Question","name":"What are the best AI courses for professionals in India?","acceptedAnswer":{"@type":"Answer","text":"NanoSchool offers AI/ML Engineering, GenAI, NLP — all NSTC-certified for working professionals."}}]}</script>';

    echo '</div><!-- #ns-india-hub -->';
    return ob_get_clean();
}

// NS_HEADER_HARDENING_START
add_filter('show_admin_bar', 'ns_show_admin_bar_for_admin_only');
function ns_show_admin_bar_for_admin_only($show){
    if (is_admin()) return $show;
    return current_user_can('manage_options');
}

add_action('wp_head', 'ns_global_header_fix', 999);
function ns_global_header_fix(){
    if (is_admin()) return;
    echo '<style id="ns-global-header-fix">';
    echo '#thrive-header{position:sticky!important;top:0!important;z-index:1000!important;background:#0f172a!important;overflow:visible!important;overflow-y:visible!important}';
    echo 'body.admin-bar #thrive-header{top:32px!important}';
    echo '@media screen and (max-width:782px){body.admin-bar #thrive-header{top:46px!important}}';
    echo '@media screen and (max-width:991px){';
    echo '#thrive-header{position:fixed!important;left:0!important;right:0!important;top:0!important;z-index:1000!important}';
    echo 'body.admin-bar #thrive-header{top:46px!important}';
    echo 'body{padding-top:60px!important}';
    echo 'body.admin-bar{padding-top:106px!important}';
    echo '#thrive-header .tve-m-trigger{position:fixed!important;top:14px!important;right:14px!important;z-index:1000001!important;display:block!important;visibility:visible!important;opacity:1!important}';
    echo 'body.admin-bar #thrive-header .tve-m-trigger{top:58px!important}';
    echo '#thrive-header .tve-ham-wrap{display:none!important}';
    echo '#thrive-header .tve-ham-wrap.tve-m-expanded{display:block!important;position:fixed!important;left:0!important;right:0!important;top:0!important;width:100%!important;max-width:100vw!important;height:100vh!important;overflow-y:auto!important;z-index:9999!important}';
    echo '}';
    echo '#thrive-header svg{max-height:100%;overflow:hidden}';
    echo '</style>';
}

add_action('wp_footer', 'ns_header_accessibility_enhancements', 99);
function ns_header_accessibility_enhancements(){
    if (is_admin()) return;
    ?>
    <script id="ns-header-a11y">
    (function () {
      function apply() {
        document.querySelectorAll('#thrive-header .tve-m-trigger').forEach(function (el) {
          if (!el.getAttribute('aria-label')) el.setAttribute('aria-label', 'Menu');
          if (!el.getAttribute('role')) el.setAttribute('role', 'button');
          if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '0');
        });
      }
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', apply, { once: true });
      } else {
        apply();
      }
    })();
    </script>
    <?php
}
// NS_HEADER_HARDENING_END


