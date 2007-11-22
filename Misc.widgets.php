<?php

/*
Template Name: Various Widgets

$Id$
*/

$ExpandableHeader_cssClassName="widgetExpandableHeader";
$ExpandableHeader_wpOptions="widget_expandableheader";



function ExpandableHeader_render($args,$instance) {
	global $ExpandableHeader_cssClassName, $ExpandableHeader_wpOptions;
	extract($args);

	echo($before_widget . "\n");?>
	<div id="banner-inner">
		<div id="banner-header"><a href="<?php bloginfo('url'); ?>" title="<?php _e(get_bloginfo('name'),'personal'); ?>" accesskey="1"><?php _e(get_bloginfo('name'),'personal'); ?></a></div>
		<div id="banner-description"><?php _e(get_bloginfo('description'),'personal');?></div>
	</div><?php
	echo($after_widget . "\n");
}




function ExpandableHeader_register($instance, $name) {
	global $ExpandableHeader_cssClassName, $ExpandableHeader_wpOptions;

	$opt['classname']=$ExpandableHeader_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'ExpandableHeader_render',$opt);
}



?>