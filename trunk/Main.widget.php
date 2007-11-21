<?php

/*
Template Name: Abstraction of main blog content

$Id: Panel.php 24 2007-11-17 01:51:51Z aviram $
*/

$Main_cssClassName="widgetMain";
$Main_wpOptions="widget_main";


function Main_register($id,$name) {
	global $Main_cssClassName, $Main_wpOptions;

	$opt['classname']=$Main_cssClassName;
	$opt['params']=$id;
	wp_register_sidebar_widget($id,$name,'Main_render',$opt);

	
	// Main WP pages are now Panels (a.k.a. sidebars).
	// The "true" means they are horizontal Panels.
	// See also http://codex.wordpress.org/Templates_Hierarchy
	Panel_register("panel-home","Home main",true);
	PanelWidget_register("panel-home","Home main","panel-home","Home main");

	Panel_register("panel-single", "Single main",true);
	PanelWidget_register("panel-single","Single main","panel-single","Single main");

	Panel_register("panel-archive", "Archive main",true);
	PanelWidget_register("panel-archive","Archive main","panel-archive","Archive main");
}



function Main_render($args,$id) {
	global $Main_cssClassName, $Main_wpOptions;

	extract($args);

	echo $before_widget . "\n";

	if (is_single()) {
		Panel_render('panel-single');
	} else if (is_home()) {
		Panel_render('panel-home');
	} else if (is_archive()) {
		Panel_render('panel-archive');
	} else echo("<b>no match</b>\n");

	echo $after_widget . "\n";
}

?>