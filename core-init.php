<?php
/*
*
*	***** Site Core *****
*
*	This file initializes all Site Core components
*
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
// Define Our Constants
define('SITE_CORE_INC',dirname( __FILE__ ).'/assets/inc/');
define('SITE_CORE_IMG',plugins_url( 'assets/img/', __FILE__ ));
define('SITE_CORE_CSS',plugins_url( 'assets/css/', __FILE__ ));
define('SITE_CORE_JS',plugins_url( 'assets/js/', __FILE__ ));
define('SITE_CORE_ADMIN_TEMPLATES',dirname( __FILE__ ).'/admin-templates');
define('SITE_CORE_FRONTEND_TEMPLATES',dirname( __FILE__ ).'/frontend-templates');
define('SITE_CORE_CACHE',dirname( __FILE__ ).'/cache');
define('SITE_CORE_PLUGINS',dirname( __FILE__ ).'/plugins');

require_once dirname(__FILE__).'/vendor/autoload.php';



/*
*
*  Register CSS
*
*/
function SITE_CORE_register_core_css(){
	wp_enqueue_style('site-core', SITE_CORE_CSS . 'site-core.css',null,time('s'),'all');
};
	add_action( 'wp_enqueue_scripts', 'SITE_CORE_register_core_css' );
/*
*
*  Register JS/Jquery Ready
*
*/
function SITE_CORE_register_core_js(){
// Register Core Plugin JS
	wp_enqueue_script('site-core', SITE_CORE_JS . 'site-core.js','jquery',time(),true);
};
	add_action( 'wp_enqueue_scripts', 'SITE_CORE_register_core_js' );


	function wpse149688(){
			 add_menu_page('SiteCore Dashboard', 'SiteCore', 'manage_options', 'sitecore-menu', 'SITE_CORE_plugins_page_call');
			 add_submenu_page('sitecore-menu', 'Plugins Settings', 'Plugins', 'manage_options', 'sitecore-menu' ,'SITE_CORE_plugins_page_call' );
			 add_submenu_page('sitecore-menu', 'Settings Settings', 'Settings', 'manage_options', 'sitecore-menu3' ,'SITE_CORE_settings_page_call');
	}

	function SITE_CORE_main_page_call(){
		//Empty Main
	}


	function SITE_CORE_plugins_page_call(){

		$loader = new Twig_Loader_Filesystem(SITE_CORE_ADMIN_TEMPLATES);
		$twig = new Twig_Environment($loader, array(
		    'cache' => false,
		));


	 $result = array();
	 $plugins= array();

   $cdir = scandir(SITE_CORE_PLUGINS);
   foreach ($cdir as $key => $value){
      if (!in_array($value,array(".",".."))){
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
         }
         else{
					 $plugin = new stdClass;
					 $plugin->dir_name=$value;
					 $plugin->manifest=json_decode(file_get_contents(SITE_CORE_PLUGINS."/".$value."/plugin.json"));


					 array_push($plugins,$plugin);


           add_shortcode($plugin->dir_name, function(){
              include SITE_CORE_PLUGINS.'/'.$plugin->dir_name.'/loader.php';
           });



         }
      }
   }


		echo $twig->render('plugins-menu.twig', array('plugins' => $plugins));

	}

	function SITE_CORE_settings_page_call(){
		$loader = new Twig_Loader_Array(array(
		    'index' => 'You are on the {{ page_name }}!',
		));
		$twig = new Twig_Environment($loader);

		echo $twig->render('index', array('page_name' => 'Settings'));
	}


	function SITE_CORE_banking_page_call(){
		$loader = new Twig_Loader_Array(array(
				'index' => 'You are on the {{ page_name }}!',
		));
		$twig = new Twig_Environment($loader);

		echo $twig->render('index', array('page_name' => 'Banking'));
	}





	function SITE_CORE_ADMIN_lockdown_init(){
	    // Remove unnecessary menus
	    $menus_to_stay = array(
					'sitecore-menu'
	    );
	    foreach ($GLOBALS['menu'] as $key => $value) {
	        if (!in_array($value[2], $menus_to_stay)) remove_menu_page($value[2]);
	    }
	}



	function SITE_CORE_add_css(){

		$loader = new Twig_Loader_Array(array(
				'wpadminbar-disable' => '<style type="text/css"> #wpadminbar { display: {{display_type}}; }  </style>',
				'wpcontent-move' => '<style type="text/css"> #wpcontent { margin-top:-{{top_margin}}px; }  </style>',
				'wpbody-content' => '<style type="text/css"> #wpbody-content { margin-top:{{top_margin}}px; }  </style>',

		));
		$twig = new Twig_Environment($loader);

		echo $twig->render('wpadminbar-disable', array('display_type' => 'none'));
		echo $twig->render('wpcontent-move', array('top_margin' => '45'));
		echo $twig->render('wpbody-content', array('top_margin' => '20'));

	}

  //add_action('admin_init', 'SITE_CORE_ADMIN_lockdown_init');
	add_action('admin_menu', 'wpse149688');
	add_action('admin_head','SITE_CORE_add_css');




	function remove_footer_admin () {
	    echo '<span id="footer-thankyou">Developed by <a href="admin.php?page=sitecore-menu3" target="_blank">SiteCore</a></span>';
	}
	add_filter('admin_footer_text', 'remove_footer_admin');

	function wpbeginner_remove_version() {
		return '';
	}
	add_filter('the_generator', 'wpbeginner_remove_version');



///AutoLoad
   $cdir = scandir(SITE_CORE_PLUGINS);
   foreach ($cdir as $key => $value){
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){

         }else{

         $new_function_name=str_replace('-','_',$value);

         $SITE_CORE_PLUGINS=SITE_CORE_PLUGINS;
         $new_loader_include="include \"$SITE_CORE_PLUGINS".'/'.$value.'/loader.php"';


         $new_function="function $new_function_name(\$atts = [], \$content = null, \$tag = ''){ $new_loader_include ;}";
         $aaa="add_shortcode('$new_function_name', '$new_function_name');";


         eval($new_function);
         eval($aaa);

         }
   }


/*
*
*  Includes
*
*/



add_filter( 'show_admin_bar', '__return_false', 999 );


include dirname(__FILE__)."/site-display.php";
