<?php
/*
Template Name: Full HTML Canvas
Template Post Type: post, page
*/

while ( have_posts() ) : the_post();
    // This outputs only the content you type in the editor
    the_content(); 
endwhile;
?>