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

		$this->wp_sidebar=register_sidebar($params);

		$con=Context::getContext();
		$con->panels[$this->getId()]=$this;
	}


	function render() {
		if ($this->isHorizontal) {
			echo "<table id=\"$this->wp_sidebar['id']\"><tbody><tr>";
			dynamic_sidebar($this->wp_sidebar['id']);
			echo('</tr></tbody></table>');
		} else {
			echo "<div id=\"$this->wp_sidebar['id']\">";
			dynamic_sidebar($this->wp_sidebar['id']);
			echo('</div>');
		}
	}

	function getId() {
		return $this->wp_sidebar['id'];
	}

}

// Initialize Widgets.


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