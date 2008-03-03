<?php
/*
 * Layout control: Export database options that control how widgets are
 * positioned in Plasma.
 * Will generate a sort of PHP dump of the Widget admin page.
 *
 *
 * $Id$
 */

$options=array('widget_bestposts',
	"widget_singlepost",
	"widget_featuredpost",
	"widget_multipost",
	"widget_main",
	"widget_commentform",
	"widget_comments",
	"widget_commentblock",
	"widget_compactsearch",
	"widget_closinginfo",
	"widget_wpinspector",
	"widget_related",
	"widget_navigation",
	"widget_etext",
	"widget_expandablebanner",
	"widget_panel");


function wpOptionToPHP($optionName) {
	$opt=get_option($optionName);

	return var_export($opt,true);
}





/**
 * Creates a hook in the WordPress admin system for our configuration form.
 *
 */
function plasma_adminPanel() {
		add_options_page('Plasma Layout Administration',
			'Plasma Layout Administration', 10,
			basename(__FILE__), 'plasma_optionsSubpanel');
}


/**
 * The configuration form builder and logic for WordPress admin system.
 *
 */
function plasma_optionsSubpanel() {
	echo <<<EOF
	<div class="wrap" style="text-align:center">
	<h2>Plasma Programmatic Layout</h2>
	</div>
EOF;
}



//admin hooks
add_action('admin_menu', 'plasma_adminPanel');



?>