<?php

/*
Template Name: Various Widgets

$Id$
*/



$RelatedLinks=array();
$RelatedLinks['baseID']           = "related";
$RelatedLinks['baseName']         = __("Related Links",'theme');
$RelatedLinks['wpOptions']        = "widget_related";
$RelatedLinks['cssClassName']     = "widgetRelated";
$RelatedLinks['renderCallback']   = "RelatedLinks_render";
$RelatedLinks['methodInit']       = "RelatedLinks_init";
$RelatedLinks['methodSetup']      = "RelatedLinks_setup";
$RelatedLinks['methodRegister']   = "RelatedLinks_register";
$RelatedLinks['methodAdminSetup'] = "RelatedLinks_adminSetup";

add_action('init', $RelatedLinks['methodInit'], 1);



function RelatedLinks_init() {
	global $RelatedLinks;

	// First check if the related posts plugin exists here.
	if (!function_exists('related_posts')) return;

	Widget_init($RelatedLinks);
}


function RelatedLinks_register($id,$name) {
	global $RelatedLinks;
	Widget_register($RelatedLinks,$id,$name);
}


function RelatedLinks_setup() {
	global $RelatedLinks;
	Widget_setup($RelatedLinks);
}


function RelatedLinks_adminSetup() {
	global $RelatedLinks;
	Widget_adminSetup($RelatedLinks);
}



function RelatedLinks_render($args,$id) {
	global $RelatedLinks;

	$config=get_option($RelatedLinks['wpOptions']);

	echo(Panel_insert_widget_style($args['before_widget'],$config[$id]) . "\n");

	echo($args['before_title'] . __("See also",'theme') . $args['after_title']);

	echo("<ul>\n");
	related_posts();
	echo("</ul>\n");

	echo($args['after_widget'] . "<!-- id=$id -->\n");
}










$NavigationLinks=array();
$NavigationLinks['baseID']           = "navigation";
$NavigationLinks['baseName']         = __("Navigation Links",'theme');
$NavigationLinks['wpOptions']        = "widget_navigation";
$NavigationLinks['cssClassName']     = "widgetNavigation";
$NavigationLinks['renderCallback']   = "NavigationLinks_render";
$NavigationLinks['methodInit']       = "NavigationLinks_init";
$NavigationLinks['methodSetup']      = "NavigationLinks_setup";
$NavigationLinks['methodRegister']   = "NavigationLinks_register";
$NavigationLinks['methodAdminSetup'] = "NavigationLinks_adminSetup";

add_action('init', $NavigationLinks['methodInit'], 1);



function NavigationLinks_init() {
	global $NavigationLinks;
	Widget_init($NavigationLinks);
}


function NavigationLinks_register($id,$name) {
	global $NavigationLinks;
	Widget_register($NavigationLinks,$id,$name);
}


function NavigationLinks_setup() {
	global $NavigationLinks;
	Widget_setup($NavigationLinks);
}


function NavigationLinks_adminSetup() {
	global $NavigationLinks;
	Widget_adminSetup($NavigationLinks);
}



function NavigationLinks_render($args,$id) {
	global $NavigationLinks;

	$config=get_option($NavigationLinks['wpOptions']);

	echo(Panel_insert_widget_style($args['before_widget'],$config[$id]) . "\n");

	if (is_single()) {?>
		<div class="pages"><?php
			wp_link_pages(array(
				'before' => '<p><strong>'.__("Pages:",'theme') . '</strong> ',
				'after' => '</p>',
				'next_or_number' => 'number'));?>
		</div>

		<div class="posts">
			<span class="prev">
				<?php previous_post_link('%link','&laquo; %title'); ?>
			</span>
			<span class="next">
				<?php next_post_link('%link','%title &raquo;'); ?>
			</span>
		</div><?php
	}

	if (is_archive() || is_search() || is_home()) {?>
		<div class="navigation">
			<span class="prev">
				<?php next_posts_link(__('&laquo; Older Entries','theme')); ?>
			</span>
			<span class="next">
				<?php previous_posts_link(__('Newer Entries &raquo;','theme')); ?>
			</span>
		</div><?php
	}

	echo($args['after_widget'] . "<!-- id=$id -->\n");
}








$ExtendedText=array();
$ExtendedText['baseID']           = "etext";
$ExtendedText['baseName']         = __("Extended Text",'theme');
$ExtendedText['wpOptions']        = "widget_etext";
$ExtendedText['cssClassName']     = "widgetEText";
$ExtendedText['renderCallback']   = "ExtendedText_render";
$ExtendedText['methodInit']       = "ExtendedText_init";
$ExtendedText['methodSetup']      = "ExtendedText_setup";
$ExtendedText['methodRegister']   = "ExtendedText_register";
$ExtendedText['methodAdminSetup'] = "ExtendedText_adminSetup";
$ExtendedText['controlCallback']  = "ExtendedText_control";
$ExtendedText['controlSize']      = array('width' => 380, 'height' => 300);

