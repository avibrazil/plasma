<?php

/*
Template Name: Theme initialization

$Id$
*/

function Panel_register($id,$name,$horizontal=false) {
	$params['name']=$name;
	if ($id) $params['id']=$id;

			// Use the table model for a horizontal "sidebar"
//			$params['before_widget']='<td id="%1$s" class="widget %2$s">';
//			$params['after_widget']='</td>';

	if ($horizontal) $params['horizontal']=1;

	$params['before_widget']='<div id="%1$s" class="widget %2$s">';
	$params['after_widget']='</div>';

	register_sidebar($params);
}



function Panel_render($instance) {
	global $wp_registered_sidebars;

	if ($wp_registered_sidebars[$instance][horizontal]) $class='horizontal-panel';
	else $class='vertical-panel';

	echo "<div class=\"$class\" id=\"$instance\">\n";
	dynamic_sidebar($instance);
	echo("</div>\n\n");
}

?>