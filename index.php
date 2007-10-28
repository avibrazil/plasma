<?php
// Template Name: Main default template

// $Id$


get_header();


$context=Context::getContext();

($context->panels['header-1'])->render();
$context->panels['header-2']->render();

$context->panels['main-multipost']->render();

$context->panels['footer-1']->render();
$context->panels['footer-2']->render();


get_footer();


?>