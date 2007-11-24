<?php

/*
Template Name: Theme initialization

$Id$
*/



// SEVERE WARNING:
// This file cannot contain empty lines outside < ?php   ? >
// Otherwise it will cause many
// "Cannot modify header information - headers already sent by" messages in
// various points of your WP blog

// Initialize internationalization and localization (a.k.a. tropicalization)
load_plugin_textdomain('theme','wp-content/themes/soleilpro/languages');
load_plugin_textdomain('personal','wp-content/themes/soleilpro/languages');





require_once('BestPosts.widget.php');
require_once('MultiPost.widget.php');
require_once('Panel.widget.php');
require_once('Main.widget.php');
require_once('Comments.widget.php');
require_once('Misc.widgets.php');

// Use Sandbox' class builder functions
require_once('classbuilder.php');


function default_widget_control($params) {
	extract($params);

	$options = get_option($wpOptionName);
	if ( !is_array($options) )
		$options = array();

echo("<pre>");
//print_r($params);
print_r($options[$id]);
echo("</pre>");

	if (isset($callback))
		$newoptions=call_user_func_array( $callback, $params);

	if ( !is_array($newoptions) )
		$newoptions = array();

	if ($_POST["$id-float"])
		$newoptions['float']=stripslashes($_POST["$id-float"]);

	if ($_POST["$id-clear"])
		$newoptions['clear']=stripslashes($_POST["$id-clear"]);

	if ($_POST["$id-width"])
		$newoptions['width']=stripslashes($_POST["$id-width"]);

	if ( $options[$id] != $newoptions ) {
		$options[$id] = $newoptions;
		update_option($wpOptionName, $options[$id]);
	}?>

<label for="<?php echo($wpOptionName); ?>-float"><b>Put widget on</b></label><br/>
<select name="<?php echo($wpOptionName); ?>-float">
	<option value="undef" <?php if (!isset($options[$id]['float'])) echo("selected=\"selected\""); ?>>Undefined</option>
	<option value="left" <?php if ($options[$id]['float']=='left') echo("selected=\"selected\""); ?>>Left</option>
	<option value="right" <?php if ($options[$id]['float']=='right') echo("selected=\"selected\""); ?>>Right</option>
</select>
<br/>
<label for="<?php echo($wpOptionName); ?>-clear"><b>Don't let other widgets float on</b></label><br/>
<select name="<?php echo($wpOptionName); ?>-clear">
	<option value="undef">Undefined</option>
	<option value="left">Left</option>
	<option value="right">Right</option>
	<option value="both">Both</option>
</select>
<br/>
<label for="<?php echo($wpOptionName); ?>-width"><b>Widget width (please specify units as in CSS)</b></label><br/>
<input name="<?php echo($wpOptionName); ?>-width" type="text"/><?php
}



function add_widget_default_control($instance,$name,$wpOptionName,$callback=0,$dims=0) {
	$params=array('width' => 380, 'height' => 280);
	$params['wpOptionName']=$wpOptionName;
	$params['id']=$instance;
	$params['name']=$name;

	if ($callback) $params['privateCallback']=$callback;

	if ($dims) $params=array_merge($params,$dims);

	wp_register_widget_control($instance,$name,'default_widget_control',$params,$params);
}



// Create "Sidebar 1"
Panel_register("panel-sidebar-1","Sidebar 1");

// Then instantiate it as (wrap it in) 3 different widgets
PanelWidget_register("widget1-sidebar-1","Sidebar 1 [1]","panel-sidebar-1","Sidebar 1");
PanelWidget_register("widget2-sidebar-1","Sidebar 1 [2]","panel-sidebar-1","Sidebar 1");
PanelWidget_register("widget3-sidebar-1","Sidebar 1 [3]","panel-sidebar-1","Sidebar 1");

// Dynamic Panel creation through a widget.
// Same as above but automatically creates a Panel inside the widget.
PanelWidget_register("sidebar-2","Sidebar 2");


// Registering the Main widget causes the registration of a series of 
// other widgets and Panels that contain a flow of posts, a single post,
// archives, etc. This Widget/Panel is what goes between header and footer
// of a blog page.
// See also http://codex.wordpress.org/Templates_Hierarchy
Main_register("main-content","Virtual Main Content");


// Other Panels (a.k.a. sidebars)
PanelWidget_register("header-1","Header 1","header-1","Header 1",true);
PanelWidget_register("header-2","Header 2","header-2","Header 2",true);
PanelWidget_register("footer-1","Footer 1","footer-1","Footer 1",true);
PanelWidget_register("footer-2","Footer 2","footer-2","Footer 2",true);

Panel_register("master-layout","Master Layout for All Pages");

Panel_register("comments","Comments & Reactions");



// Register elements as widgets
BestPosts_register("bestposts-homepage","Best Posts [home]");
MultiPost_register("multipost","Flow of Posts");
SinglePost_register("singlepost","One Post");
ExpandableHeader_register("banner","Header Banner");
CommentForm_register("commentform","Comment Form");
Comments_register("commentslist","Comments List");
CommentBlock_register("commentblock","Comments & Reactions");
FeaturedPost_register("featuredpost","Featured Post");


/*
Comments_register("comments","Flow of Comments");
CommentForm_register("comment-form","Comment Form");
Search_register("search","Search");
RelatedPosts_register("relatedposts","Related Posts");
ShareLinks_register("sharelinks","Share Link");
HorCategories_register("horcat","Horizontal Categories");
*/


//$best=new BestOf("Best Of","bestof");

//BestPosts_appendTab("bestposts-homepage","English", "en");
//BestPosts_appendTab("bestposts-homepage","Portuguese", "pt_br");


// Inititalize Sidebars.

//new Panel("Header 1","header-1",true);
//new Panel("Header 2","header-2",true);
?>
