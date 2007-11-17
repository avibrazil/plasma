<?php
/*
Template Name: Post module, to render posts everywhere


$Id$
*/


require_once('SinglePost.widget.php');


$MultiPost_cssClassName = "widgetMultiPost";
$MultiPost_wpOptions = "widget_multipost";


function MultiPost_render($args,$instance) {
	global $MultiPost_cssClassName, $MultiPost_wpOptions;

	extract($args);

	echo $before_widget . "\n";

	/* Lets create a fake sidebar-like environment for the Widget SinglePost */
	$options=array();
	$options['after_widget']="</div> <!-- class=post -->\n";

	while (have_posts()) {
		the_post();
		$options['before_widget']="<div class=\"post\" id=\"post-" . get_the_ID() . "\">\n";
		SinglePost_render($options);
	}

	echo $after_widget . "\n";
}




function MultiPost_register($instance, $name) {
	global $MultiPost_cssClassName, $MultiPost_wpOptions;

	$opt['classname']=$SinglePost_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'SinglePost_render',$opt);
}



?>
