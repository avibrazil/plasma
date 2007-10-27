<?php 

/*
Template Name: Sidebar

$Id$

*/

// Initialize Sidebars.
register_sidebar(array(
	'name' => 'header-1'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'header-2'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'footer-1'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'footer-2'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'main-singlepost'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'main-multipost'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'real-1'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'real-2'
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));



?>
