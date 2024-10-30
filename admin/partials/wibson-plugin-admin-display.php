<div style="background-color: rgba(249, 250, 251, 1); padding: 15px 10px 15px 20px; margin-top: 10px">

    <h2 style="color: #8042e9; font-size: 30px;"> <?php esc_html_e( 'illow - GDPR, LGPD and CCPA/CPRA Cookie Consent', 'wibson-plugin' ); ?>
        <small style="color: rgba(17, 24, 39, 0.7); font-size: 20px">(v<?php echo esc_attr(constant('WIBSON_PLUGIN_VERSION')); ?>)</small>
    </h2>
    <br>

    <?php if( esc_attr(isset($_GET['settings-updated'])) ) { ?>
    <div id=”message” class=”updated”>
        <p><strong><?php esc_html_e( 'Settings saved.', 'wibson-plugin' ); ?></strong></p>
    </div>
    <?php } ?>

    <!-- SiteId not defined input -->
    <?php if( esc_attr($options['siteId_key'] == '')) { ?>
    <div class="wrap" style="display:flex;flex-direction:row;">
        <div style="flex: auto; display:flex;flex-direction:column;">
            <div style=" display:flex;flex-direction:row;justify-content:space-between">
                <div style="width:35% ">

                    <p class="description" style="display: inline; font-size: 18px; color:#000000"> <label style="text-decoration: underline #7C3AED; color: #7C3AED;cursor:default;"><?php esc_html_e( 'STEP 1:', 'wibson-plugin' ); ?></label>  <?php esc_html_e( 'Create your
                        account and select your plan to get integration code using this', 'wibson-plugin' ); ?>

                    <?php esc_attr($hostname_backend = constant('WIBSON_PLUGIN_HOSTNAME_BACKEND')); esc_attr($email_WP = urlencode(wp_get_current_user()->user_email)); esc_attr($siteurl_WP = urlencode(get_site_url()));  echo "<a target='_blank' href='$hostname_backend/public/integrations/authorize/wordpress?email=$email_WP&companyUrl=$siteurl_WP'>link</a>" ?>
                        .
                    </p>
                   <div style="margin-top:30px">
                        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/sign-up_illow.png'; ?>" alt="E.g.-login"
                            style="width: 100%; height:auto;">
                    </div>
                </div>
                <div style="width:55%">

                    <p class="description" style="display: inline; font-size: 18px; color:#000000"><label style="text-decoration: underline #7C3AED; color: #7C3AED;cursor:default;"><?php esc_html_e( 'STEP 2:', 'wibson-plugin' ); ?></label> <?php esc_html_e( 'Wait for it
                        to generate the integration code and copy it.', 'wibson-plugin' ); ?> </p>

                    <div style="margin-top:30px">
                        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/copy_code_illow.png'; ?>" alt="copy-code"
                            style="width: 100%; height:auto;">
                    </div>
                </div>
            </div>
            <br>
            <div style="display:flex; margin-top: 50px">
                <form method="post" id="wibson_form" novalidate>
                    <input type="hidden" name="wibson_form" value="true" />
                    <div>
                        <p class="description" style="display: inline; font-size: 18px; color:#000000"><label style="text-decoration: underline #7C3AED; color: #7C3AED;cursor:default;"><?php esc_html_e( 'STEP 3:', 'wibson-plugin' ); ?></label> <?php esc_html_e( 'Paste the integration code and press save button to final activation of plugin.', 'wibson-plugin' ); ?>
                        <section id="c-key">
                            <table class="form-table">
                                <tr>
                                    <td>
                                        <textarea type="text" placeholder=<?php esc_html_e( 'Paste integration code here.', 'wibson-plugin' ); ?> 
                                            name="wibson_options[wibson_script]" cols="90" rows="2" maxlength="140"
                                            style="resize: none;"></textarea>
                                        <br>
                                    </td>
                                </tr>
                            </table>
                        </section>
                    </div>
                    <section >
                        <p class="description" style="display: inline; font-size: 18px; color:#000000"><label style="text-decoration: underline #7C3AED; color: #7C3AED;cursor:default;"><?php esc_html_e( 'Google Consent Mode:', 'wibson-plugin' ); ?></label> 
                        <table style="display: flex; flex-direction: column; align-items: flex-start">
                            <tr style="display: flex; justify-items: flex-start" >
                                <th style="margin-right: 10px;" scope="row"><?php esc_html_e('Enable Google Consent Mode v2:', 'wibson-plugin'); ?></th>
                                <td >
                                    <input type="checkbox" name="wibson_options[enable_gcm]" value="1" <?php checked(1, $options['enable_gcm'], true); ?> />
                                    <label for="enable_gcm"><?php esc_html_e('Check to enable Google Consent Mode v2', 'wibson-plugin'); ?></label>
                                </td>
                            </tr>
                            <tr style="display: flex; justify-items: flex-start">
                                <th style="margin-right: 10px;" scope="row"><?php esc_html_e('Disable Google Consent Mode v2 url_passthrough:', 'wibson-plugin'); ?></th>
                                <td>
                                    <input type="checkbox" name="wibson_options[disable_url_passthrough]" value="1" <?php checked(1, $options['disable_url_passthrough'], true); ?> />
                                    <label for="disable_url_passthrough"><?php esc_html_e('Check to disable Google Consent Mode v2 url_passthrough', 'wibson-plugin'); ?></label>
                                </td>
                            </tr>
                        </table>
                    </section>
                    <?php submit_button(__('Save')); ?>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- SiteId defined input -->
    <?php if( esc_attr($options['siteId_key'] !== '')) { ?>
        <div class="wrap" style="display:flex;flex-direction:column-reverse;">
        <div style="flex:1">
            <form method="post" id="wibson_form" novalidate>
                <input type="hidden" name="wibson_form" value="true" />
                <div>
                    <section id="c-key">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Site ID :', 'wibson-plugin' ); ?></th>
                                <td>
                                    <input size="40" disabled type="text" placeholder=""
                                        name="wibson_options[siteId_key]"
                                        value="<?php echo esc_attr($options['siteId_key']); ?>"
                                        id="wibson-siteid-input" />
                            </tr>
                        </table>
                    </section>
                </div>
                <?php submit_button(__('Reset config','wibson-plugin'))?>
            </form>
            <hr>
        </div>
        </div>
    <?php } ?>

</div>