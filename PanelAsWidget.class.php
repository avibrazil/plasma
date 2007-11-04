<?php
/*

$Id$
*/



function PanelAsWidget_render($args,$sidebarName) {
	//global $wp_registered_widgets;
	extract($args);
	//$options=get_option('widget_panel');

	echo($before_widget . "\n");

	dynamic_sidebar($sidebarName);

	echo($after_widget . "\n");
}





class PanelAsWidget extends Widget {
	/** Encapsulated panel of class Panel */
	public $panel;

	static private $index=1;
	static public $created=array();

	function __construct($name,$id=0,$register=true) {
		if (get_class($name) == "Panel") {
			$this->panel=$name;
			$params['params']['panel_name']=$this->panel->wp_sidebar['name'];

			parent::__construct($this->panel->wp_sidebar['name'],
				$this->panel->wp_sidebar['id'],
				"PanelAsWidget_render",
				'widget_panel',
				$params,
				true);
		} else {
			$params['params']['panel_name']=$name;
			parent::__construct($name,
				$id,
				"PanelAsWidget_render",
				'widget_panel',
				$params,$register);
			$this->panel=new Panel($name,$id,$isHorizontal);
		}
		PanelAsWidget::$created[PanelAsWidget::$index]=$this;
		PanelAsWidget::$index++;

		$options=get_option('widget_panel');
		$options[PanelAsWidget::$index]['panel_name']=$this->panel->getName();
		update_option('widget_panel',$options);
	}


	static public function render($args,$num=0) {
		PanelAsWidget_render($args,$num);
	}
}





?>