<?php
/*

$Id$
*/

function BestOf_render($args,$num) {
	extract($args);
	$tabs=BestOf::$created[$num]->tabs;


	echo($before_widget . "\n");

	print_r($args);
	print_r("num=$num");
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
		echo("<ul id=\"pop-$index\">\n");
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

		parent::__construct($name,$id,'BestOf_render','widget_bestof',$params);
	}

	public static function render($args,$id) {
		BestOf_render($args,$id);
	}
}

?>