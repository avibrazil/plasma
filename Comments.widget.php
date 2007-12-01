<?php
/*

$Id$
*/


$CommentForm_cssClassName = "widgetCommentForm";
$CommentForm_wpOptions = "widget_commentform";



function CommentForm_render($args,$instance) {
	global $CommentForm_cssClassName, $CommentForm_wpOptions;
	global $post, $user_ID, $req, $user_identity;
	global $comment_author, $comment_author_email, $comment_author_url;
	global $wp_query;

	extract($args);

	$cforms=get_option($CommentForm_wpOptions);

	if ('open' != $post->comment_status) return;

	echo(Panel_insert_widget_style($before_widget,$cforms[$instance]) . "\n");
	echo($before_title . __('Leave a Reply','theme') . $after_title);

	if ( get_option('comment_registration') && !$user_ID ) {
		// Registration required
		echo("<p>");
		printf(__("You must be <a href=\"%s\">logged in</a> to post a comment.",'theme'),get_option('siteurl')."/wp-login.php?redirect_to=".get_permalink());
		echo("</p>");
	} else {?>
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		<?php if ( $user_ID ) {
			echo("<p>");
			printf(__("Logged in as <a href=\"%s\">%s</a>. <a href=\"%s\">Logout »</a>",'theme'),
				get_option('siteurl')."/wp-admin/profile.php",
				__($user_identity,'personal'),
				get_option('siteurl')."/wp-login.php?action=logout");
			echo("</p>");
		} else { ?>
			<p>
				<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
				<label for="author"><small><?php _e('Name','theme');
					if ($req) _e('(required)','theme'); ?>
				</small></label>
			</p>
			<p>
				<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
				<label for="email"><small><?php _e('E-mail (will not be published)','theme');
					if ($req) _e('(required)','theme'); ?>
				</small></label>
			</p>
			<p>
				<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
				<label for="url"><small><?php _e('Website','theme'); ?></small></label>
			</p>
		<?php } ?>
			<p><small><strong>XHTML:</strong> <?php _e('You can use these tags:','theme');  echo allowed_tags(); ?></small></p>
			<p>
				<textarea name="comment" id="comment" rows="10" tabindex="4"></textarea>
			</p>
			<p>
				<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','theme'); ?>" />
				<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
			</p>
			<?php do_action('comment_form', $post->ID); ?>
		</form><?php
	}
	echo($after_widget . "\n");
}


function CommentForm_register($instance, $name) {
	global $CommentForm_cssClassName, $CommentForm_wpOptions;

	$opt['classname']=$CommentForm_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'CommentForm_render',$opt);

	Panel_add_style_control($instance,$name,$CommentForm_wpOptions);
}






$Comments_cssClassName = "widgetComments";
$Comments_wpOptions = "widget_comments";




function Comments_render($args,$instance) {
	global $Comments_cssClassName, $Comments_wpOptions;
	global $wp_query, $comment;

	extract($args);

	$cmts=get_option($Comments_wpOptions);
	echo(Panel_insert_widget_style($before_widget,$cmts[$instance]) . "\n");

	echo($before_title);

	?><a class="adm-commentsfeed" href="<?php echo(get_post_comments_feed_link()); ?>" title="<?php _e('Subscribe comments to this post','theme')?>">&nbsp;</a> <?php

	$no=sprintf(__("No Responses to &#8220;%s&#8221;",'theme'),__(get_the_title(),'personal'));
	$one=sprintf(__("One Response to &#8220;%s&#8221;",'theme'),__(get_the_title(),'personal'));
	$many=sprintf(__("%% Responses to &#8220;%s&#8221;",'theme'),__(get_the_title(),'personal'));

	comments_number($no,$one,$many);

	echo($after_title);


	if (!empty($post->post_password)) { // if there's a password...
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {
			// ...and it doesn't match the cookie
			echo("<p class=\"nocomments\">");
			_e("This post is password protected. Enter the password to view comments.",'theme');
			echo("</p>");
			return;
		}
	}


	echo("<ol class=\"commentlist\">");

	foreach ($wp_query->comments as $comment) {
		$text="";
		$type=get_comment_type();
		if ( $type=='trackback' || $type=='pingback' ) {
			$text=__("Via $type",'theme');
		}?>

		<li class="<?php sandbox_comment_class(); ?>" id="comment-<?php comment_ID() ?>">
			<div class="metadata">
				<a rel="external" class="author" href="<?php comment_author_url(); ?>"><?php comment_author(); ?> <?php
					if (!empty($text)) echo('<span class="adm-trackback" title="' . $text . '">&nbsp;</span>');?></a>
				<span class="date"><?php comment_date() ?></span>
				<span class="admin">
					<a class="adm-permalink" href="<?php get_comment_link() ?>" rel="bookmark" title="<?php _e('Comment permalink','theme'); ?>">&nbsp;</a>
					<?php edit_comment_link('<span class="adm-editpost">&nbsp;</span>'); ?>
				</span>
			</div> <!-- class=metadata -->

			<div class="content"><?php
				if ($comment->comment_approved == '0') {
					echo("<p class=\"warning\">");
					_e("Your comment is awaiting moderation.",'theme');
					echo("</p>");
				} else comment_text();?>
			</div>
		</li><?php
	}

	echo("</ol>");
	echo($after_widget . "\n");
}



function Comments_register($instance, $name) {
	global $Comments_cssClassName, $Comments_wpOptions;

	$opt['classname']=$Comments_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'Comments_render',$opt);

	Panel_add_style_control($instance,$name,$Comments_wpOptions);
}







$CommentBlock_cssClassName = "widgetCommentBlock";
$CommentBlock_wpOptions = "widget_commentblock";




function CommentBlock_render($args,$instance) {
	global $CommentBlock_cssClassName, $CommentBlock_wpOptions;

	extract($args);

	$cmts=get_option($CommentBlock_wpOptions);

	echo(Panel_insert_widget_style($before_widget,$cmts[$instance]) . "\n");
	comments_template();
	echo($after_widget . "\n");
}



function CommentBlock_register($instance, $name) {
	global $CommentBlock_cssClassName, $CommentBlock_wpOptions;

	$opt['classname']=$CommentBlock_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'CommentBlock_render',$opt);

	Panel_add_style_control($instance,$name,$CommentBlock_wpOptions);
}


?>
