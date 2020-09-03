<?php
include '/var/www/html/wp-load.php';


$loader = new Twig_Loader_Filesystem(dirname(__FILE__).'/templates');
$twig = new Twig_Environment($loader, array(
    'cache' => false,
));



if($_POST["submit"]){


$my_post = array(
  'post_title'    => $_POST['title'],
  'post_content'  => $_POST['ad'],
  'post_status'   => 'publish',
  'post_author'   => 1,
//  'post_category' => array('alt','food'),
  'post_type' => 'post',
  'tags_input'    => $_POST['keywords'],
);


// Insert the post into the database
$result=wp_insert_post( $my_post );
add_metadata( 'post', $result, "download_url", $_POST['url'], false );
add_metadata( 'post', $result, "video_url", $_POST['url'], false );

add_metadata( 'post', $result, "trailer_url", $_POST['turl'], false );

add_metadata( 'post', $result, "fetch_info", 1, false );

if(is_int($result)){
  //$link="<a target='_blank' href=".get_permalink( $result).">".$_POST['title']."</a>";
  $link="<a target='_blank' href=".get_edit_post_link( $result).">".$_POST['title']."</a>";
  $atts['link']=$link;
}

}

echo $twig->render('main.twig', $atts);


?>
