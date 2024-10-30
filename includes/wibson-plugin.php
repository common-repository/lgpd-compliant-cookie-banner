<?php

class Wibson_Plugin {

	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->version = constant('WIBSON_PLUGIN_VERSION');
		$this->plugin_name = 'wibson-plugin';
		$this->wibson_load_textdomain();
		$this->load_dependencies();
	}
	function wibson_load_textdomain() {
		load_plugin_textdomain( 'wibson-plugin', false, dirname(plugin_basename( __FILE__ )).'/languages' ); 
	}

	public static function activate() {
		$defaults = array(
            'plugin_ver' => constant('WIBSON_PLUGIN_VERSION'),
			'siteId_key' => ''
        );

		if (get_option(constant('WIBSON_PLUGIN_OPTION_NAME'), false) === false) {
            update_option(constant('WIBSON_PLUGIN_OPTION_NAME'), $defaults);
        }

	}

	public static function deactivate() {
		if(defined('WIBSON_PLUGIN_OPTION_NAME')){
			delete_option(constant('WIBSON_PLUGIN_OPTION_NAME'));
		}
	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wibson-plugin-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/wibson-plugin-public.php';
	}

	private function define_admin_hooks() {
		$plugin_admin = new Wibson_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	private function define_public_hooks() {
		add_action( 'wp_enqueue_scripts', array($this, 'add_wibson_script_in_head') );
	}



	public function add_wibson_script_in_head() {
		$options = get_option('wibson_options');
        $siteId = esc_attr($options['siteId_key']);
		$enable_gcm = isset($options['enable_gcm']) ? $options['enable_gcm'] : 0;
		$disable_url_passthrough = isset($options['disable_url_passthrough']) ? $options['disable_url_passthrough'] : 0;		
		
		if ($enable_gcm) {
            add_action('wp_head', function() use ($siteId, $disable_url_passthrough) {
                ?>
                <script>
                (function(siteId, win, doc) {
                    var cookieName = 'illow-consent-' + siteId;
                    var cookies = doc.cookie.split(';');
                    var cookie = cookies.map((c) => c.split('=')).find(([name]) => name.trim() === cookieName);
                    var consentStr = cookie ? cookie.slice(1).join('=').trim() : undefined;

                    var consent = {};
                    consentStr?.split('|').map((v) => v.split('=')).forEach(([k, v]) => {
                        consent[k] = v === 'true';
                    });
					// Function to get boolean value or undefined
					function getBooleanOrUndefined(v) {
							if (typeof v === 'undefined') return undefined;
							return v === 'true';
					}
					function transformToGoogleSettings(str) {
						if (!str) return undefined;
						const p = ['optedIn', 'preferences', 'marketing', 'statistics'];
						const c = {};
						str.split('|').map((v) => v.split('=')).filter((v) => p.includes(v[0])).forEach((v) => {
							c[v[0]] = getBooleanOrUndefined(v[1]);
						});
						return {
							state: {
								ad_storage: c.marketing || c.optedIn ? 'granted' : 'denied',
								analytics_storage: c.preferences || c.optedIn ? 'granted' : 'denied',
								functionality_storage: c.preferences || c.optedIn ? 'granted' : 'denied',
								personalization_storage: c.preferences || c.optedIn ? 'granted' : 'denied',
								security_storage: c.preferences || c.optedIn ? 'granted' : 'denied',
								ad_user_data: c.marketing || c.optedIn ? 'granted' : 'denied',
								ad_personalization: c.marketing || c.optedIn ? 'granted' : 'denied',
								wait_for_update: 500,
							},
							others: {
								ads_data_redaction: !c.marketing && !c.optedIn,
								url_passthrough: (!c.marketing || !c.preferences) && !c.optedIn,
							},
						};
					}

					// Function to set default consent state
					function setDefaultConsentState(settings) {
						window.gtag('consent', 'default', settings);
					}

					// Function to set gtag configuration
					function gtagSet(settings) {
						window.gtag('set', settings);
					}

					// Initialize the data layer for Google Tag Manager
					window.dataLayer = window.dataLayer || [];
					function gtag() {
						window.dataLayer.push(arguments);
					}
					window.gtag = window.gtag || gtag;

					const regions = [
						{ region: ['US-VA', 'US-CO', 'US-IN'], value: 'denied' },
						{ region: ['US'], value: 'granted' },
						{ value: 'denied' },
					];

					const googleSettings = transformToGoogleSettings(consentStr);

					if (googleSettings) {
						setDefaultConsentState(googleSettings.state);
					} else {
						regions.forEach((state) => {
							setDefaultConsentState({
								ad_storage: state.value,
								analytics_storage: state.value,
								functionality_storage: state.value,
								personalization_storage: state.value,
								security_storage: state.value,
								ad_user_data: state.value,
								ad_personalization: state.value,
								region: state.region,
								wait_for_update: 500,
							});
						});
					}
                    
					const urlPassthrough = !<?php echo json_encode($disable_url_passthrough); ?> && (!googleSettings || googleSettings.others.url_passthrough);

					gtagSet({
						ads_data_redaction: !googleSettings || googleSettings.others.ads_data_redaction,
						url_passthrough: urlPassthrough,
						'developer_id.dYTYxZj': true,
					});

                })("<?php echo $siteId; ?>", window, document);
                </script>
                <?php
            });
		}


		if (!current_user_can('administrator')) {
			$plugin_public = new Wibson_Plugin_Public($this->get_plugin_name(), $this->get_version());
			$src_wibson_script = $plugin_public->get_wibson_script();
			wp_enqueue_script('wibson_plugin_script', $src_wibson_script, array(), $this->get_version());
		}
	}

	
	public function run() {
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_version() {
		return $this->version;
	}

}
