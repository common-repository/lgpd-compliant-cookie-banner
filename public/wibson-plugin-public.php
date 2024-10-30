<?php

class Wibson_Plugin_Public {

	private $plugin_name;
	private $version;
	private $siteId_key;
	private $src;
    private $enable_gcm;
    private $disable_url_passthrough;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->fill_options();

	}

	public function get_wibson_script() {
        $siteId = $this->siteId_key;
        $url_backend = constant('WIBSON_PLUGIN_HOSTNAME_FRONTEND');
        if($siteId === '')
            return '';
        else
            return "$url_backend/banner.js?siteId=$siteId";
	}

	public function get_siteId_key() {
		return $this->siteId_key;
	}


	private function fill_options() {
		$options = $this->get_safe_options();
		$this->siteId_key = esc_attr($options['siteId_key']);
        $this->enable_gcm = isset($options['enable_gcm']) ? (int) $options['enable_gcm'] : 0;
        $this->disable_url_passthrough = isset($options['disable_url_passthrough']) ? (int) $options['disable_url_passthrough'] : 0;
	}

    private function get_safe_options() {
        $db_options = get_option(constant('WIBSON_PLUGIN_OPTION_NAME'));
		
        if (!is_array($db_options)) {
            $db_options = array();
        }

        $db_options = empty($db_options) ? $this->get_default_options() : array_merge($this->get_default_options(), $db_options);
        return $db_options;
		
    }

    private function get_default_options() {
        $defaults = array(
            'plugin_ver' => $this->version,
            'siteId_key' => '',
            'enable_gcm' => 0,
            'disable_url_passthrough' => 0
        );
        return $defaults;
    }
	
    public function load_options_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You don\'t have sufficient permissions to access this page.', $this->plugin_name));
        }

        $this->load_view('settings-page.php');

    }


}
