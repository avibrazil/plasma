<?php

/*
Template Name: Abstraction of main blog content

$Id$
*/

$Main_cssClassName="widgetMain";
$Main_wpOptions="widget_main";


function Main_register($id,$name) {
	global $Main_cssClassName, $Main_wpOptions;

	$opt['classname']=$Main_cssClassName;
	$opt['params']=$id;
	wp_register_sidebar_widget($id,$name,'Main_render',$opt);

	Panel_add_style_control($id,$name,$Main_wpOptions);

	// Main WP pages are now Panels (a.k.a. sidebars).
	// The "true" means they are horizontal Panels.
	// See also http://codex.wordpress.org/Templates_Hierarchy
	Panel_register("panel-home","Home main",true);
	PanelWidget_register("widget-panel-home","Home main","panel-home","Home main");

	Panel_register("panel-single", "Single main",true);
	PanelWidget_register("widget-panel-single","Single main","panel-single","Single main");

	Panel_register("panel-archive", "Archive main",true);
	PanelWidget_register("widget-panel-archive","Archive main","panel-archive","Archive main");
}



function Main_render($args,$id) {
	global $Main_cssClassName, $Main_wpOptions, $PanelWidget_cssClassName;
/*
echo("\n<pre>\n");
echo("id=$id :::: \n");
print_r($args);
echo("</pre>\n");
*/

/*	extract($args);

	$main=get_option($Main_wpOptions);
	echo(Panel_insert_widget_style($before_widget,$main[$id]) . "\n");
*/

	if (is_single()) $realid='widget-panel-single';
	else if (is_home()) $realid='widget-panel-home';
	else if (is_archive()) $realid='widget-panel-archive';
	else {
		echo("<b>no match</b>\n");
		return;
	}

	$args['before_widget']=str_replace("id=\"$id\"","id=\"$realid\"",$args['before_widget']);
	$args['before_widget']=str_replace("$Main_cssClassName","$Main_cssClassName $PanelWidget_cssClassName",$args['before_widget']);
		
	PanelWidget_render($args,$realid);

/*
	echo $after_widget . "\n";*/
}

?>