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


function __autoload($className) {
   include_once("{$className}.class.php");
}



class Context {
	private static $instance;
	public $panels=array();
	public $widgets=array();

	private function __construct() { }

	public static function getContext() {
		if (empty(self::$instance)) self::$instance=new Context();
		return self::$instance;
	}
}









/**
 * Panel is a container for Widgets.
 * It can be vertical - a.k.a. sidebar - or horizontal.
 */
class Panel {
	/** Weather this is a vertical (sidebar) or horizontal panel */
	public $isHorizontal=true;

	/** Reference to the real WP Sidebar object */
	public $wp_sidebar; /* [name], [id], [before_widget], [after_widget], [before_title], [after_title] */

	/** A global array of all panels instantiated */
	static public $panels=array();

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
//		$this->panels[$this->getId()]=$this;

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





/**
 * A Widget is everything that can be placed into a Panel.
 * Can be text widgets, RSS widgets, etc.
 */
abstract class Widget {
	/** Reference to the real WP Widget object */
	public $wp_widget; /* [name], [id], [callback], [params] */

	/** Widget class for CSS. */
	public $class;

	/** Wether to register with the WP Widget API */
	public $register=true;

	/** Callback function name. Can't be a method because WP's register_sidebar_widget() needs a real function name */
	public $callbackName;

	static public $widgets=array();

	function __construct($name,$id, $callbackName, $class="", $params=array(), $register=true) {
		global $wp_registered_widgets;

		$options['classname']=$class;
		$options['params']=$params;

		if ($register) {
			//print_r($this);
			wp_register_sidebar_widget($id,$name,$callbackName,$options);
		}

		$this->wp_widget=$wp_registered_widgets[$id];
//		$this->widgets[$id]=$this;

		$con=Context::getContext();
		$con->widgets[$id]=$this;
	}

	static public function render($args=array()) {}
}









// include(TEMPLATEPATH . '/post.php');


// Initialize Widgets.
new WidgetMultiPost("Flow of Posts","multipost");
new PanelAsWidget(new Panel("Sidebar 1","sidebar-1"));
new PanelAsWidget(new Panel("Sidebar 2","sidebar-2"));


// Initialize Sidebars.

//new Panel("Header 1","header-1",true);
//new Panel("Header 2","header-2",true);

new Panel("Footer 1","footer-1",true);
new Panel("Footer 2","footer-2",true);

new Panel("Main Multipost","main-multipost",true);
new Panel("Main Singlepost","main-singlepost",true);

?>
