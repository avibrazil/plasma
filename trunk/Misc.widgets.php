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

	Use it to debug WordPress options in wp_options table.<br/>
	<form method="POST">
		<input type="text" name="option_name" value="<?php echo($_POST['option_name']); ?>"/>
		<input type="submit" value="Inspect"/>
	</form><?php

	if ( isset($_POST['option_name']) ) {?>
		<pre style="border: 1px solid black; background: white; overflow: scroll">
Inspecting <?php echo($_POST['option_name']); ?><br/>
<?php print_r(get_option($_POST['option_name']));?>
		</pre><?php
	}

	echo($after_widget);
}

?>