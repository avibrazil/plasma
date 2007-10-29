<?php

/*
Template Name: Theme initialization

$Id$
*/



// SEVERE WARNING:
// This file cannot contain empty lines outside < ?php   ? >
// Otherwise it will cause many
// "Cannot modify header information - headers already sent by" messages in
// various points of your WP blog

// Initialize internationalization and localization (a.k.a. tropicalization)
load_plugin_textdomain('theme','wp-content/themes/soleilpro/languages');
load_plugin_textdomain('personal','wp-content/themes/soleilpro/languages');





class Context {
	private static $instance;
	public $panels=array();

	private function __construct() { }

	public static function getContext() {
		if (empty(self::$instance)) self::$instance=new Context();
		return self::$instance;
	}
}










class Panel {
	public $isHorizontal=true;
	public $wp_sidebar;

	function __construct($name,$id=0,$isHorizontal=false) {
		global $wp_registered_sidebars;

		$this->isHorizontal=$isHorizontal;

		$params['name']=$name;
		if ($id) $params['id']=$id;

		if ($this->isHorizontal) {
			// Use the table model for a horizontal "sidebar"
			$params['before_widget']='<td id="%1$s" class="widget %2$s">';
			$params['after_widget']='</td>';
		} else {
			// Use the regular positioning for a real vertical sidebar
			$params['before_widget']='<div id="%1$s" class="widget %2$s">';
			$params['after_widget']='</div>';
		}

		$this->wp_sidebar=$wp_registered_sidebars[register_sidebar($params)];

		$con=Context::getContext();
		$con->panels[$this->getId()]=$this;
	}


	function render() {
		if ($this->isHorizontal) {
			echo "<table class=\"horizontal-panel\" id=\"" . $this->getId() . "\"><tbody><tr>\n";
			dynamic_sidebar($this->getName());
			echo("</tr></tbody></table>\n\n");
		} else {
			echo "<div class=\"vertical-panel\" id=\"" . $this->getId() . "\">\n";
			dynamic_sidebar($this->getName());
			echo("</div>\n\n");
		}
	}

	function getId() {
		return $this->wp_sidebar['id'];
	}

	function getName() {
		return $this->wp_sidebar['name'];
	}
}



abstract class Widget {
	/** Widget name as it should appear in the widget admin interface. */
	public $name;

	/** Widget id as it should appear in the HTML tag. */
	public $id;

	/** Widget class for CSS. */
	public $class;

	/** Wether to register with the WP Widget API */
	public $register=true;

	function __construct($name,$id,$register=true,$class="") {
		$this->name=$name;
		$this->id=$id;
		$this->class=$class;
		$this->register=$register;

		if ($register) {
			//print_r($this);
			register_sidebar_widget($name, 'render', $class);
		}
	}

	static public function render($args=array()) {}
}

include(TEMPLATEPATH . '/post.php');


// Initialize Widgets.
new WidgetMultiPost();


// Initialize Sidebars.

new Panel("Sidebar 1","sidebar-1");
new Panel("Sidebar 2","sidebar-2");

new Panel("Header 1","header-1",true);
new Panel("Header 2","header-2",true);

new Panel("Footer 1","footer-1",true);
new Panel("Footer 2","footer-2",true);

new Panel("Main Multipost","main-multipost",true);
new Panel("Main Singlepost","main-singlepost",true);

?>