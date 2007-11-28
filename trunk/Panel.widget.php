<?php
/*

$Id$
*/


$PanelWidget_cssClassName = "widgetPanel";
$PanelWidget_wpOptions = "widget_panel";



function Panel_register($id,$name,$horizontal=false) {
	$params['name']=$name;
	if ($id) $params['id']=$id;

	if ($horizontal) $params['horizontal']=1;

	$params['before_widget']='<div id="%1$s" class="widget %2$s" style="">';
	$params['after_widget']='</div>';

	register_sidebar($params);
}



function Panel_render($instance) {
	global $wp_registered_sidebars;

	if ($wp_registered_sidebars[$instance]['horizontal']) $class='horizontal-panel';
	else $class='vertical-panel';

	echo "<div class=\"$class\" id=\"$instance\">\n";
	dynamic_sidebar($instance);
	echo("</div>\n\n");
}




function Panel_add_style_control($instance,$name,$wpOptionName,$callback=0,$dims=0) {
	$params=array();
	$params['id']=$instance;
	$params['wpOptionName']=$wpOptionName;
	$params['name']=$name;

	if ($callback) $params['semanticCallback']=$callback;

	if ($dims) $params=array_merge($params,$dims);
	$params['height']+=230;

	wp_register_widget_control($instance,$name,'Panel_widget_style_control',$params,$params);
}



function Panel_insert_widget_style($string,$options) {
	$style="";

	if (isset($options['pos']['float']) && $options['pos']['float']!='default')
		$style="float:" . $options['pos']['float'] . ";";

	if (isset($options['pos']['clear']) && $options['pos']['clear']!='default')
		$style.="clear:" . $options['pos']['clear'] . ";";

	if (isset($options['pos']['width']) && !empty($options['pos']['width']))
		$style.="width:" . $options['pos']['width'] . ";";

	if (empty($style)) return $string;
	else return str_replace('style=""', 'style="' . $style . '"', $string);
}





function Panel_widget_style_control($params) {
	extract($params);

	$options = get_option($wpOptionName);

	if ( !is_array($options) )
		$options = array();

	$newoptions=$options[$id]['pos'];

	if ( !is_array($newoptions) )
		$newoptions = array();



	if (isset($semanticCallback))
		call_user_func_array($semanticCallback, $params);

	if ($_POST["$id-float"])
		$newoptions['float']=stripslashes($_POST["$id-float"]);

	if ($_POST["$id-clear"])
		$newoptions['clear']=stripslashes($_POST["$id-clear"]);

	if (isset($_POST["$id-width"]))
		$newoptions['width']=stripslashes($_POST["$id-width"]);


	if ( $newoptions != $options[$id]['pos'] ) {
		$options[$id]['pos'] = $newoptions;
		update_option($wpOptionName, $options);
	}?>
<hr/>
<b style="text-decoration: underline">Positioning options</b><br/>
<label for="<?php echo($id); ?>-float"><b>Align widget on</b></label><br/>
<select name="<?php echo($id); ?>-float">
	<option value="default" <?php if (!isset($options[$id]['pos']['float']) || $options[$id]['pos']['float']=="default") echo("selected=\"selected\""); ?>>Default</option>
	<option value="left" <?php if ($options[$id]['pos']['float']=='left') echo("selected=\"selected\""); ?>>Left</option>
	<option value="right" <?php if ($options[$id]['pos']['float']=='right') echo("selected=\"selected\""); ?>>Right</option>
</select>
<br/>
<label for="<?php echo($id); ?>-clear"><b>Don't let other widgets float on</b></label><br/>
<select name="<?php echo($id); ?>-clear">
	<option value="default" <?php if (!isset($options[$id]['pos']['clear']) || $options[$id]['pos']['clear']=="default") echo("selected=\"selected\""); ?>>Default</option>
	<option value="left" <?php if ($options[$id]['pos']['clear']=="left") echo("selected=\"selected\""); ?>>Left</option>
	<option value="right" <?php if ($options[$id]['pos']['clear']=="right") echo("selected=\"selected\""); ?>>Right</option>
	<option value="both" <?php if ($options[$id]['pos']['clear']=="both") echo("selected=\"selected\""); ?>>Both</option>
</select>
<br/>
<label for="<?php echo($id); ?>-width"><b>Widget width (please specify units as in CSS)</b></label><br/>
<input name="<?php echo($id); ?>-width" type="text" value="<?php echo($options[$id]['pos']['width']); ?>"/><?php
}








function PanelWidget_render($args,$instance) {
	global $PanelWidget_cssClassName, $PanelWidget_wpOptions;

	extract($args);
	
	$panels=get_option($PanelWidget_wpOptions);

	if (empty($panels[$instance]['panel'])) return;

	echo(Panel_insert_widget_style($before_widget,$panels[$instance]) . "\n");

	Panel_render($panels[$instance]['panel']);

	echo($after_widget . "\n");
}


function PanelWidget_register($instance, $name, $panelID="", $panelName="",$horizontal=false) {
	global $PanelWidget_cssClassName, $PanelWidget_wpOptions;
	global $wp_registered_sidebars;

	$opt['classname']=$PanelWidget_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'PanelWidget_render',$opt);

	Panel_add_style_control($instance,$name,$PanelWidget_wpOptions);

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