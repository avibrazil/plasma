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



// Create Sidebar 1 then wrap it as a widget
Panel_register("sidebar-1","Sidebar 1");
PanelWidget_register("sidebar-1","Sidebar 1","sidebar-1","Sidebar 1");

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
