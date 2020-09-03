<?php

class PluginDisplayTemplate {

	private $source;
	private $dest;

	public function __construct() {
		$my_theme = wp_get_theme();
		$this->source = dirname(__FILE__) . '/frontend-templates/site_display_plugin/site_display_plugin.php';
		$this->dest = $my_theme->get_template_directory() . '/site_display_plugin.php';

		add_action( 'init', array( $this, 'plugindisplaytemplate_custom_sidebar_init') );

		register_deactivation_hook( __FILE__, array( $this, 'plugindisplaytemplate_custom_sidebar_deactivate' ) );
	}

	// Init plugin
	public function plugindisplaytemplate_custom_sidebar_init() {
		if( !file_exists( $this->dest ) ) {
			copy( $this->source, $this->dest );
		}
	}

	//Deactivate plugin
	public function plugindisplaytemplate_custom_sidebar_deactivate(){
		if( file_exists( $this->dest ) ) {
			unlink($this->dest);
		}
	}

}

//Initialize plugin
new PluginDisplayTemplate();
?>
