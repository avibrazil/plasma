<?php
/*

$Id$
*/


$SinglePost_cssClassName = "widgetSinglePost";
$SinglePost_wpOptions = "widget_singlepost";



function SinglePost_render($args,$instance) {
	global $SinglePost_cssClassName, $SinglePost_wpOptions;
	extract($args);

	$singles=get_option($SinglePost_wpOptions);

	if (! $wrapped) {
		echo(Panel_insert_widget_style($before_widget,$singles[$instance]) . "\n");
		the_post();
	}

	echo("<div class=\"" . sandbox_post_class(0) . "\" id=\"post-" . get_the_ID() . "\">\n");
	SinglePost_renderPost($args,$instance);
	echo("</div>");

	if (! $wrapped) echo($after_widget . "\n");
}




function SinglePost_register($instance, $name) {
	global $SinglePost_cssClassName, $SinglePost_wpOptions;

	$opt['classname']=$SinglePost_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'SinglePost_render',$opt);

	Panel_add_style_control($instance,$name,$SinglePost_wpOptions);
}





function SinglePost_renderPost($args,$instance) {
	global $SinglePost_cssClassName, $SinglePost_wpOptions;
	extract($args);?>

	<div class="header">

		<a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title(",",0)); ?>"><?php the_title(); ?></a>

		<span class="author"><?php printf(__('By %s','theme'),__(get_the_author(),'personal')); ?></span><?php

		$posted=get_the_time('r');
		$modified=get_the_modified_time('r');

		echo("<span class=\"date long\">$posted</span>\n");
		if ($modified != $posted)
			echo("<span class=\"date-modified long\">($modified)</span>\n");

		$posted=get_the_time('j M Y');
		$modified=get_the_modified_time('j M Y');

		echo("<span class=\"date short\">$posted</span>\n");
		if ($modified != $posted)
			echo("<span class=\"date-modified short\">($modified)</span>\n");

		$posted=get_the_time();
		$modified=get_the_modified_time();

		echo("<span class=\"date default\">$posted</span>\n");
		if ($modified != $posted)
			echo("<span class=\"date-modified default\">($modified)</span>\n");?>

		<a class="comments" href="<?php comments_link(); ?>" title="<?php echo __('Comments to:','theme') . ' '; the_title(); ?>">
			<span class="number"><?php comments_number('0','1','%'); ?></span>
			<span class="word"><?php
				if (get_comments_number() == "1") _e('comment','theme');
				else _e('comments','theme');?></span></a>

		<span class="categories"><?php the_category(' &bull; '); ?></span>

		<div class="admin">
			<a class="esc-permalink" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title('','',0)); ?>">&nbsp;</a>
			<a class="esc-trackback" href="<?php trackback_url(display); ?>" title="<?php _e('Trackback URL: use this to comment on your own blog','theme'); ?>">&nbsp;</a>
			<a class="esc-comments" href="<?php comments_link(); ?>" title="<?php _e('Read and write comments to this post','theme'); ?>">&nbsp;</a>
			<a class="esc-commentsfeed" href="<?php echo(get_post_comments_feed_link()); ?>" title="<?php _e('Subscribe comments to this post','theme')?>">&nbsp;</a>
			<?php edit_post_link('<span class="esc-editpost">&nbsp;</span>'); ?>
		</div> <!-- class=admin -->

	</div> <!-- class=header --><?php

	if ($content!="no") echo("<div class=\"content\">");

	if ($content=='excerpt') {
		if (function_exists('the_excerpt_reloaded')) {
			the_excerpt_reloaded(50,'<a><p><i>','excerpt', false);
		} else the_excerpt();?>
		<span class="readmore">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf(__('%s: read full post','theme'), get_the_title()); ?>">
				<?php _e("Click here to continue reading…",'theme'); ?>
			</a>
		</span><?php
	} else if ($content!="no") {
		the_content();
		echo("<!--");
		trackback_rdf();
		echo("-->");
	}

	if ($content!="no") {
		echo("</div> <!-- class=content -->");
		echo("<div class=\"info\">" . wp_link_pages() . "</div>");
	}
}



$FeaturedPost_cssClassName = "widgetFeaturedPost";
$FeaturedPost_wpOptions = "widget_featuredpost";


function FeaturedPost_register($instance,$name) {
	global $FeaturedPost_cssClassName, $FeaturedPost_wpOptions;

	$opt['classname']=$FeaturedPost_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'FeaturedPost_render',$opt);

	Panel_add_style_control($instance,$name,$FeaturedPost_wpOptions);
}


function FeaturedPost_render($args,$instance) {
	global $FeaturedPost_cssClassName, $FeaturedPost_wpOptions;
	extract($args);

	$myoptions=get_option($FeaturedPost_wpOptions);

	$query='posts_per_page=1&orderby=date&tag=en';
	if (is_category()) $query.='&cat='.get_the_category();

	query_posts($query);

	if (! have_posts()) return;

	echo(Panel_insert_widget_style($before_widget,$myoptions[$instance]) . "\n");

	echo($before_title);
	_e("Featured Post",'theme');
	echo($after_title . "\n");

	$params=array();
	$params['wrapped']=true;
	$params['content']='excerpt';

	while (have_posts()) {
		the_post();
		SinglePost_render($params,0);
	}

	echo($after_widget . "\n");
}




$MultiPost_cssClassName = "widgetMultiPost";
$MultiPost_wpOptions = "widget_multipost";


function MultiPost_render($args,$instance) {
	global $MultiPost_cssClassName, $MultiPost_wpOptions;

	extract($args);

	query_posts(0);

	$multi=get_option($MultiPost_wpOptions);
	echo(Panel_insert_widget_style($before_widget,$multi[$instance]) . "\n");

	echo($before_title . __("Recently at the Blog",'theme') . $after_title . "\n");

	$options=array();
	$options['wrapped']=true;

	if (is_home() || is_search())
		$options['content']='excerpt';

	while (have_posts()) {
		the_post();
		SinglePost_render($options,0);
	}

	echo($after_widget . "\n");
}




function MultiPost_register($instance, $name) {
	global $MultiPost_cssClassName, $MultiPost_wpOptions;

	$opt['classname']=$MultiPost_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'MultiPost_render',$opt);

	Panel_add_style_control($instance,$name,$MultiPost_wpOptions);
}




?>