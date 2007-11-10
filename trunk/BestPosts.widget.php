<?php
/*
$Id$
*/


$BestPosts_cssClassName = "widgetBestPosts";
$BestPosts_wpOptions = "widget_bestposts";



function BestPosts_render($args,$instance) {
	global $BestPosts_cssClassName, $BestPosts_wpOptions;

	extract($args);
	//$tabs=BestOf::$created[$instance]->tabs;

	$tabs = get_option($BestPosts_wpOptions);

	if (empty($tabs)) return;

	echo($before_widget . "\n");

	$index=0;
	echo("<ul class=\"tabs\">\n");
	echo("instance=$instance\n");
	print_r($tabs);
	foreach ($tabs[$instance] as $tab) {
		echo("<li id=\"tab-" . $index . "\">" . $tab['title'] . "</li>\n");
		$index++;
	}
	echo("</ul>\n");


	$index=0;
	echo("<div class=\"pop-in\">\n");
	echo("instance=$instance\n");
	foreach ($tabs[$instance] as $tab) {
		query_posts("tag=" . $tab['tag'] . "&order=DESC&showposts=15");
		echo("<ul id=\"list-$index\">\n");
		if (have_posts()) :
			while (have_posts()) : the_post();
				echo("<li>");
				the_title();
				echo("</li>\n");
			endwhile;
		endif;
		echo("</ul>\n");
		$index++;
	}
	echo("</div>\n");

	echo($after_widget . "\n");
}



function BestPosts_serialize($instance) {
	global $BestPosts_cssClassName, $BestPosts_wpOptions;

	$string="";

	$tabs = get_option($BestPosts_wpOptions);

	if (empty($tabs[$instance])) return $string;


	/* Build $string="tag1~Title 1^tag2~Title 2^tag3~Title 3" */
	foreach($tabs[$instance] as $fullTab) {
		if (!empty($string)) $string.="^";
		$string.=$fullTab['tag'] . "~" . $fullTab['title'];
	}
	return $string;
}


function BestPosts_unserialize($instance,$string) {
	global $BestPosts_cssClassName, $BestPosts_wpOptions;
	
	$final[$instance]=array();
	$unified=split("^",$string);
	
	foreach ($unified as $pair) {
		$temp=split("~",$pair);
		$final[$instance]['tag']=$temp[0];
		$final[$instance]['title']=$temp[1];
	}

	return $final;
}



function BestPosts_control($id) {
	global $BestPosts_cssClassName, $BestPosts_wpOptions;
//	extract($params);

	$options = $newoptions = get_option($BestPosts_wpOptions);

	if ( !is_array($options) )
		$options = $newoptions = array();

//	if ($_POST["$id-submit"]) {
//		$newoptions[$id][
//	}

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option($BestPosts_wpOptions, $options);
	}?>

<script type="text/javascript">
//<![CDATA[

// Reference: http://www.mredkj.com/tutorials/tutorial005.html


// This array is filled by tabsUnserialize() function and 
var BestPosts_tabs=new Array();
BestPosts_tabs['<?php echo("$id"); ?>']=new Array();



/** Unserialize the hidden <input id="${id}-tabs-tags"/> */
function tabsUnserialize(id) {
	var serElem=document.getElementById(id + "-tabs-tags");
	var array1;

	array1=serElem.value.split("^");
	for (var i=0; i<array1.length; i++) {
		var array2=array1[i].split("~");

		BestPosts_tabs[id][i]['tag']=array2[0];
		BestPosts_tabs[id][i]['title']=array2[1];
	}
}


/** Rebuilds the UI based on BestPosts_tabs[id] array. */
function tabsRebuildUI(id) {
	var elSel = document.getElementById(id + "-tabs");
	var elOpt;

	// First remove all.
	while (elOpt=elSel.childNodes[0]) elSel.removeChild(elOpt);

	// Now create new options based on the BestPosts_tabs[id] array
	for (var i=0; i<BestPosts_tabs[id].length; i++) {
	  elOpt = document.createElement('option');

		elOpt.text = BestPosts_tabs[id][i]['title'] + " (tag: " + BestPosts_tabs[id][i]['tag'] + ")";
		elOpt.value = i;

		try {
			// standards compliant; doesn't work in IE
			elSel.add(elOpt, null);
		} catch(ex) {
			// IE only
			elSel.add(elOpt);
		}
	}
}


function removeTabSelected(id) {
  var elSel = document.getElementById("tabs-" + id);
  var i;
  for (i = elSel.length - 1; i>=0; i--) {
    if (elSel.options[i].selected) {
      elSel.remove(i);
    }
  }
}

