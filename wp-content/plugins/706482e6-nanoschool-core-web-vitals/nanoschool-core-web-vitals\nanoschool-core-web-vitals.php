<?php
/**
 * Plugin Name: Nanoschool Core Web Vitals Optimizer
 * Description: Hardcodes programmatic optimizations for LCP, CLS, and INP without the bloat of caching plugins.
 * Version: 1.1.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Nanoschool_CWV_Optimizer {

    public function __construct() {
        // 1. Defer all non-essential JS (INP, LCP)
        add_filter('script_loader_tag', array($this, 'defer_javascript'), 10, 2);
        
        // 2. Remove query strings from static resources (Caching efficiency)
        add_filter('style_loader_src', array($this, 'remove_query_strings'), 10, 2);
        add_filter('script_loader_src', array($this, 'remove_query_strings'), 10, 2);
        
        // 3. Remove WordPress Bloat
        add_action('init', array($this, 'remove_wp_bloat'));
        
        // 4. Force font-display: swap on Enqueued Fonts (CLS)
        add_filter('style_loader_tag', array($this, 'add_font_display_swap'), 10, 3);

        // 5. Preconnect & Preload Critical Resources (LCP)
        add_action('wp_head', array($this, 'critical_resource_hints'), 5);
    }

    /**
     * Defer JS Execution to prevent render-blocking (INP/LCP improvement)
     */
    public function defer_javascript($tag, $handle) {
        // Do not defer jQuery or admin scripts to prevent breakage
        if (is_admin() || strpos($handle, 'jquery') !== false) {
            return $tag;
        }
        
        // Add exact defer attribute
        if (strpos($tag, 'defer') === false) {
            return str_replace(' src', ' defer="defer" src', $tag);
        }
        return $tag;
    }

    /**
     * Remove ?ver= query strings from JS/CSS
     */
    public function remove_query_strings($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Remove unneeded WordPress core bloat
     */
    public function remove_wp_bloat() {
        if (!is_admin()) {
            // Remove emojis
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            
            // Remove OEmbed
            wp_deregister_script('wp-embed');
            
            // Remove dashicons on frontend (unless explicitly logged in)
            if (!is_user_logged_in()) {
                wp_deregister_style('dashicons');
            }
        }
    }

    /**
     * Ensure Fonts use font-display: swap to fix CLS issues
     */
    public function add_font_display_swap($html, $handle, $href) {
        if (strpos($href, 'fonts.googleapis.com') !== false || strpos($href, 'fonts.bunny.net') !== false) {
            if (strpos($href, 'display=swap') === false) {
                // Append display=swap if not present
                $href = add_query_arg('display', 'swap', $href);
                // Actually rewrite the original HTML tag with the new href
                $html = preg_replace('/href=(["\']).*?(["\'])/i', 'href=$1' . esc_url($href) . '$2', $html);
            }
        }
        return $html;
    }

    /**
     * Add Preconnect and Preload hints for critical resources
     */
    public function critical_resource_hints() {
        // Preconnect to Bunny Fonts
        echo '<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>' . "\n";
        
        // Preload Critical Roboto Font identified from PageSpeed report
        if (is_front_page()) {
            echo '<link rel="preload" href="https://fonts.bunny.net/roboto/files/roboto-latin-500-normal.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
        }
    }
}

new Nanoschool_CWV_Optimizer();
