<?php
/*

$Id$
*/

function BestOf_render($args,$ident) {
	extract($args);
	$tabs=BestOf::$created[$ident]->tabs;


	echo($before_widget . "\n");

	print_r($args);
	print_r("num=$ident");
	print_r("tabs=$tabs");

	echo("<ul class=\"tabs\">\n");
	foreach ($tabs as $tab) {
		echo("<li id=\"tab-" . $index . "\">" . $tab['title'] . "</li>\n");
		$index++;
	}
	echo("</ul>\n");


	$index=0;
	echo("<div class=\"pop-in\">\n");
	foreach ($tabs as $tab) {
		query_posts("cat=" . $tab['tag'] . "&order=ASC");
		echo("<ul id=\"list-$index\">\n");
		if (have_posts()) :
			while (have_posts()) : the_post();
				echo("<li>");
				the_title();
				echo("</li>");
			endwhile;
		endif;
		echo("</ul>\n");
	}
	echo("</div>\n");

	echo($after_widget . "\n");
}






function BestOf_control($id) {
	$options = $newoptions = get_option('widget_bestof');

	if ( !is_array($options) )
		$options = $newoptions = array();

//	if ($_POST["$id-submit"]) {
//		$newoptions[$id][
//	}

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_bestof', $options);
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
	var tabTitle=document.getElementById('new-tab-title-' + id).value;
	var tagName=document.getElementById('new-tab-tag-' + id).value;
	
  var elOptNew = document.createElement('option');
  elOptNew.text = tabTitle + " (tag: " + tagName + ")";
  elOptNew.value = tabTitle + "|" + tagName;
  var elSel = document.getElementById("tabs-" + id);

  try {
    elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
  } catch(ex) {
    elSel.add(elOptNew); // IE only
  }
}

//]]>
</script>
<table border="1"><tr><td style="width: 80%">
<select style="width: 90%" multiple="multiple" name="tabs-<?php echo("$id"); ?>" id="tabs-<?php echo("$id"); ?>">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="fiat">Fiat</option>
  <option value="audi">Audi</option>
</select>
</td>
<td>
<input type="button" value="&uarr;" onclick=""/><br/>
<input type="button" value="&darr;" onclick=""/>
</td>
</tr><tr>
<td>
<input type="button" value="Add tab" onclick="appendTab('tabs-<?php echo("$id"); ?>')"/> 
<input type="button" value="Delete tab" onclick="removeOptionSelected('tabs-<?php echo("$id"); ?>')"/>
</td>
</tr><tr>
<td>
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
		$params[$id]=$id;
		BestOf::$created[$id]=$this;

		$dims = array('width' => 600, 'height' => 450);

		parent::__construct($name,$id,'BestOf_render','widget_bestof',$params,'BestOf_control',$dims,$params);
	}

	public static function render($args,$id) {
		BestOf_render($args,$id);
	}
}

?>