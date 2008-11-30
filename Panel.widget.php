<?php
/*

$Id$
*/



/*****************************************************************
 *
 * The Panel-as-a-Widget (a.k.a. Sidebar-as-a-Widget) methods
 *
 *****************************************************************/


$PanelWidget=array();
$PanelWidget['baseID']           = "panelwidget";
$PanelWidget['baseName']         = __("Panel as Widget",'theme');
$PanelWidget['wpOptions']        = "widget_panel";
$PanelWidget['cssClassName']     = "widgetPanel";
$PanelWidget['renderCallback']   = "PanelWidget_render";
$PanelWidget['methodInit']       = "PanelWidget_init";
$PanelWidget['methodSetup']      = "PanelWidget_setup";
$PanelWidget['methodRegister']   = "PanelWidget_register";
$PanelWidget['methodAdminSetup'] = "PanelWidget_adminSetup";
$PanelWidget['controlCallback']  = "PanelWidget_control";
$PanelWidget['controlSize']      = array('width' => 380, 'height' => 280);

add_action('init', $PanelWidget['methodInit'], 1);




function PanelWidget_render($args,$instance) {
	global $PanelWidget;

	extract($args);
	
	$panels=get_option($PanelWidget['wpOptions']);

	if (empty($panels[$instance]['panelID'])) return;

	echo(Panel_insert_widget_style($before_widget,$panels[$instance]) . "\n");

	Panel_render($panels[$instance]['panelID']);

	echo($after_widget . "<!-- id=$instance -->\n");
}




function PanelWidget_init() {
	global $PanelWidget;
	Widget_init($PanelWidget);
}



function PanelWidget_setup() {
	global $PanelWidget;
	Widget_setup($PanelWidget);
}




function PanelWidget_adminSetup() {
	global $PanelWidget;
	Widget_adminSetup($PanelWidget);
}


function PanelWidget_register($instance, $name , $panelID="", $panelName="",$horizontal=false) {
	global $PanelWidget;
	global $wp_registered_sidebars;

	$options = get_option($PanelWidget['wpOptions']);

	if (! isset($options[$instance]['panelName'])) {
		$options[$instance]['panelName']=$name;
		update_option($PanelWidget['wpOptions'],$options);
	}

	Widget_register($PanelWidget,$instance,$options[$instance]['panelName']);

	if (!array_key_exists($options[$instance]['panelID'],$wp_registered_sidebars)) {
		// Panel was not previously registered. Register.
		Panel_register($options[$instance]['panelID'],
			$options[$instance]['panelName'],
			$options[$instance]['panelStyle']=="h");
	}
}







function PanelWidget_control($id) {
	global $PanelWidget;
	global $wp_registered_sidebars;

	$options = get_option($PanelWidget['wpOptions']);

	if (! is_array($options))
		$options = array();

	if (!is_array($options[$id]))
		$options[$id]=array();

//	if (!is_array($options[$id]['tabs']))
//		$options[$id]['tabs']=array();

	$newoptions=$options[$id];

	if ($_POST["$id-source"]=="$id-existing") {
		if ($_POST["$id-panelID-existing"]) {
			$newoptions['panelID']=stripslashes($_POST["$id-panelID-existing"]);
			$newoptions['panelName']=$wp_registered_sidebars[$newoptions['panelID']]['name'];
			$newoptions['panelStyle']=$wp_registered_sidebars[$newoptions['panelID']]['horizontal']?"h":"v";
		}
	} else if ($_POST["$id-source"]=="$id-new") {
		if ($_POST["$id-panelID-new"])
			$newoptions['panelID']=stripslashes($_POST["$id-panelID-new"]);

		if ($_POST["$id-panelName-new"])
			$newoptions['panelName']=stripslashes($_POST["$id-panelName-new"]);
		else $newoptions['panelName']=$newoptions['panelID'];

		if ($_POST["$id-panelStyle-new"])
			$newoptions['panelStyle']=stripslashes($_POST["$id-panelStyle-new"]);
	}

	if ($options[$id] != $newoptions) {
		$options[$id]=$newoptions;
		update_option($PanelWidget['wpOptions'], $options);
	}

?>

<b><input type="radio" checked="checked" name="<?php echo($id); ?>-source" id="<?php echo($id); ?>-source-existing" value="<?php echo($id); ?>-existing" />
<label for="<?php echo($id); ?>-source-existing">This widget renders the panel:</label></b><br/>
<select name="<?php echo($id); ?>-panelID-existing" style="width: 90%" size="5"><?php
	foreach ($wp_registered_sidebars as $p) {
		if ($options[$id]['panelID']==$p['id']) $selected='selected="selected"';
		else $selected="";
		printf('<option %1$s value="%2$s">%3$s (%4$s) &lt;%2$s&gt;</option>',
			$selected,
			$p['id'],
			$p['name'],
			$p['horizontal'] ? "h" : "v");
	}?>
</select>
<br/>
<b><input type="radio" name="<?php echo($id); ?>-source" id="<?php echo($id); ?>-source-new" value="<?php echo($id); ?>-new" />
<label for="<?php echo($id); ?>-source-new">Or create a new panel (a.k.a. sidebar):</label></b><br/>
<input type="text" name="<?php echo($id); ?>-panelID-new"> ID (required)<br/>
<input type="text" name="<?php echo($id); ?>-panelName-new"> Name in widget admin interface<br/>
<select name="<?php echo($id); ?>-panelStyle-new">
	<option value="h" selected="selected">Horizontal, side by side</option>
	<option value="v">Vertical, as a sidebar</option>
</select> Alignment for contained widgets<?php
}




