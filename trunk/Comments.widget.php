<?php
/*

$Id$
*/


$CommentForm=array();
$CommentForm['baseID']           = "commentform";
$CommentForm['baseName']         = __("Comment Form",'theme');
$CommentForm['wpOptions']        = "widget_commentform";
$CommentForm['cssClassName']     = "widgetCommentForm";
$CommentForm['renderCallback']   = "CommentForm_render";
//$CommentForm['methodInit']       = "CommentForm_init";
//$CommentForm['methodSetup']      = "CommentForm_setup";
$CommentForm['methodRegister']   = "CommentForm_register";
//$CommentForm['methodAdminSetup'] = "CommentForm_adminSetup";
//$CommentForm['controlCallback']  = "CommentForm_control";
//$CommentForm['controlSize']      = array('width' => 380, 'height' => 280);



function CommentForm_render($args,$instance) {
	global $CommentForm;
	global $post, $user_ID, $req, $user_identity;
	global $comment_author, $comment_author_email, $comment_author_url;
	global $wp_query;

	if ('open' != $post->comment_status) return;

	extract($args);

	$cforms=get_option($CommentForm['wpOptions']);

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
	global $CommentForm;
	Widget_register($CommentForm,$instance,$name);
}






$Comments=array();
$Comments['baseID']           = "comments";
$Comments['baseName']         = __("Flow of Comments",'theme');
$Comments['wpOptions']        = "widget_comments";
$Comments['cssClassName']     = "widgetComments";
$Comments['renderCallback']   = "Comments_render";
//$Comments['methodInit']       = "Comments_init";
//$Comments['methodSetup']      = "Comments_setup";
$Comments['methodRegister']   = "Comments_register";
//$Comments['methodAdminSetup'] = "Comments_adminSetup";
//$Comments['controlCallback']  = "Comments_control";
//$Comments['controlSize']      = array('width' => 380, 'height' => 280);



function Comments_render($args,$instance) {
	global $Comments;
	global $wp_query, $comment;

	extract($args);

	$cmts=get_option($Comments['wpOptions']);
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


	echo("<div class=\"commentlist\">");

	$cindex=0;
	foreach ($wp_query->comments as $comment) {
		$cindex++;
		$text="";
		$type=get_comment_type();
		if ( $type=='trackback' || $type=='pingback' ) {
			$text=__("Via $type",'theme');
		}?>

		<div class="<?php sandbox_comment_class(); ?>" id="comment-<?php comment_ID() ?>">
			<a href="<?php get_comment_link() ?>" class="index"><?php printf(__("#%d",'theme'),$cindex);?></a>

			<div class="metadata">
				<span class="author"><?php
				comment_author_link();
				if (!empty($text)) echo(' <span class="adm-trackback" title="' . $text . '">&nbsp;</span>');
				echo("</span> <!-- class=\"author\" -->");?>
				<span class="date"><?php comment_date() ?></span>
				<span class="admin">
					<a class="adm-permalink" href="<?php get_comment_link() ?>" rel="bookmark" title="<?php _e('Comment permalink','theme'); ?>">&nbsp;</a>
					<?php edit_comment_link('<span class="adm-editpost">&nbsp;</span>'); ?>
				</span>
			</div>

			<div class="content"><?php
				if ($comment->comment_approved == '0') {
					echo("<p class=\"warning\">");
					_e("Your comment is awaiting moderation.",'theme');
					echo("</p>");
				} else comment_text();?>
			</div>
		</div><?php
	}

	echo("</div>");
	echo($after_widget . "\n");
}



function Comments_register($instance, $name) {
	global $Comments;
	Widget_register($Comments,$instance,$name);
}







$CommentBlock=array();
$CommentBlock['baseID']           = "commentblock";
$CommentBlock['baseName']         = __("Comments & Reactions",'theme');
$CommentBlock['wpOptions']        = "widget_commentblock";
$CommentBlock['cssClassName']     = "widgetCommentBlock";
$CommentBlock['renderCallback']   = "CommentBlock_render";
$CommentBlock['methodInit']       = "CommentBlock_init";
$CommentBlock['methodSetup']      = "CommentBlock_setup";
$CommentBlock['methodRegister']   = "CommentBlock_register";
$CommentBlock['methodAdminSetup'] = "CommentBlock_adminSetup";
//$CommentBlock['controlCallback']  = "CommentBlock_control";
//$CommentBlock['controlSize']      = array('width' => 380, 'height' => 280);


add_action('init', $CommentBlock['methodInit'], 1);


function CommentBlock_render($args,$instance) {
	global $CommentBlock;

	extract($args);

	$cmts=get_option($CommentBlock['wpOptions']);

	echo(Panel_insert_widget_style($before_widget,$cmts[$instance]) . "\n");
	comments_template();
	echo($after_widget . "\n");
}



function CommentBlock_register($instance, $name) {
	global $CommentBlock;
	Widget_register($CommentBlock,$instance,$name);
}




function CommentBlock_init() {
	global $CommentBlock;
	Widget_init($CommentBlock);
}




function CommentBlock_setup() {
	global $CommentBlock;
	Widget_setup($CommentBlock);
}




function CommentBlock_adminSetup() {
	global $CommentBlock;
	Widget_adminSetup($CommentBlock);
}





?>
