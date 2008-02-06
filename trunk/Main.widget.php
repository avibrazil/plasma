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
//$Main['methodInit']       = "Main_init";
$Main['methodSetup']      = "Main_setup";
$Main['methodRegister']   = "Main_register";
//$Main['methodAdminSetup'] = "Main_adminSetup";
//$Main['controlCallback']  = "Main_control";
//$Main['controlSize']      = array('width' => 380, 'height' => 280);

//add_action('init', $Main['methodInit'], 1);



function Main_register($id,$name) {
	global $Main;

	Widget_register($Main,$id,$name);

	// Main WP pages are now Panels (a.k.a. sidebars).
	// The "true" means they are horizontal Panels.
	// See also http://codex.wordpress.org/Templates_Hierarchy

	Panel_register("panel-home","Main panel on Home Page",true);

	Panel_register("panel-single", "Main panel on Single Page",true);

	Panel_register("panel-category", "Main panel on Category Page",true);

	Panel_register("panel-search", "Main panel on Search Page",true);

	Panel_register("panel-archive", "Main panel on Archive Page",true);
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

	$main=get_option($Main['wpOptions']);


	if (is_single()) $realid='panel-single';
	else if (is_home()) $realid='panel-home';
	else if (is_category()) $realid='panel-category';
	else if (is_search()) $realid='panel-search';
	else if (is_archive()) $realid='panel-archive';

	if (!is_single() && (get_query_var('paged') || $_GET['pagedmode']!=0)) $realid='panel-archive';

	//$args['before_widget']=str_replace("id=\"$id\"","id=\"$realid\"",$args['before_widget']);
	$args['before_widget']=str_replace($Main['cssClassName'],$Main['cssClassName'] . " " . $PanelWidget['cssClassName'],$args['before_widget']);

	echo(Panel_insert_widget_style($args['before_widget'],$main[$id]) . "\n");
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
	
	echo($args['after_widget'] . "<!-- id=$id -->\n");
}

?>
