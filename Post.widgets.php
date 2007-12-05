<?php
/*

$Id$
*/


$SinglePost=array();
$SinglePost['baseID']           = "singlepost";
$SinglePost['baseName']         = __("Single Post",'theme');
$SinglePost['wpOptions']        = "widget_singlepost";
$SinglePost['cssClassName']     = "widgetSinglePost";
$SinglePost['renderCallback']   = "SinglePost_render";
$SinglePost['methodInit']       = "SinglePost_init";
$SinglePost['methodSetup']      = "SinglePost_setup";
$SinglePost['methodRegister']   = "SinglePost_register";
$SinglePost['methodAdminSetup'] = "SinglePost_adminSetup";

add_action('init', $SinglePost['methodInit'], 1);


function SinglePost_render($args,$instance) {
	global $SinglePost;
	$current=$SinglePost;

	extract($args);

	$singles=get_option($current['wpOptions']);

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
	global $SinglePost;
	Widget_register($SinglePost,$instance,$name);
}



function SinglePost_setup() {
	global $SinglePost;
	Widget_setup($SinglePost);
}



function SinglePost_adminSetup() {
	global $SinglePost;
	Widget_adminSetup($SinglePost);
}



function SinglePost_init() {
	global $SinglePost;
	Widget_init($SinglePost);
}












$FeaturedPost=array();
$FeaturedPost['baseID']           = "featuredpost";
$FeaturedPost['baseName']         = __("Featured Post",'theme');
$FeaturedPost['wpOptions']        = "widget_featuredpost";
$FeaturedPost['cssClassName']     = "widgetFeaturedPost";
$FeaturedPost['renderCallback']   = "FeaturedPost_render";
$FeaturedPost['methodInit']       = "FeaturedPost_init";
$FeaturedPost['methodSetup']      = "FeaturedPost_setup";
$FeaturedPost['methodRegister']   = "FeaturedPost_register";
$FeaturedPost['methodAdminSetup'] = "FeaturedPost_adminSetup";

add_action('init', $FeaturedPost['methodInit'], 1);


function FeaturedPost_register($instance,$name) {
	global $FeaturedPost;
	Widget_register($FeaturedPost,$instance,$name);
}



function FeaturedPost_setup() {
	global $FeaturedPost;
	Widget_setup($FeaturedPost);
}



function FeaturedPost_adminSetup() {
	global $FeaturedPost;
	Widget_adminSetup($FeaturedPost);
}



function FeaturedPost_init() {
	global $FeaturedPost;
	Widget_init($FeaturedPost);
}





$MultiPost=array();
$MultiPost['baseID']           = "multipost";
$MultiPost['baseName']         = __("Flow of Posts",'theme');
$MultiPost['wpOptions']        = "widget_multipost";
$MultiPost['cssClassName']     = "widgetMultiPost";
$MultiPost['renderCallback']   = "MultiPost_render";
$MultiPost['methodInit']       = "MultiPost_init";
$MultiPost['methodSetup']      = "MultiPost_setup";
$MultiPost['methodRegister']   = "MultiPost_register";
$MultiPost['methodAdminSetup'] = "MultiPost_adminSetup";

add_action('init', $MultiPost['methodInit'], 1);


function MultiPost_register($instance, $name) {
	global $MultiPost;
	Widget_register($MultiPost,$instance,$name);
}



function MultiPost_setup() {
	global $MultiPost;
	Widget_setup($MultiPost);
}



function MultiPost_adminSetup() {
	global $MultiPost;
	Widget_adminSetup($MultiPost);
}



function MultiPost_init() {
	global $MultiPost;
	Widget_init($MultiPost);
}



function MultiPost_render($args,$instance) {
	global $MultiPost;

	extract($args);

	query_posts(0);

	$multi=get_option($MultiPost['wpOptions']);
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



function FeaturedPost_render($args,$instance) {
	global $FeaturedPost;
	$current=$FeaturedPost;

	extract($args);

	$myoptions=get_option($current['wpOptions']);

	$query='posts_per_page=1&orderby=date&tag=en';
	if (is_category()) $query.='&cat='.get_the_category();

	query_posts($query);

	if (! have_posts()) return;

	echo(Panel_insert_widget_style($before_widget,$myoptions[$instance]) . "\n");

	echo($before_title);
	echo($current['baseName']);
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



function SinglePost_renderPost($args,$instance) {
	global $SinglePost;
	$current=$SinglePost;
	extract($args);?>

	<div class="header">

		<a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title('','',false)); ?>"><?php the_title(); ?></a>

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
			<a class="adm-permalink" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title('','',0)); ?>">&nbsp;</a>
			<a class="adm-trackback" href="<?php trackback_url(display); ?>" title="<?php _e('Trackback URL: use this to comment on your own blog','theme'); ?>">&nbsp;</a>
			<a class="adm-comments" href="<?php comments_link(); ?>" title="<?php _e('Read and write comments to this post','theme'); ?>">&nbsp;</a>
			<a class="adm-commentsfeed" href="<?php echo(get_post_comments_feed_link()); ?>" title="<?php _e('Subscribe comments to this post','theme')?>">&nbsp;</a>
			<?php edit_post_link('<span class="adm-editpost">&nbsp;</span>'); ?>
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



?>