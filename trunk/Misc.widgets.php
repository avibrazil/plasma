<?php

/*
Template Name: Various Widgets

$Id$
*/


$ExpandableBanner=array();
$ExpandableBanner['baseID']           = "expandablebanner";
$ExpandableBanner['baseName']         = __("Expandable Banner",'theme');
$ExpandableBanner['wpOptions']        = "widget_expandablebanner";
$ExpandableBanner['cssClassName']     = "widgetExpandableBanner";
$ExpandableBanner['renderCallback']   = "ExpandableBanner_render";
$ExpandableBanner['methodInit']       = "ExpandableBanner_init";
$ExpandableBanner['methodSetup']      = "ExpandableBanner_setup";
$ExpandableBanner['methodRegister']   = "ExpandableBanner_register";
$ExpandableBanner['methodAdminSetup'] = "ExpandableBanner_adminSetup";
$ExpandableBanner['controlCallback']  = "ExpandableBanner_control";
//$ExpandableBanner['controlSize']      = array('width' => 380, 'height' => 280);


add_action('init', $ExpandableBanner['methodInit'], 1);



function ExpandableBanner_render($args,$instance) {
	global $ExpandableBanner;

	extract($args);
	$options = get_option($ExpandableBanner['wpOptions']);

	echo(Panel_insert_widget_style($before_widget,$options[$instance]) . "\n");
	<div class="inner">
		<a class="name" href="<?php bloginfo('url'); ?>" title="<?php _e(get_bloginfo('name'),'personal'); ?>" accesskey="1"><?php _e(get_bloginfo('name'),'personal'); ?></a></div>
		<span class="description"><?php _e(get_bloginfo('description'),'personal');?></span>
	</div><?php
	echo($after_widget . "\n");
}




function ExpandableBanner_register($instance, $name) {
	global $ExpandableBanner;
	Widget_register($ExpandableBanner,$instance,$name);
}


function ExpandableBanner_init() {
	global $ExpandableBanner;
	Widget_init($ExpandableBanner);
}



function ExpandableBanner_setup() {
	global $ExpandableBanner;
	Widget_setup($ExpandableBanner);
}



function ExpandableBanner_adminSetup() {
	global $ExpandableBanner;
	Widget_adminSetup($ExpandableBanner);
}





$WPInspector=array();
$WPInspector['baseID']           = "wpinspector";
$WPInspector['baseName']         = __("WP Inspector",'theme');
$WPInspector['wpOptions']        = "widget_wpinspector";
$WPInspector['cssClassName']     = "widgetWPInspector";
$WPInspector['renderCallback']   = "WPInspector_render";
$WPInspector['methodInit']       = "WPInspector_init";
$WPInspector['methodSetup']      = "WPInspector_setup";
$WPInspector['methodRegister']   = "WPInspector_register";
$WPInspector['methodAdminSetup'] = "WPInspector_adminSetup";

add_action('init', $WPInspector['methodInit'], 1);



function WPInspector_register($instance,$name) {
	global $WPInspector;
	Widget_register($WPInspector,$instance,$name);
}



function WPInspector_init() {
	global $WPInspector;
	Widget_init($WPInspector);
}


function WPInspector_setup() {
	global $WPInspector;
	Widget_setup($WPInspector);
}




function WPInspector_adminSetup() {
	global $WPInspector;
	Widget_adminSetup($WPInspector);
}



function WPInspector_render($args,$instance) {
	global $WPInspector;

	extract($args);

	$options = get_option($WPInspector['wpOptions']);

	echo(Panel_insert_widget_style($before_widget,$options[$instance]) . "\n");
	echo($before_title);
	_e("Inspect WordPress options",'theme');
	echo($after_title . "\n");?>

	Use it to debug WordPress options in wp_options table or internal variables.<br/>
	<form method="POST">
		<input type="text" name="option_name" value="<?php echo($_POST['option_name']); ?>"/>
		<input type="text" name="var_name" value="<?php echo($_POST['var_name']); ?>"/>
		<input type="submit" value="Inspect"/>
	</form><?php

	if ( isset($_POST['option_name']) ) {?>
		<pre style="border: 1px solid red; background: white; overflow: scroll">
Inspecting <?php echo($_POST['option_name']); ?><br/>
<?php print_r(get_option($_POST['option_name']));?>
		</pre><?php
	}

	if ( isset($_POST['var_name']) ) {?>
		<pre style="border: 1px solid blue; background: white; overflow: scroll">
Inspecting <?php echo($_POST['var_name']); ?><br/>
<?php eval("global \$" . $_POST['var_name'] . "; echo(htmlentities(print_r(\$" . $_POST['var_name'] . ",true)));") ;?>
		</pre><?php
	}

	echo($after_widget);
}

?>