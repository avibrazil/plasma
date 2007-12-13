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





/**
 * General single widget registration logic.
 *
 */
function Widget_register(array $current,$id,$name) {
	$opt['classname']=$current['cssClassName'];
	$opt['params']=$id;
	wp_register_sidebar_widget($id,$name,$current['renderCallback'],$opt);

	Panel_add_style_control($id,$name,$current['wpOptions'],
		$current['controlCallback'],$current['controlSize']);
}


/**
 * Redefines the number of same widgets the user wants based on the admin GUI.
 *
 */
function Widget_setup(array $current) {
	$options = $newoptions = get_option($current['wpOptions']);
	$name=$current['baseID'] . "-number";

	if ( isset($_POST[$name . '-submit']) ) {
		$number = (int) $_POST[$name];
		if ( $number > 9 ) $number = 9;
		if ( $number < 1 ) $number = 1;

		if (sizeof($options) > $number) array_splice($newoptions,$number);
		if (sizeof($options) < $number) {
			for ($i=sizeof($options); $i<$number; $i++) {
				$previousSize=sizeof($newoptions);
				$newoptions[$current['baseID'] . "-" . ($previousSize+1)]=array();
			}
		}
	}

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option($current['wpOptions'], $options);
		Widget_init($current);
	}
}



/**
 * General logic to register widgets based on their parameters fetched from DB.
 *
 */
function Widget_init(array $current) {
	$options = get_option($current['wpOptions']);
	if (! is_array($options)) {
		$options=array();
		$options[$current['baseID'] . "-1"]=array();
		update_option($current['wpOptions'], $options);
	}

	$i=1;
	foreach ($options as $id => $params) {
//		Widget_register($current,$id,$current['baseName'] . " [$i]");
		call_user_func_array($current['methodRegister'],
			array(&$id,$current['baseName'] . " [$i]"));
		$i++;
	}

	add_action('sidebar_admin_setup', $current['methodSetup']);
	add_action('sidebar_admin_page',  $current['methodAdminSetup']);
}






/**
 * Adds in the GUI the question about the number of widgets user wants,
 * for each widget.
 *
 */
function Widget_adminSetup(array $current) {
	$options = get_option($current['wpOptions']);
	$name=$current['baseID'] . "-number";
	$size=sizeof($options);?>

	<div class="wrap">
		<form method="POST">
			<h2><?php printf(__('%s Widgets','theme'),$current['baseName']); ?></h2>
			<p style="line-height: 30px;"><?php
				printf(__('How many <strong>%s</strong> widgets would you like?','theme'),$current['baseName']);
				echo("<select id=\"$name\" name=\"$name\" value=\"$size\">\n");
				for ( $i = 1; $i < 10; ++$i )
					echo("<option value='$i' ".($size==$i ? "selected='selected'" : '').">$i</option>");?>
				</select>
				<span class="submit">
					<input type="submit" name="<?php echo($name)?>-submit" id="<?php echo($name)?>-submit" value="<?php echo attribute_escape(__('Save')); ?>" />
				</span>
			</p>
		</form>
	</div><?php
}




require_once('BestPosts.widget.php');
require_once('Post.widgets.php');
require_once('Panel.widget.php');
require_once('Main.widget.php');
require_once('Comments.widget.php');
require_once('Misc.widgets.php');

// Use Sandbox' class builder functions
require_once('classbuilder.php');



// Create "Sidebar 1"
Panel_register("sidebar-1","Sidebar 1");

// Then instantiate it as (wrap it in) 3 different widgets
//PanelWidget_register("widget-sidebar-1-1","Sidebar 1 [1]","sidebar-1","Sidebar 1");
//PanelWidget_register("widget-sidebar-1-2","Sidebar 1 [2]","sidebar-1","Sidebar 1");
//PanelWidget_register("widget-sidebar-1-3","Sidebar 1 [3]","sidebar-1","Sidebar 1");



// Create "Sidebar 2"
Panel_register("sidebar-2","Sidebar 2");

// Then instantiate it as (wrap it in) 3 different widgets
//PanelWidget_register("widget-sidebar-2-1","Sidebar 2 [1]","sidebar-2","Sidebar 2");
//PanelWidget_register("widget-sidebar-2-2","Sidebar 2 [2]","sidebar-2","Sidebar 2");
//PanelWidget_register("widget-sidebar-2-3","Sidebar 2 [3]","sidebar-2","Sidebar 2");


// Other Panels (a.k.a. sidebars)
//PanelWidget_register("header-1","Header 1","header-1","Header 1",true);
//PanelWidget_register("header-2","Header 2","header-2","Header 2",true);
//PanelWidget_register("footer-1","Footer 1","footer-1","Footer 1",true);
//PanelWidget_register("footer-2","Footer 2","footer-2","Footer 2",true);


// The Master Layout is a panel that contains everything but headers
// and footers.
Panel_register("master-layout","Master Layout for All Pages");



// Registering the Main widget below causes the registration of a series of
// other widgets and Panels that contain a flow of posts, a single post,
// archives, etc. This Widget/Panel is what goes between header and footer
// of a blog page. It has the logic to decide how to render the main
// content of the page.
// See also http://codex.wordpress.org/Templates_Hierarchy
Main_register("main-content","Virtual Main Content");


// Define a panel to hold all comment-related elements as form,
// flow of comments, lists of trackbacks, reactions, etc.
Panel_register("comments","Comments & Reactions");


// Now register all blog elements as widgets.
//BestPosts_register("bestposts-1","Best Posts [1]");
//BestPosts_register("bestposts-2","Best Posts [2]");
//BestPosts_register("bestposts-3","Best Posts [3]");
//MultiPost_register("multipost","Flow of Posts");
//SinglePost_register("singlepost","One Post");
ExpandableHeader_register("banner","Header Banner");
CommentForm_register("commentinput","Comment Form");
Comments_register("commentslist","Comments List");
CommentBlock_register("commentblock","Comments & Reactions");
//FeaturedPost_register("featuredpost-1","Featured Post [1]");
//FeaturedPost_register("featuredpost-2","Featured Post [2]");
//FeaturedPost_register("featuredpost-3","Featured Post [3]");

?>
