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
PanelWidget_register("widget-sidebar-1-1","Sidebar 1 [1]","sidebar-1","Sidebar 1");
PanelWidget_register("widget-sidebar-1-2","Sidebar 1 [2]","sidebar-1","Sidebar 1");
PanelWidget_register("widget-sidebar-1-3","Sidebar 1 [3]","sidebar-1","Sidebar 1");



// Create "Sidebar 2"
Panel_register("sidebar-2","Sidebar 2");

// Then instantiate it as (wrap it in) 3 different widgets
PanelWidget_register("widget-sidebar-2-1","Sidebar 2 [1]","sidebar-2","Sidebar 2");
PanelWidget_register("widget-sidebar-2-2","Sidebar 2 [2]","sidebar-2","Sidebar 2");
PanelWidget_register("widget-sidebar-2-3","Sidebar 2 [3]","sidebar-2","Sidebar 2");


// Other Panels (a.k.a. sidebars)
PanelWidget_register("header-1","Header 1","header-1","Header 1",true);
PanelWidget_register("header-2","Header 2","header-2","Header 2",true);
PanelWidget_register("footer-1","Footer 1","footer-1","Footer 1",true);
PanelWidget_register("footer-2","Footer 2","footer-2","Footer 2",true);



// Registering the Main widget below causes the registration of a series of
// other widgets and Panels that contain a flow of posts, a single post,
// archives, etc. This Widget/Panel is what goes between header and footer
// of a blog page. It has the logic to decide how to render the main
// content of the page.
// See also http://codex.wordpress.org/Templates_Hierarchy
Main_register("main-content","Virtual Main Content");

// The Master Layout is a panel that contains everything but headers
// and footers.
Panel_register("master-layout","Master Layout for All Pages");


// Define a panel to hold all comment-related elements as form,
// flow of comments, lists of trackbacks, reactions, etc.
Panel_register("comments","Comments & Reactions");


// Now register all blog elements as widgets.
BestPosts_register("bestposts-homepage","Best Posts [home]");
MultiPost_register("multipost","Flow of Posts");
SinglePost_register("singlepost","One Post");
ExpandableHeader_register("banner","Header Banner");
CommentForm_register("commentinput","Comment Form");
Comments_register("commentslist","Comments List");
CommentBlock_register("commentblock","Comments & Reactions");
FeaturedPost_register("featuredpost","Featured Post");

?>
