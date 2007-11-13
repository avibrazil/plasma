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



function tabsAdd(id,title,tag) {
	var i=BestPosts_tabs[id].length;

	BestPosts_tabs[id][i]=new Array;

	BestPosts_tabs[id][i]['tag']=tag;
	BestPosts_tabs[id][i]['title']=title;
}



/** Unserialize the hidden <input id="${id}-tabs-tags"/> */
function tabsUnserialize(id) {
	var serElem=document.getElementById(id + "-tabs-tags");
	var array1;

	array1=serElem.value.split("^");
	for (var i=0; i<array1.length; i++) {
		var array2=array1[i].split("~");

		tabsAdd(id,array2[1],array2[0]);
	}
}

/** Serializes BestPosts_tabs[id] into the hidden <input id="${id}-tabs-tags"/> */
function tabsSerialize(id) {
	var serialized="";

	for (var i=0; i<BestPosts_tabs[id].length; i++) {
		if (serialized != "") serialized+="^";

		serialized+=BestPosts_tabs[id][i]['tag'];
		serialized+="~";
		serialized+=BestPosts_tabs[id][i]['title'];
	}

	document.getElementById(id + "-tabs-tags").value=serialized;
}


/** Rebuilds the UI based on BestPosts_tabs[id] array. */
function tabsRebuildUI(id) {
	var elSel = document.getElementById(id + "-tabs");
	var elOpt;
	var selectedIndex=null;

	if (arguments.length>1) selectedIndex=arguments[1];

	// First remove all.
	while (elOpt=elSel.childNodes[0]) elSel.removeChild(elOpt);

	// Now create new options based on the BestPosts_tabs[id] array
	for (var i=0; i<BestPosts_tabs[id].length; i++) {
		elOpt = document.createElement('option');

		elOpt.text = BestPosts_tabs[id][i]['title'] + " (tag: " + BestPosts_tabs[id][i]['tag'] + ")";
		elOpt.value = i;

		if (selectedIndex!=null && selectedIndex==i) elOpt.selected=1;

		try {
			// standards compliant; doesn't work in IE
			elSel.add(elOpt, null);
		} catch(ex) {
			// IE only
			elSel.add(elOpt);
		}
	}
	document.getElementById(id + "-title-edit").value="";
	document.getElementById(id + "-tag-edit").value="";
}


function actionAddTab(id) {
	var tabTitle=document.getElementById(id + "-title-edit").value;
	var tabTag =document.getElementById(id + "-tag-edit").value;

	tabsAdd(id,tabTitle,tabTag);
	tabsSerialize(id);
	tabsRebuildUI(id);
}


function actionRemoveSelectedTab(id) {
	var elemSelect=document.getElementById(id + "-tabs");

	for (var i=0; i<elemSelect.length; i++) {
		if (elemSelect.options[i].selected) {
			BestPosts_tabs[id].splice(i,1);
			break;
		}
	}
	tabsSerialize(id);
	tabsRebuildUI(id);
}



function actionUpdateSelectedTab(id) {
	var tabTitle=document.getElementById(id + "-title-edit").value;
	var tabTag=document.getElementById(id + "-tag-edit").value;
	var elemSelect=document.getElementById(id + "-tabs");

	if (tabTag=="") return;

	for (var i=0; i<elemSelect.length; i++) {
		if (elemSelect.options[i].selected) {
			BestPosts_tabs[id][i]['tag']=tabTag;
			BestPosts_tabs[id][i]['title']=tabTitle;
			break;
		}
	}
	tabsSerialize(id);
	tabsRebuildUI(id);
}



function actionMoveSelectedUp(id) {
	var elemSelect=document.getElementById(id + "-tabs");

	for (var i=0; i<elemSelect.length; i++) {
		if (elemSelect.options[i].selected) {
			if (i==0) return;

			var prev=BestPosts_tabs[id][i-1];
			BestPosts_tabs[id][i-1]=BestPosts_tabs[id][i];
			BestPosts_tabs[id][i]=prev;

			break;
		}
	}
	tabsSerialize(id);
	tabsRebuildUI(id,i-1);
}


function actionMoveSelectedDown(id) {
	var elemSelect=document.getElementById(id + "-tabs");

	for (var i=0; i<elemSelect.length; i++) {
		if (elemSelect.options[i].selected) {
			if (i==elemSelect.length-1) return;

			var next=BestPosts_tabs[id][i+1];
			BestPosts_tabs[id][i+1]=BestPosts_tabs[id][i];
			BestPosts_tabs[id][i]=next;

			break;
		}
	}
	tabsSerialize(id);
	tabsRebuildUI(id,i+1);
}


function actionSelectTab(id) {
	var elemSelect=document.getElementById(id + "-tabs");

	for (var i=0; i<elemSelect.length; i++) {
		if (elemSelect.options[i].selected) {
			document.getElementById(id + "-title-edit").value=BestPosts_tabs[id][i]['title'];
			document.getElementById(id + "-tag-edit").value=BestPosts_tabs[id][i]['tag'];

			break;
		}
	}
}



//]]>
</script>

<!-- The one field that matters. All the rest is just UI. -->
<input type="hidden" id="<?php echo("$id"); ?>-tabs-tags" value="<?php echo(BestPosts_serialize($id)); ?>"/>

<table border="1"><tr><td style="width: 90%">
<select id="<?php echo("$id"); ?>-tabs"
	onchange="actionSelectTab('<?php echo("$id"); ?>')"
	style="width: 100%" size="5"></select>
</td>

<td>
<input type="button" value="&uArr;" onclick="actionMoveSelectedUp('<?php echo("$id"); ?>');"/><br/>
<input type="button" value="&dArr;" onclick="actionMoveSelectedDown('<?php echo("$id"); ?>');"/>
</td>
</tr>

<tr>
<td>
<input type="button" value="Add tab" onclick="actionAddTab('<?php echo("$id"); ?>')"/> 
<input type="button" value="Delete tab" onclick="actionRemoveSelectedTab('<?php echo("$id"); ?>')"/>
<input type="button" value="Update selected" onclick="actionUpdateSelectedTab('<?php echo("$id"); ?>');"/>
</td>
</tr>

<tr>
<td>
<label for="<?php echo("$id"); ?>-title-edit">Tab title</label><br/>
<input style="width: 80%;" name="<?php echo("$id"); ?>-title-edit" id="<?php echo("$id"); ?>-title-edit" type="text"/><br/>
<label for="<?php echo("$id"); ?>-tag-edit">Tag containing posts</label><br/>
<input style="width: 80%;" name="<?php echo("$id"); ?>-tag-edit" id="<?php echo("$id"); ?>-tag-edit" type="text"/>
</td>

</tr>
</table>

<script type="text/javascript">
//<![CDATA[
tabsUnserialize('<?php echo("$id"); ?>');
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