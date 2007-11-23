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

	query_posts(0);

	echo($before_widget . "\n");
	echo($before_title . __("Recently at the Blog",'theme') . $after_title . "\n");

	$options=array();
	$options['wrapped']=true;

	if (is_home() || is_search())
		$options['content']='excerpt';

	while (have_posts()) {
		the_post();
		SinglePost_render($options,0);
	}

	echo($after_widget . "\n");
}




function MultiPost_register($instance, $name) {
	global $MultiPost_cssClassName, $MultiPost_wpOptions;

	$opt['classname']=$MultiPost_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'MultiPost_render',$opt);
}



?>
