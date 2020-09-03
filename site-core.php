<?php
/*
Plugin Name: Site Core
Description: Site Core
Version: 0.0.1
Text Domain: Site Core
*/

// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if

// Let's Initialize Everything
if ( file_exists( plugin_dir_path( __FILE__ ) . 'core-init.php' ) ) {
  require_once( plugin_dir_path( __FILE__ ) . 'core-init.php' );
}
