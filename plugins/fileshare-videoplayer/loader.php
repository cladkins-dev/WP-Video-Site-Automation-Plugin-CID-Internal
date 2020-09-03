<?php


$loader = new Twig_Loader_Filesystem(dirname(__FILE__).'/templates');
$twig = new Twig_Environment($loader, array(
    'cache' => false,
));


echo $twig->render('main.twig', $atts);


?>
