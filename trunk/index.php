<?php
// Template Name: Main default template

// $Id$

$context=Context::getContext();

get_header();


$context->panels['header-1']->render();
$context->panels['header-2']->render();

$context->panels['main-multipost']->render();

$context->panels['footer-1']->render();
$context->panels['footer-2']->render();


get_footer();


?>