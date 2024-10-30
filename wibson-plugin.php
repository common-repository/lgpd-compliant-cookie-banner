<?php

/**
* Plugin Name: illow | GDPR, LGPD and CCPA/CPRA Cookie Consent
* Plugin URI: https://illow.io
* Description: Start your compliance path on your website, and in 5 minutes, make it a great example of how to handle privacy.
* Version: 0.2.0
* Author: illow
* Author URI: https://illow.io
* License: GPL2
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain: wibson-plugin
* Domain path: /languages
*/

if ( ! defined( 'WPINC' ) ) {
	// If this plugin file is called directly
	die("You do not have access to this page!");
}

setup_wibson_plugin_constants();
function setup_wibson_plugin_constants() {
	if(!defined('WIBSON_PLUGIN_VERSION')) {
		define( 'WIBSON_PLUGIN_VERSION', '0.2.0' );
	}
	if(!defined('WIBSON_PLUGIN_OPTION_NAME')){
		define( 'WIBSON_PLUGIN_OPTION_NAME', 'wibson_options');
	}
	if(!defined('WIBSON_PLUGIN_ENVIROMENT_NAME')){
		define('WIBSON_PLUGIN_HOSTNAME_BACKEND','https://api.platform.illow.io');
		define('WIBSON_PLUGIN_HOSTNAME_FRONTEND','https://platform.illow.io');
	} elseif(constant('WIBSON_PLUGIN_ENVIROMENT_NAME') === 'development') {
		define('WIBSON_PLUGIN_HOSTNAME_BACKEND','http://localhost:9000');
		define('WIBSON_PLUGIN_HOSTNAME_FRONTEND','http://localhost:1234');
	} else {
		define('WIBSON_PLUGIN_HOSTNAME_BACKEND','https://api.stg.platform.illow.io');
		define('WIBSON_PLUGIN_HOSTNAME_FRONTEND','https://stg.platform.illow.io');
	};
}

require plugin_dir_path( __FILE__ ) . 'includes/wibson-plugin.php';

// Activation
register_activation_hook( __FILE__, 'activate_wibson_plugin' );
function activate_wibson_plugin() {
	Wibson_Plugin::activate();
}

// Deactivation
register_deactivation_hook( __FILE__, 'deactivate_wibson_plugin' );
function deactivate_wibson_plugin() {
	Wibson_Plugin::deactivate();
}

// i18n
add_action( 'init', 'wibson_load_textdomain' );
function wibson_load_textdomain() {
    load_plugin_textdomain( 'wibson-plugin', false, dirname(plugin_basename( __FILE__ )).'/languages' ); 
}

function run_wibson_wbsn_plugin() {
	$plugin = new Wibson_Plugin();
	$plugin->run();
}
run_wibson_wbsn_plugin();