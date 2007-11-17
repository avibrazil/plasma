<?php
// Template Name: Main default template

// $Id$


get_header();

//print_r($wp_registered_widgets);


Panel_render("main-multipost");

Panel_render('footer-1');
Panel_render('footer-2');

get_footer();


?>