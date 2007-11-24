<?php
/*

$Id$
*/


require_once('Panel.php');


$PanelWidget_cssClassName = "widgetPanel";
$PanelWidget_wpOptions = "widget_panel";



function PanelWidget_render($args,$instance) {
	global $PanelWidget_cssClassName, $PanelWidget_wpOptions;

	extract($args);
	
	$panels=get_option($PanelWidget_wpOptions);

	if (empty($panels[$instance]['panel'])) return;

	echo($before_widget . "\n");

	Panel_render($panels[$instance]['panel']);

	echo($after_widget . "\n");
}


function PanelWidget_register($instance, $name, $panelID="", $panelName="",$horizontal=false) {
	global $PanelWidget_cssClassName, $PanelWidget_wpOptions;
	global $wp_registered_sidebars;

	$opt['classname']=$PanelWidget_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'PanelWidget_render',$opt);

	add_widget_default_control($instance,$name,$PanelWidget_wpOptions);

	if (empty($panelID)) $panelID="panel-" . $instance;

	if (empty($wp_registered_sidebars[$panelID])) {
		// Panel (a.k.a. sidebar) doesn't exist. Register a new panel.
		if (empty($panelName)) $panelName=$name;
		Panel_register($panelID,$panelName,$horizontal);
	}

	// Bind this widget to its panel
	$options=$newoptions=get_option($PanelWidget_wpOptions);
	if ( !is_array($options) ) $options = $newoptions = array();

	$newoptions[$instance]['panel']=$panelID;

	if ($options != $newoptions) {
		$options = $newoptions;
		update_option($PanelWidget_wpOptions, $options);
	}
}



/* Old stuff

class PanelAsWidget extends Widget {
	public $panel;

	static private $index=1;
	static public $created=array();

	function __construct($name,$id=0,$register=true) {
		if (get_class($name) == "Panel") {
			$this->panel=$name;
			$params['panel_name']=$this->panel->wp_sidebar['name'];

			parent::__construct($this->panel->wp_sidebar['name'],
				$this->panel->wp_sidebar['id'],
				"PanelAsWidget_render",
				'widget_panel',
				$params,'',0,0,
				true);
		} else {
			$params['panel_name']=$name;
			parent::__construct($name,
				$id,
				"PanelAsWidget_render",
				'widget_panel',
				$params,'',0,0,
				$register);
			$this->panel=new Panel($name,$id,$isHorizontal);
		}
		PanelAsWidget::$created[PanelAsWidget::$index]=$this;
		PanelAsWidget::$index++;

		$options=get_option('widget_panel');
		$options[PanelAsWidget::$index]['panel_name']=$this->panel->getName();
		update_option('widget_panel',$options);
	}


	static public function render($args,$sidebarName) {
		PanelAsWidget_render($args,$sidebarName);
	}
}


*/


?>