function appendTab(id) {
	var tabTitle=document.getElementById("new-tab-title-" + id).value;
	var tagName =document.getElementById("new-tab-tag-"   + id).value;
	
  var elOptNew = document.createElement('option');
  elOptNew.text = tabTitle + " (tag: " + tagName + ")";
  elOptNew.value = tagName + "|" + tabTitle;
	elOptNew.id = id + "-" + tagName;
	elOptNew.onclick = "optionSelected('" + id + "','" + elOptNew.id + "');";

	var elSel = document.getElementById(id + "-tabs");

	try {
		elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
	} catch(ex) {
		elSel.add(elOptNew); // IE only
	}
}


function optionSelected(id) {
	var elemSelect=document.getElementById("tabs-" + id);

  for (var i = elemSelect.length - 1; i>=0; i--) {
    if (elemSelect.options[i].selected) {
			var tabtag=elemSelect.options[i].value.split("|");
			document.getElementById("new-tab-tag-" + id).value=tabtag[0];
			document.getElementById("new-tab-title-" + id).value=tabtag[1];
			document.getElementById("current-tab-tag-" + id).value=elemSelect.options[i].id;

			break;
    }
  }
}

//]]>
</script>
<table border="1"><tr><td style="width: 50%">
<select id="<?php echo("$id"); ?>-tabs"
	onchange="optionSelected('<?php echo("$id"); ?>')"
	style="width: 100%" size="5"></select>
</td>

<td>
<input type="button" value="&uArr;" onclick=""/><br/>
<input type="button" value="&dArr;" onclick=""/>
</td>
</tr>

<tr>
<td>
<input type="button" value="Add tab" onclick="appendTab('<?php echo("$id"); ?>')"/> 
<input type="button" value="Delete tab" onclick="removeTabSelected('<?php echo("$id"); ?>')"/>
</td>
</tr>

<tr>
<td>
<!-- The one field that matters. All the rest is just UI. -->
<input type="hidden" id="<?php echo("$id"); ?>-tabs-tags" value="<?php echo(BestPosts_serialize($id)); ?>"/>

<input type="hidden" id="<?php echo("$id"); ?>-current-selection" value=""/>

<label for="<?php echo("$id"); ?>-title-edit">Tab title</label><br/>
<input style="width: 80%;" name="<?php echo("$id"); ?>-title-edit" id="<?php echo("$id"); ?>-title-edit" type="text"/><br/>
<label for="<?php echo("$id"); ?>-tag-edit">Tag containing posts</label><br/>
<input style="width: 80%;" name="<?php echo("$id"); ?>-tag-edit" id="<?php echo("$id"); ?>-tag-edit" type="text"/>
</td>

<td>
<input type="button" value="Update selected" onclick="tabsRebuildUI('<?php echo("$id"); ?>');"/>
</td>
</tr>
</table>

<script type="text/javascript">
//<![CDATA[
tabsRebuildUI('<?php echo("$id"); ?>');
//]]>
</script>



	<?php
}





function BestPosts_appendTab($instance,$title,$tag) {
	global $BestPosts_cssClassName, $BestPosts_wpOptions;

	$tabs=get_option($BestPosts_wpOptions);
	
	$index=sizeof($tabs[$instance]);
	$tabs[$instance][$index]=array('title' => $title, 'tag' => $tag);

	update_option($BestPosts_wpOptions, $tabs);
}




function BestPosts_register($instance,$name) {
	global $BestPosts_cssClassName, $BestPosts_wpOptions;

	$opt['classname']=$BestPosts_cssClassName;
	$opt['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'BestPosts_render',$opt);

	$dims = array('width' => 500, 'height' => 350);
	wp_register_widget_control($instance,$name,'BestPosts_control',$dims,$instance);
}



class BestOf extends Widget {
	static public $created=array();

	/**
	 * Indexed array like this:
	 * [0] = Array (
	 *          title="Tab title 1"
	 *          tag="tag1"
	 *       )
	 * [1] = Array (
	 *          title="Tab title 2"
	 *          tag="tag2"
	 *       )
	 * [2] = Array (
	 *          title="Tab title 3"
	 *          tag="tag3"
	 *       )
	 */
	public $tabs=array();

	public function __construct($name,$id=0,$register=true) {
		$params['id']=$id;
		BestOf::$created[$id]=$this;

		$dims = array('width' => 500, 'height' => 350);

		parent::__construct($name,$id,'BestOf_render','widget_bestof',$params,'BestOf_control',$dims,$params);

		$options = get_option('widget_bestof');
	}







	public static function render($args,$id) {
		BestOf_render($args,$id);
	}
}
















?>