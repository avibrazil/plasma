<?php
/*
Template Name: Post module, to render posts everywhere


$Id$
*/


function WidgetSinglePost_render($args) {
	if (!empty($args)) {
		// Into a Sidebar/Widget context
		extract($args);
		echo $before_widget;
	} else echo("<div class=\"" . $this->class . "\" id=\"post-" . the_ID() . "\">\n");
	WidgetSinglePosT::renderPost();
	if ($after_widget) echo $after_widget . "\n";
	else echo("</div> <!-- class=post -->\n");
}


class WidgetSinglePost extends Widget {
	static function renderPost() {?>
		<div class="post-header">
			<div class="post-categories"><?php the_category(' || '); ?></div>

			<div class="post-baloon">
				<span class="number"><a href="<?php comments_link(); ?>" title="<?php echo __('Comments to:','theme') . ' '; the_title(); ?>"><?php comments_number('0','1','%'); ?></a></span><br/><a href="<?php comments_link(); ?>" title="<?php printf(__('Comments to: %s','theme'), the_title('','',0)); ?>"><?php if ($post->comment_count == 1) { _e('comment','theme'); } else { _e('comments','theme');} ?></a>
			</div>

			<div class="post-title">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title(",",0)); ?>"><?php the_title(); ?></a>
			</div>

			<div class="post-date">
				<img alt="clock" src="<?php bloginfo('template_directory'); ?>/img/clock.png" style="vertical-align: middle"/> <?php the_time('r'); ?> (<?php the_modified_time('r'); ?>)
			</div>
			<div class="post-author"><?php printf(__('Por %s','theme'),__(get_the_author(),'personal')); ?></div>
			<div class="post-admin">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link: %s','theme'), the_title('','',0)); ?>"><img alt="permalink" src="<?php bloginfo('template_directory'); ?>/img/permalink.png"/></a>
				<a href="<?php trackback_url(display); ?>" title="<?php _e('Trackback URL: use this to comment on your own blog','theme'); ?>"><img alt="trackback" src="<?php bloginfo('template_directory'); ?>/img/trackback.png"/></a>
				<a href="<?php comments_link(); ?>" title="<?php _e('Read and write comments to this post','theme'); ?>"><img alt="comments" src="<?php bloginfo('template_directory'); ?>/img/comment.png"/></a>
				<?php comments_rss_link('<img alt="feed" title="' .
					__('Subscribe comments to this post','theme') .
					'" src="' . get_bloginfo('template_directory') . '/img/feed.png"/>'); ?>
				<?php edit_post_link('<img alt="edit" src="' . get_bloginfo('template_directory') . '/img/edit.png"/>'); ?>
			</div>
		</div> <!-- class=post-header -->

		<div class="post-content">
			<?php
			if (is_search()) {
				the_excerpt();
				?><p class="readmore"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf(__('%s: read full post','theme'), get_the_title()); ?>"><?php _e("Read more . . .",'theme'); ?></a></p>
			<?php } else the_content();
			?>
		
			<!--
			<?php trackback_rdf(); ?>
			-->
		</div> <!-- class=post-content -->
		<div class="post-info"><?php wp_link_pages(); ?></div><?php
	}


	static public function render($args) {
		WidgetSinglePost_render($args);
	}

	function __construct($register=true) {
		__construct("Single Post","post",'WidgetSinglePost_render',"",$register);
	}
}



function WidgetMultiPost_render($args) {
	extract($args);

	echo $before_widget . "\n";

	$options=array();
	$options['after_widget']="</div> <!-- class=post -->\n";

	while (have_posts()) {
		the_post();
		$options['before_widget']="<div class=\"post\" id=\"post-" . the_ID() . "\">\n";
		WidgetSinglePost::render($options);
	}
	echo $after_widget . "\n";
}


class WidgetMultiPost extends Widget {
	function __construct($register=true) {
		parent::__construct("Flow of Posts","posts",'WidgetMultiPost_render',"", $register);
	}
	static public function render($args) {
		WidgetMultiPost_render($args);
	}
}



function PanelAsWidget_render($args) {
	extract($args);
	echo $before_widget . "\n";
	

	echo $after_widget . "\n";
}


class PanelAsWidget extends Widget {
	function __construct($isHorizontal=false,$register=true) {
		parent::__construct("Panel as Widget","panel","PanelAsWidget_render","",$register);
	}
	static public function render($args) {
		PanelAsWidget_render($args);
	}
}


?>
