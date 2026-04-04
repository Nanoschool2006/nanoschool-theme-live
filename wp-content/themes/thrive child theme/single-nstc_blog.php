<?php
/*
 * Template Name: Custom nstc-blog Template
 * Template Post Type: page
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php the_title();  ?></title>

   <link rel="canonical" href="<?php the_field('canonical_url'); ?>">
    
    <meta name="description" content="<?php echo the_excerpt(); ?>">
    <meta name="Keyword" content="<?php echo get_tags(); ?>">
    <meta name="robots" content="index, follow"> 
    
    <meta name="author" content="NSTC">
    <meta name="publisher" content="Nano Science and Technology Consortium">

    <meta name="geo.region" content="IN-UP"> <meta name="geo.placename" content="Noida">
    <meta name="geo.position" content="28.5355; 77.3910"> <meta name="ICBM" content="28.5355, 77.3910">
    
    <meta property="og:title" content="<?php the_title(); ?>">
    <meta property="og:description" content="<?php echo the_excerpt(); ?>">
    <meta property="og:type" content="article">
<meta property="og:url" content="<?php echo get_permalink(); ?>">
    
<meta property="og:image" content="<?php echo get_the_post_thumbnail_url( null, 'medium' ); ?>">


    <meta property="og:site_name" content="Nano Science and Technology Consortium">
    <meta property="article:published_time" content="<?php echo get_the_time('Y-m-d\TH:i:sP'); ?>">


    <?php
// Ensure we are inside The Loop and have a featured image before outputting schema
if ( have_posts() && has_post_thumbnail() ) : the_post();
    
    // 1. Get necessary dynamic variables
    $post_url      = get_permalink();
    $post_title    = get_the_title();
    $post_excerpt  = get_the_excerpt();
    $published_iso = get_the_time('Y-m-d\TH:i:sP'); // YYYY-MM-DDTHH:MM:SS+00:00 format
    $modified_iso  = get_the_modified_time('Y-m-d\TH:i:sP'); // Use modified time for accuracy
    $author_name   = get_the_author_meta('display_name');
    $site_name     = get_bloginfo('name');
    $featured_image_url = get_the_post_thumbnail_url( null, 'full' ); // Use 'full' size for best quality
    
    // Get your site's logo URL (You may need to define this in theme settings or a function)
    $logo_url = home_url('/images/logo.png'); // Example: Replace with actual logo URL
    
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo esc_url( $post_url ); ?>"
  },
  "headline": "<?php echo esc_attr( $post_title ); ?>",
  "image": [
    "<?php echo esc_url( $featured_image_url ); ?>"
  ],
  "datePublished": "<?php echo $published_iso; ?>",
  "dateModified": "<?php echo $modified_iso; ?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo esc_attr( $author_name ); ?>"
  },
  "publisher": {
    "@type": "Organization",
    "name": "<?php echo esc_attr( $site_name ); ?>",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo esc_url( $logo_url ); ?>"
    }
  },
  "description": "<?php echo esc_attr( wp_strip_all_tags( $post_excerpt ) ); ?>"
}
</script>
<?php 
// Close the Loop actions
wp_reset_postdata();
endif; 
?>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [{
        "@type": "Question",
        "name": "<?php echo the_field('q1'); ?>",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "<?php echo the_field('a1'); ?>"
        }
      }]
    }
    </script>
    
</head>
<body>


    <article>
        <?php echo the_content(); ?>
    </article>

    <footer>
        <p>&copy; 2025 Nano Science and Technology Consortium.</p>
    </footer>
</body>
</html>