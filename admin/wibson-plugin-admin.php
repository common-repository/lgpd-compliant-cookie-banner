<?php


class Wibson_Plugin_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('admin_menu', array($this, 'wibson_menu_pages'));
        add_action('admin_init', array($this, 'register_wibson_settings'));
        add_action('init', array($this, 'wibson_load_textdomain'));

        $this->update_config_db();
    }

    public function register_wibson_settings() {
        register_setting('wibson_options_group', 'wibson_options');
    }

    public function show_admin_success() {
        $this->load_view('success-admin-notice.php', array());
    }

    private function update_config_db() {
        if (isset($_POST['wibson_form']) && $_POST['wibson_form'] !== '') {

            if (preg_match('/[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}/i', $_POST['wibson_options']['wibson_script'], $coincidences)) {
                $site_id = sanitize_text_field($coincidences[0]);
            } else {
                $site_id = '';
            }

            $enable_gcm = isset($_POST['wibson_options']['enable_gcm']) ? 1 : 0;
            $disable_url_passthrough = isset($_POST['wibson_options']['disable_url_passthrough']) ? 1 : 0;

            $configs = array(
                'plugin_ver' => constant('WIBSON_PLUGIN_VERSION'),
                'siteId_key' => $site_id,
                'enable_gcm' => $enable_gcm,
                'disable_url_passthrough' => $disable_url_passthrough
            );

            update_option(constant('WIBSON_PLUGIN_OPTION_NAME'), $configs);
            add_action('admin_notices', array($this, 'show_admin_success'));
        }
    }

    public function wibson_menu_pages() {
        add_menu_page('illow | GDPR, LGPD and CCPA/CPRA', 'illow Plugin Config', 'manage_options', 'wibson-menu', array($this, 'load_options_page'));
        add_submenu_page('illow', 'Plugin Config', 'Configurações', 'manage_options', 'wibson-menu', array($this, 'load_options_page'));
    }

    public function wibson_load_textdomain() {
        load_plugin_textdomain('wibson-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function load_options_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You don\'t have sufficient permissions to access this page.', 'wibson-menu'));
        }

        $options = get_option(constant('WIBSON_PLUGIN_OPTION_NAME'));
        $this->load_view('wibson-plugin-admin-display.php', $options);
    }

    private function load_view($file, $options = array()) {
        $file_path = plugin_dir_path(__FILE__) . 'partials/' . $file;
        if (is_readable($file_path)) {
            require $file_path;
        } else {
            throw new \Exception('Unable to load template file - ' . esc_html($file_path));
        }
    }
}
