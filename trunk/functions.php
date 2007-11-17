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
require_once('Panel.php');
require_once('Panel.widget.php');


// include(TEMPLATEPATH . '/post.php');


// Initialize Widgets.
MultiPost_register("multipost","Flow of Posts");

// Explicit Panel registration followed by a bind to a widget
Panel_register("sidebar-1","Sidebar 1");
PanelWidget_register("sidebar-1","Sidebar 1","sidebar-1","Sidebar 1");

// Dynamic Panel creation through a widget
PanelWidget_register("sidebar-2","Sidebar 2");

// Other Panels (a.k.a. sidebars)
Panel_register("footer-1","Footer 1",true);
Panel_register("footer-2","Footer 2",true);

Panel_register("main-multipost","Main Multipost",true);
Panel_register("main-singlepost","Main Singlepost",true);


BestPosts_register("bestposts-homepage","Best Posts [homepage]");

//$best=new BestOf("Best Of","bestof");

//BestPosts_appendTab("bestposts-homepage","English", "en");
//BestPosts_appendTab("bestposts-homepage","Portuguese", "pt_br");


// Inititalize Sidebars.

//new Panel("Header 1","header-1",true);
//new Panel("Header 2","header-2",true);
?>