add_action('init', $ExtendedText['methodInit'], 1);



function ExtendedText_init() {
	global $ExtendedText;
	Widget_init($ExtendedText);
}


function ExtendedText_register($id,$name) {
	global $ExtendedText;

	$config=get_option($ExtendedText['wpOptions']);
	if (isset($config[$id]['title'])) $name=$config[$id]['title'];

	Widget_register($ExtendedText,$id,$name);
}


function ExtendedText_setup() {
	global $ExtendedText;
	Widget_setup($ExtendedText);
}


function ExtendedText_adminSetup() {
	global $ExtendedText;
	Widget_adminSetup($ExtendedText);
}



function ExtendedText_render($args,$id) {
	global $ExtendedText;

	$config=get_option($ExtendedText['wpOptions']);

	if (empty($config[$id]['title']) && empty($config[$id]['text'])) return;

	echo(Panel_insert_widget_style($args['before_widget'],$config[$id]) . "\n");

	if (!empty($config[$id]['title']))
			echo($args['before_title'] . __($config[$id]['title'],'personal') . $args['after_title']);

	_e($config[$id]['text'],'personal');

	echo($args['after_widget'] . "<!-- id=$instance -->\n");
}



function ExtendedText_control($id) {
	global $ExtendedText;
	$config=get_option($ExtendedText['wpOptions']);

	if (! is_array($config))
		$config = array();

	if (!is_array($config[$id]))
		$config[$id]=array();

	$newconfig=$config[$id];
	
	if ($_POST["$id-title"])
		$newconfig['title']=strip_tags(stripslashes($_POST["$id-title"]));

	if ($_POST["$id-text"]) {
		$newconfig['text']=stripslashes($_POST["$id-text"]);
		if ( !current_user_can('unfiltered_html') )
			$newconfig['text'] = stripslashes(wp_filter_post_kses($newconfig['text']));
	}


	if ($config[$id] != $newconfig) {
		$config[$id]=$newconfig;
		update_option($ExtendedText['wpOptions'], $config);
	}?>

<input style="width: 90%;" name="<?php echo("$id-title")?>" value="<?php echo($config[$id]['title'])?>"/><br/>
<textarea style="width: 90%; height: 280px;"
name="<?php echo("$id-text")?>"><?php echo(format_to_edit($config[$id]['text']))?></textarea><?php
}








$ExpandableBanner=array();
$ExpandableBanner['baseID']           = "expandablebanner";
$ExpandableBanner['baseName']         = __("Expandable Banner",'theme');
$ExpandableBanner['wpOptions']        = "widget_expandablebanner";
$ExpandableBanner['cssClassName']     = "widgetExpandableBanner";
$ExpandableBanner['renderCallback']   = "ExpandableBanner_render";
$ExpandableBanner['methodInit']       = "ExpandableBanner_init";
$ExpandableBanner['methodSetup']      = "ExpandableBanner_setup";
$ExpandableBanner['methodRegister']   = "ExpandableBanner_register";
$ExpandableBanner['methodAdminSetup'] = "ExpandableBanner_adminSetup";
//$ExpandableBanner['controlCallback']  = "ExpandableBanner_control";
//$ExpandableBanner['controlSize']      = array('width' => 380, 'height' => 280);


add_action('init', $ExpandableBanner['methodInit'], 1);




function ExpandableBanner_render($args,$instance) {
	global $ExpandableBanner;

	extract($args);
	$options = get_option($ExpandableBanner['wpOptions']);

	echo(Panel_insert_widget_style($before_widget,$options[$instance]) . "\n");?>

	<div class="inner">
		<a class="name" href="<?php bloginfo('url'); ?>" title="<?php _e(get_bloginfo('name'),'personal'); ?>" accesskey="1"><?php _e(get_bloginfo('name'),'personal'); ?></a>
		<span class="description"><?php _e(get_bloginfo('description'),'personal');?></span>
	</div><?php

	echo("$after_widget <!-- id=$instance -->\n");
}




function ExpandableBanner_register($instance, $name) {
	global $ExpandableBanner;
	Widget_register($ExpandableBanner,$instance,$name);
}


function ExpandableBanner_init() {
	global $ExpandableBanner;
	Widget_init($ExpandableBanner);
}



function ExpandableBanner_setup() {
	global $ExpandableBanner;
	Widget_setup($ExpandableBanner);
}



function ExpandableBanner_adminSetup() {
	global $ExpandableBanner;
	Widget_adminSetup($ExpandableBanner);
}







