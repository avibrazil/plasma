<?php

/*
Template Name: Abstraction of main blog content

$Id$
*/

$Main_cssClassName="widgetMain";
$Main_wpOptions="widget_main";


$Main=array();
$Main['baseID']           = "main";
$Main['baseName']         = __("Virtual Main Content",'theme');
$Main['wpOptions']        = "widget_main";
$Main['cssClassName']     = "widgetMain";
$Main['renderCallback']   = "Main_render";
$Main['methodInit']       = "Main_init";
$Main['methodSetup']      = "Main_setup";
$Main['methodRegister']   = "Main_register";
$Main['methodAdminSetup'] = "Main_adminSetup";
//$Main['controlCallback']  = "Main_control";
//$Main['controlSize']      = array('width' => 380, 'height' => 280);

//add_action('init', $Main['methodInit'], 1);



function Main_register($id,$name) {
	global $Main;

	Widget_register($Main,$id,$name);

	// Main WP pages are now Panels (a.k.a. sidebars).
	// The "true" means they are horizontal Panels.
	// See also http://codex.wordpress.org/Templates_Hierarchy
	Panel_register("panel-home","Home main panel",true);
//	PanelWidget_register("widget-panel-home","Panel Home main","panel-home","Home main");

	Panel_register("panel-single", "Single main panel",true);
//	PanelWidget_register("widget-panel-single","Panel Single main","panel-single","Single main");

	Panel_register("panel-archive", "Archive main panel",true);
//	PanelWidget_register("widget-panel-archive","Panel Archive main","panel-archive","Archive main");
}



function Main_render($args,$id) {
	global $Main, $PanelWidget;
	global $wp_registered_sidebars;

/*
echo("\n<pre>\n");
echo("id=$id :::: \n");
print_r($args);
echo("</pre>\n");
*/

/*	extract($args); */

	$main=get_option($Main_wpOptions);


	if (is_single()) $realid='panel-single';
	else if (is_home()) $realid='panel-home';
	else if (is_archive()) $realid='panel-archive';
	else {
		echo("<b>no match</b>\n");
		return;
	}

	//$args['before_widget']=str_replace("id=\"$id\"","id=\"$realid\"",$args['before_widget']);
	$args['before_widget']=str_replace($Main['cssClassName'],$Main['cssClassName'] . " " . $PanelWidget['cssClassName'],$args['before_widget']);

	echo(Panel_insert_widget_style($args['before_widget'],$id) . "\n");
/*
echo("<pre>");
print_r($realid);
echo("\n");
print_r($args);
echo("\n");
echo htmlentities(print_r($wp_registered_sidebars,true));
echo("</pre>");
*/

	Panel_render($realid);

	echo($args['after_widget'] . "\n");
}

?>