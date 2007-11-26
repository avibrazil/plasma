<?php

/*
Template Name: Theme initialization

$Id$
*/

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




function Panel_widget_style_control($params) {
	extract($params);

	$options = $newoptions = get_option($wpOptionName);
	if ( !is_array($options) )
		$options = array();

//echo("<pre>");
//print_r($_POST);
//print_r(get_option('sidebars_widgets'));
//echo("</pre>");

	if (isset($callback))
		$newoptions=call_user_func_array( $callback, $params);

	if ( !is_array($newoptions) )
		$newoptions = array();

	if ($_POST["$id-float"])
		$newoptions['float']=stripslashes($_POST["$id-float"]);

	if ($_POST["$id-clear"])
		$newoptions['clear']=stripslashes($_POST["$id-clear"]);

	if ($_POST["$id-width"])
		$newoptions['width']=stripslashes($_POST["$id-width"]);


	if ( $newoptions != $options[$id] ) {
		$options[$id] = $newoptions;
		update_option($wpOptionName, $options);
	}?>

<?php
echo("<pre>");
print_r($options[$id]);
//print_r(get_option('sidebars_widgets'));
echo("</pre>");
?>

<label for="<?php echo($id); ?>-float"><b>Put widget on</b></label><br/>
<select name="<?php echo($id); ?>-float">
	<option value="default" <?php if (!isset($options[$id]['float']) || $options[$id]['float']=="default") echo("selected=\"selected\""); ?>>Default</option>
	<option value="left" <?php if ($options[$id]['float']=='left') echo("selected=\"selected\""); ?>>Left</option>
	<option value="right" <?php if ($options[$id]['float']=='right') echo("selected=\"selected\""); ?>>Right</option>
</select>
<br/>
<label for="<?php echo($id); ?>-clear"><b>Don't let other widgets float on</b></label><br/>
<select name="<?php echo($id); ?>-clear">
	<option value="default" <?php if (!isset($options[$id]['clear']) || $options[$id]['clear']=="default") echo("selected=\"selected\""); ?>>Default</option>
	<option value="left" <?php if ($options[$id]['clear']=="left") echo("selected=\"selected\""); ?>>Left</option>
	<option value="right" <?php if ($options[$id]['clear']=="right") echo("selected=\"selected\""); ?>>Right</option>
	<option value="both" <?php if ($options[$id]['clear']=="both") echo("selected=\"selected\""); ?>>Both</option>
</select>
<br/>
<label for="<?php echo($id); ?>-width"><b>Widget width (please specify units as in CSS)</b></label><br/>
<input name="<?php echo($id); ?>-width" type="text" value="<?php echo($options[$id]['width']); ?>"/><?php
}



function Panel_add_style_control($instance,$name,$wpOptionName,$callback=0,$dims=0) {
	$params=array('width' => 380, 'height' => 280);
	$params['wpOptionName']=$wpOptionName;
	$params['id']=$instance;
	$params['name']=$name;

	if ($callback) $params['privateCallback']=$callback;

	if ($dims) $params=array_merge($params,$dims);

	wp_register_widget_control($instance,$name,'Panel_widget_style_control',$params,$params);
}

function Panel_insert_widget_style($string,$options) {
	$style="";

	if (isset($options['float']) && $options['float']!='default')
		$style="float:" . $options['float'] . ";";

	if (isset($options['clear']) && $options['clear']!='default')
		$style.="clear:" . $options['clear'] . ";";

	if (isset($options['width']) && !isempty($options['width']))
		$style.="width:" . $options['width'] . ";";

	if (empty($style)) return $string;
	else return str_replace('style=""', $style, $string);
}

?>