$Search=array();
$Search['baseID']           = "compactsearch";
$Search['baseName']         = __("Compact Search",'theme');
$Search['wpOptions']        = "widget_compactsearch";
$Search['cssClassName']     = "widgetCompactSearch";
$Search['renderCallback']   = "Search_render";
$Search['methodInit']       = "Search_init";
$Search['methodSetup']      = "Search_setup";
$Search['methodRegister']   = "Search_register";
$Search['methodAdminSetup'] = "Search_adminSetup";


add_action('init', $Search['methodInit'], 1);




function Search_render($args,$instance) {
	global $Search;

	extract($args);
	$options = get_option($Search['wpOptions']);

	echo(Panel_insert_widget_style($before_widget,$options[$instance]) . "\n");?>

	<form id="searchform" method="get" action="<?php bloginfo('home'); ?>">
		<input type="text" name="s" id="s" value="<?php _e('Type and hit enter to search','theme'); ?>" onfocus="if (this.value == '<?php _e('Type and hit enter to search','theme'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Type and hit enter to search','theme'); ?>';}" />
	</form><?php

	echo("$after_widget <!-- id=$instance -->\n");
}




function Search_register($instance, $name) {
	global $Search;
	Widget_register($Search,$instance,$name);
}


function Search_init() {
	global $Search;
	Widget_init($Search);
}



function Search_setup() {
	global $Search;
	Widget_setup($Search);
}



function Search_adminSetup() {
	global $Search;
	Widget_adminSetup($Search);
}










$ClosingInfo=array();
$ClosingInfo['baseID']           = "closinginfo";
$ClosingInfo['baseName']         = __("Closing Info",'theme');
$ClosingInfo['wpOptions']        = "widget_closinginfo";
$ClosingInfo['cssClassName']     = "widgetClosingInfo";
$ClosingInfo['renderCallback']   = "ClosingInfo_render";
$ClosingInfo['methodInit']       = "ClosingInfo_init";
$ClosingInfo['methodSetup']      = "ClosingInfo_setup";
$ClosingInfo['methodRegister']   = "ClosingInfo_register";
$ClosingInfo['methodAdminSetup'] = "ClosingInfo_adminSetup";


add_action('init', $ClosingInfo['methodInit'], 1);




function ClosingInfo_render($args,$instance) {
	global $ClosingInfo;
	global $wpdb;

	extract($args);
	$options = get_option($ClosingInfo['wpOptions']);

	echo(Panel_insert_widget_style($before_widget,$options[$instance]) . "\n");

	echo("<p>");
	printf(__("%s is powered by <a href=\"http://WordPress.org\">WordPress %s</a> and delivered to you in %g seconds using %d queries.",'theme'),
		"<a href=\"" . get_bloginfo('url') . "\" title=\"" . __(get_bloginfo('name'),'personal') . "\">" . __(get_bloginfo('name'),'personal') . "</a>",
		get_bloginfo('version'),
		timer_stop(0),
		$wpdb->num_queries);
	echo("</p>");

	echo("<p>");
	_e("Theme: <a href=\"http://avi.alkalay.net/2006/11/soleil-theme-for-wordpress.html\" title=\"Powered by Plasma, your last WordPress theme\">Plasma, your last WordPress theme</a> by <a href=\"http://avi.alkalay.net\" title=\"Visit Avi's Blog\">Avi Alkalay</a>.",'theme');
	echo("</p>");

	echo("<p>");
	_e("Icons by the <a href=\"http://avi.alkalay.net/2007/05/blog-icons.html\">Blog Icons Project</a>.",'theme');
	echo("</p>");

	echo("$after_widget <!-- id=$instance -->\n");
}




function ClosingInfo_register($instance, $name) {
	global $ClosingInfo;
	Widget_register($ClosingInfo,$instance,$name);
}


function ClosingInfo_init() {
	global $ClosingInfo;
	Widget_init($ClosingInfo);
}



function ClosingInfo_setup() {
	global $ClosingInfo;
	Widget_setup($ClosingInfo);
}



function ClosingInfo_adminSetup() {
	global $ClosingInfo;
	Widget_adminSetup($ClosingInfo);
}







$WPInspector=array();
$WPInspector['baseID']           = "wpinspector";
$WPInspector['baseName']         = __("WP Inspector",'theme');
$WPInspector['wpOptions']        = "widget_wpinspector";
$WPInspector['cssClassName']     = "widgetWPInspector";
$WPInspector['renderCallback']   = "WPInspector_render";
$WPInspector['methodInit']       = "WPInspector_init";
$WPInspector['methodSetup']      = "WPInspector_setup";
$WPInspector['methodRegister']   = "WPInspector_register";
$WPInspector['methodAdminSetup'] = "WPInspector_adminSetup";

add_action('init', $WPInspector['methodInit'], 1);



function WPInspector_register($instance,$name) {
	global $WPInspector;
	Widget_register($WPInspector,$instance,$name);
}



function WPInspector_init() {
	global $WPInspector;
	Widget_init($WPInspector);
}


