<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<!-- $Id$ -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/1">
	<title><?php
		if (is_404()) {
			_e('Page Not Found','theme');
		} elseif (is_search()) {
			printf(__('Search results for &raquo; &#8220;%s&#8221;','theme'),$s);
		} elseif (wp_title('', false)) {
			_e(wp_title('',false),'personal');
		} else _e(get_bloginfo('description'),'personal');

		echo ' :: ';
		_e(get_bloginfo('name'),'personal');
	?></title>

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats please -->
	<meta name="author" content="Soleil theme by http://Avi.Alkalay.net" />

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" media="all" type="text/css"/>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); echo("/style-print.css"); ?>" media="print" type="text/css"/>

	<link rel="alternate" type="application/rss+xml" title="Whole blog feed: RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="Whole blog feed: RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Whole blog feed: Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<?php if (is_category()) { ?>
	<link rel="alternate" type="application/rss+xml" title="Channel feed: RSS 2.0" href="<?php echo get_category_rss_link(0, intval(get_query_var('cat')), ''); ?>" />
	<?php } ?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<!--?php wp_get_archives('type=monthly&format=link'); ?-->
	<link rel="shortcut icon" href="<?php bloginfo('url'); ?>/favicon.ico" />

	<?php wp_head(); ?>
</head>

<body class="<?php sandbox_body_class() ?>">
