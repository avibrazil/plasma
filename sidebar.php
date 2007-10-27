<?php 

/*
Template Name: Sidebar
*/

// Initialize the Widgets subsystem.
register_sidebar(array(
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
));





if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) {

	// This code won't be executed if you have the Widgets plugin.
	// But its designed to work correctly even if you don't have the plugin.
	
	widget_soleil_search(array(
 		'before_widget' => '<div id="soleil-search" class="widget widget_soleil_search">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
        ));

	widget_soleil_categories(array(
 		'before_widget' => '<div id="soleil-categories" class="widget widget_soleil_categories">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
        ));

	widget_soleil_archives(array(
 		'before_widget' => '<div id="soleil-archives" class="widget widget_soleil_archives">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
        ));

	if(is_home()) {
		?><div class="widget_about widget" id="about">
			<h2 class="widgettitle"><?php _e('About this Blog','theme'); ?></h2>
			<?php _e(get_bloginfo('description'),'personal'); ?>
		</div><?php
	}

	widget_soleil_links(array(
		'before_widget' => '<div id="soleil-links" class="widget widget_soleil_links">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
        ));

	widget_soleil_subscriptions(array(
 		'before_widget' => '<div id="soleil-subscriptions" class="widget widget_soleil_subscriptions">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
        ));

	widget_soleil_meta(array(
 		'before_widget' => '<div id="soleil-meta" class="widget widget_soleil_meta">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
        ));


	if (function_exists('wp_theme_switcher')) {
		?><div class="widget" id="theme-switcher">
			<h2 class="widgettitle"><?php _e('Themes:','theme'); ?></h2>
			<?php wp_theme_switcher(); ?>
		</div><?php
	}

} ?>
