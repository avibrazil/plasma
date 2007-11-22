<?php
/*

$Id$
*/


$SinglePost_cssClassName = "widgetSinglePost";
$SinglePost_wpOptions = "widget_singlepost";



function SinglePost_render($args,$instance) {
	global $SinglePost_cssClassName, $SinglePost_wpOptions;
	extract($args);

	if ($wrapped) echo($before_widget . "\n");
	else the_post();

	echo("<div class=\"" . sandbox_post_class(0) . "\" id=\"post-" . get_the_ID() . "\">\n");
	SinglePost_renderPost($args,$instance);
	echo("</div>");

	if ($wrapped) echo($after_widget . "\n");
}




function SinglePost_register($instance, $name) {
	global $SinglePost_cssClassName, $SinglePost_wpOptions;

	$opt['classname']=$SinglePost_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'SinglePost_render',$opt);
}





function SinglePost_renderPost($args,$instance) {
	global $SinglePost_cssClassName, $SinglePost_wpOptions;?>

	<div class="header">

		<a class="title" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title(",",0)); ?>"><?php the_title(); ?></a>

		<a class="comments" href="<?php comments_link(); ?>" title="<?php echo __('Comments to:','theme') . ' '; the_title(); ?>">
			<span class="number"><?php comments_number('0','1','%'); ?></span>
			<span class="word"><?php
				if ($post->comment_count == 1) _e('comment','theme');
				else _e('comments','theme');?>
			</span>
		</a>

		<span class="categories"><?php the_category(' || '); ?></span>

		<span class="date"><?php the_time('r'); ?></span>
		<span class="date-modified">(<?php the_modified_time('r'); ?>)</span>

		<span class="author"><?php printf(__('By %s','theme'),__(get_the_author(),'personal')); ?></span>

		<div class="admin">
			<a class="esc-permalink" href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title('','',0)); ?>">&nbsp;</a>
			<a class="esc-trackback" href="<?php trackback_url(display); ?>" title="<?php _e('Trackback URL: use this to comment on your own blog','theme'); ?>"><span></span></a>
			<a class="esc-comments" href="<?php comments_link(); ?>" title="<?php _e('Read and write comments to this post','theme'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/comment.png"/></a>
			<?php comments_rss_link('<img alt="feed" title="' .
				__('Subscribe comments to this post','theme') .
				'" src="' . get_bloginfo('template_directory') . '/img/feed.png"/>'); ?>
			<?php edit_post_link('<img alt="edit" src="' . get_bloginfo('template_directory') . '/img/edit.png"/>'); ?>
		</div> <!-- class=admin -->

	</div> <!-- class=header -->

	<div class="content"><?php
		if (is_search()) {
			the_excerpt();?>
			<span class="readmore">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf(__('%s: read full post','theme'), get_the_title()); ?>">
					<?php _e("Read more . . .",'theme'); ?>
				</a>
			</span><?php
		} else the_content();?>
	
<!--
		<?php trackback_rdf(); ?>
-->

	</div> <!-- class=content -->

	<div class="info"><?php wp_link_pages(); ?></div><?php
}


?>