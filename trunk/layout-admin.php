<?php
/*
 * Layout control: Export database options that control how widgets are positioned in Plasma.
 * Will generate a sort of PHP dump of the Widget admin page.
 *
 *
 * $Id: BestPosts.widget.php 66 2008-02-24 13:25:03Z aviram $
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



?>