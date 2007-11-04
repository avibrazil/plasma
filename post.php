<?php
/*
Template Name: Post module, to render posts everywhere


$Id$
*/


function WidgetMultiPost_render($args,$num=1) {
	extract($args);

	echo $before_widget . "\n";

	/* Lets create a fake sidebar-like environment for the WidgetSinglePost */
	$options=array();
	$options['after_widget']="</div> <!-- class=post -->\n";

	while (have_posts()) {
		the_post();
		$options['before_widget']="<div class=\"post\" id=\"post-" . get_the_ID() . "\">\n";
		WidgetSinglePost::render($options);
	}
	echo $after_widget . "\n";
}





class WidgetMultiPost extends Widget {
	function __construct($name,$id,$register=true) {
		parent::__construct($name,$id,'WidgetMultiPost_render','widget_multipost',array(0),$register);
	}
	static public function render($args,$num=1) {
		WidgetMultiPost_render($args,$num=1);
	}
}



?>
