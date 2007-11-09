<?php
/*
$Id$
*/


$BestPosts_cssClassName="widgetBestPosts";
$BestPosts_wpOptions="widget_bestposts";



function BestPosts_render($args,$instance) {
	extract($args);
	//$tabs=BestOf::$created[$instance]->tabs;

	$tabs = get_option($BestPosts_wpOptions);

	echo($before_widget . "\n");

	$index=0;
	echo("<ul class=\"tabs\">\n");
	foreach ($tabs[$instance] as $tab) {
		echo("<li id=\"tab-" . $index . "\">" . $tab['title'] . "</li>\n");
		$index++;
	}
	echo("</ul>\n");


	$index=0;
	echo("<div class=\"pop-in\">\n");
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






function BestPosts_control($params) {
	extract($params);
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
  var elSel = document.getElementById("tabs-" + id);

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
<select name="tabs-<?php echo("$id"); ?>" id="tabs-<?php echo("$id"); ?>"
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
<input type="hidden" id="current-tab-tag-<?php echo("$id"); ?>" value=""/>
<input type="hidden" id="<?php echo("$id"); ?>-tabs-tags" value=""/> <!-- the one field that matters -->
<label for="new-tab-title-<?php echo("$id"); ?>">New tab title</label><br/>
<input style="width: 80%;" name="new-tab-title-<?php echo("$id"); ?>" id="new-tab-title-<?php echo("$id"); ?>" type="text"/><br/>
<label for="new-tab-tag-<?php echo("$id"); ?>">New tab title</label><br/>
<input style="width: 80%;" name="new-tab-tag-<?php echo("$id"); ?>" id="new-tab-tag-<?php echo("$id"); ?>" type="text"/>
</td>

<td>
<input type="button" value="Update selected" onclick=""/>
</td>
</tr>
</table>





	<?php
}





function appendTab($instance,$title,$tag) {
	$tabs=get_option($BestPosts_wpOptions);
	
	$index=sizeof($tabs[$instance]);
	$tabs[$instance][$index]=array('title' => $title, 'tag' => $tag);

	update_option($BestPosts_wpOptions, $tabs);
}




function BestPosts_register($instance,$name) {
	$options['classname']=$BestPosts_cssClassName;
	$options['params']=$instance;
	wp_register_sidebar_widget($instance,$name,'BestPosts_render',$options);

	$dims = array('width' => 500, 'height' => 350);
	wp_register_widget_control($instance,$name,'BestPosts_control',$dims);
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