function WPInspector_setup() {
	global $WPInspector;
	Widget_setup($WPInspector);
}




function WPInspector_adminSetup() {
	global $WPInspector;
	Widget_adminSetup($WPInspector);
}



function WPInspector_render($args,$instance) {
	global $WPInspector;

	extract($args);

	$options = get_option($WPInspector['wpOptions']);

	echo(Panel_insert_widget_style($before_widget,$options[$instance]) . "\n");
	echo($before_title);
	_e("Inspect WordPress options",'theme');
	echo($after_title . "\n");?>

	Use it to debug WordPress options in wp_options table or internal variables.<br/>
	<form method="POST">
		<input type="text" name="option_name" value="<?php echo($_POST['option_name']); ?>"/>
		<input type="text" name="var_name" value="<?php echo($_POST['var_name']); ?>"/>
		<input type="submit" value="Inspect"/>
	</form><?php

	if ( isset($_POST['option_name']) && !empty($_POST['option_name']) ) {?>
		<pre style="border: 1px solid red; background: white; overflow: scroll">
Inspecting <?php echo($_POST['option_name']); ?><br/>
<?php print_r(get_option($_POST['option_name']));?>
		</pre><?php
	}

	if ( isset($_POST['var_name']) && !empty($_POST['var_name'])) {?>
		<pre style="border: 1px solid blue; background: white; overflow: scroll">
Inspecting <?php echo($_POST['var_name']); ?><br/>
<?php eval("global \$" . $_POST['var_name'] . "; echo(htmlentities(print_r(\$" . $_POST['var_name'] . ",true)));") ;?>
		</pre><?php
	}

	echo($after_widget);
}






/*
 * Future use....
 *
abstract class Widget {
	public $baseID;
	public $baseName;
	public $wpOptions;
	public $cssClassName;

	function __construct() { }
	abstract function render($args,$id);
	
	public function init() {
		$options = get_option($this->wpOptions);
		if (! is_array($options)) {
			$options=array();
			$options[$this->baseID . "-1"]=array();
			update_option($this->wpOptions, $options);
		}
	
		$i=1;
		foreach ($options as $id => $params) {
			call_user_func_array(array($this,'register'),
				array(&$id,$this->baseName . " [$i]"));
			$i++;
		}

		add_action('sidebar_admin_setup', array($this,'setup'));
		add_action('sidebar_admin_page',  array($this,'adminSetup'));
	}


	public function setup() {
		$options = $newoptions = get_option($this->wpOptions);
		$name=$this->baseID . "-number";

		if ( isset($_POST[$name . '-submit']) ) {
			$number = (int) $_POST[$name];
			if ( $number > 9 ) $number = 9;
			if ( $number < 1 ) $number = 1;
	
			if (sizeof($options) > $number) array_splice($newoptions,$number);
			if (sizeof($options) < $number) {
				for ($i=sizeof($options); $i<$number; $i++) {
					$previousSize=sizeof($newoptions);
					$newoptions[$this->baseID . "-" . ($previousSize+1)]=array();
				}
			}
		}
	
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option($this->wpOptions, $options);
			init();
		}
	}

	public function adminSetup() {
		$options = get_option($this->wpOptions);
		$name=$this->baseID . "-number";
		$size=sizeof($options);?>
	
		<div class="wrap">
			<form method="POST">
				<h2><?php printf(__('%s Widgets','theme'),$this->baseName); ?></h2>
				<p style="line-height: 30px;"><?php
					printf(__('How many <strong>%s</strong> widgets would you like?','theme'),$this->baseName);
					echo("<select id=\"$name\" name=\"$name\" value=\"$size\">\n");
					for ( $i = 1; $i < 10; ++$i )
						echo("<option value='$i' ".($size==$i ? "selected='selected'" : '').">$i</option>");?>
					</select>
					<span class="submit">
						<input type="submit" name="<?php echo($name)?>-submit" id="<?php echo($name)?>-submit" value="<?php echo attribute_escape(__('Save')); ?>" />
					</span>
				</p>
			</form>
		</div><?php
	}

	public function register($id,$name) {
		$opt['classname']=$this->cssClassName;
		$opt['params']=$id;

		wp_register_sidebar_widget($id,$name,array($this,'render'),$opt);

		addPanelStyleControl($id,$name);
	}

	public function addPanelStyleControl($id,$name) {
		$params=array();
		$params['id']=$id;
		$params['name']=$name;
		$params['wpOptionName']=$this->wpOptions;
	
		if (is_function($this->adminControl))
			$params['semanticCallback']=array($this,$this->adminControl);
	
		if ($dims) $params=array_merge($params,$this->dims);
		$params['height']+=250;

		wp_register_widget_control($id,$name,'Panel_widget_style_control',$params,$params);
	}
}

*/


















?>