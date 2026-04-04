<?php
/* Template Name: Custom HTML Page */
?>

<?php
    // This displays the content added via the WordPress editor
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>


<?php get_footer(); ?>