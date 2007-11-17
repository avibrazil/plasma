<?php
/*

$Id$
*/


$SinglePost_cssClassName = "widgetSinglePost";
$SinglePost_wpOptions = "widget_singlepost";



function SinglePost_render($args,$instance) {
	global $SinglePost_cssClassName, $SinglePost_wpOptions;
	extract($args);

	echo($before_widget . "\n");
	SinglePost_renderPost($args,$num);
	echo($after_widget . "\n");
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
		<div class="categories"><?php the_category(' || '); ?></div>

		<div class="heading-block">
			<div class="baloon">
				<span class="number"><a href="<?php comments_link(); ?>" title="<?php echo __('Comments to:','theme') . ' '; the_title(); ?>"><?php comments_number('0','1','%'); ?></a></span><br/><a href="<?php comments_link(); ?>" title="<?php printf(__('Comments to: %s','theme'), the_title('','',0)); ?>"><?php if ($post->comment_count == 1) { _e('comment','theme'); } else { _e('comments','theme');} ?></a>
			</div>

			<div class="title">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title(",",0)); ?>"><?php the_title(); ?></a>
			</div>

			<div class="date">
				<img alt="clock" src="<?php bloginfo('template_directory'); ?>/img/clock.png" style="vertical-align: middle"/> <?php the_time('r'); ?> (<?php the_modified_time('r'); ?>)
			</div>
			<div class="author"><?php printf(__('By %s','theme'),__(get_the_author(),'personal')); ?></div>
			<div class="admin">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title('','',0)); ?>"><img alt="permalink" src="<?php bloginfo('template_directory'); ?>/img/permalink.png"/></a>
				<a href="<?php trackback_url(display); ?>" title="<?php _e('Trackback URL: use this to comment on your own blog','theme'); ?>"><img alt="trackback" src="<?php bloginfo('template_directory'); ?>/img/trackback.png"/></a>
				<a href="<?php comments_link(); ?>" title="<?php _e('Read and write comments to this post','theme'); ?>"><img alt="comments" src="<?php bloginfo('template_directory'); ?>/img/comment.png"/></a>
				<?php comments_rss_link('<img alt="feed" title="' .
					__('Subscribe comments to this post','theme') .
					'" src="' . get_bloginfo('template_directory') . '/img/feed.png"/>'); ?>
				<?php edit_post_link('<img alt="edit" src="' . get_bloginfo('template_directory') . '/img/edit.png"/>'); ?>
			</div> <!-- class=admin -->
		</div> <!-- class=heading-block -->
	</div> <!-- class=header -->

	<div class="content"><?php
		if (is_search()) {
			the_excerpt();?>
			<p class="readmore"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf(__('%s: read full post','theme'), get_the_title()); ?>"><?php _e("Read more . . .",'theme'); ?></a></p><?php
		} else the_content();?>
	
		<!--
		<?php trackback_rdf(); ?>
		-->
	</div> <!-- class=content -->
	<div class="info"><?php wp_link_pages(); ?></div><?php
}


?>