function Panel_register($id,$name,$horizontal=true) {
	$params['name']=$name;
	if ($id) $params['id']=$id;

	if ($horizontal) $params['horizontal']=1;


	// Creates a widget wrapper that can receive embedded style.
	// The empty style="" will be subtituted by what user configures
	// in the widgets admin interface.
	$params['before_widget']='<div id="%1$s" class="widget %2$s" style="">';
	$params['after_widget']='</div>';

	register_sidebar($params);
}



function Panel_render($instance) {
	global $wp_registered_sidebars;

/*
echo("<pre>");
echo("instance=$instance :::: \n");
print_r($wp_registered_sidebars[$instance]);
echo("</pre>");
*/
	if (isset($wp_registered_sidebars[$instance]) &&
			$wp_registered_sidebars[$instance]['horizontal']==1)
		$class='horizontal-panel';
	else $class='vertical-panel';

	echo "<div class=\"panel $class\" id=\"$instance\">\n";
	dynamic_sidebar($instance);
	echo("</div> <!-- id=$instance -->\n\n");
}



/**
 * This method should be called by all widgets at registration time.
 * It adds a default control to widget admin UI to let user define
 * widget positioning.
 */
function Panel_add_style_control($instance,$name,$wpOptionName,$callback=0,$dims=0) {
	$params=array();
	$params['id']=$instance;
	$params['wpOptionName']=$wpOptionName;
	$params['name']=$name;

	if ($callback) $params['semanticCallback']=$callback;

	if ($dims) $params=array_merge($params,$dims);
	$params['height']+=250;

	wp_register_widget_control($instance,$name,'Panel_widget_style_control',$params,$params);
}




/**
 * This method should be called by widgets in rendering time.
 * It will embed the positioning style in $before_widget, as defined
 * by the user in the widget admin interface.
 *
 * @see Panel_add_style_control(), Panel_widget_style_control()
 *
 */
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




/**
 * The business logic that creates and handles the default widget
 * positioning controls in the widgets UI interface.
 *
 * @see Panel_insert_widget_style(),Panel_add_style_control()
 */
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
<b>Widget ID:</b> <?php echo($id); ?><br/>
<b>Widget Name:</b> <?php echo($name); ?><br/>
<hr/>
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




/*
function PanelWidget_ask_number($instance) {
	global $PanelWidget_cssClassName, $PanelWidget_wpOptions;

	$options = $newoptions = get_option($PanelWidget_wpOptions);?>

	<div class="wrap">
		<form method="POST">
			<h2><?php printf(__("Panel %s",'theme'),$options[$instance]['panel']); ?></h2>
			<p style="line-height: 30px;"><?php printf(__('How many %s you would like?','theme'),$options[$instance]['panel']); ?>
				<select id="<?php echo($options[$instance]['panel']); ?>-number" name="<?php echo($options[$instance]['panel']); ?>-number" value="<?php echo $options['number']; ?>"><?php
					for ( $i = 1; $i < 10; ++$i )
						echo("<option value='$i' ".($options['number']==$i ? "selected='selected'" : '').">$i</option>");?>
				</select>
				<span class="submit"><input type="submit" name="text-number-submit" id="text-number-submit"
					value="<?php echo attribute_escape(__('Save')); ?>" /></span></p>
		</form>
	</div><?php
}
*/

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
