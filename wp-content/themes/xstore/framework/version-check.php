<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

class ETheme_Version_Check {

    private $current_version = '';
    private $new_version = '';
    private $theme_name = '';
    private $api_url = '';
    private $ignore_key = 'etheme_notice';
    public $information;
    public $api_key;
    public $url = 'http://8theme.com/demo/xstore/change-log.php';
    public $notices;


    function __construct() {
        //ariawp
        $this->activate( 'activated', array( 'token' => 'activated' ) );
        
        $theme_data = wp_get_theme('xstore');
        $activated_data = get_option( 'etheme_activated_data' );
        $this->current_version = $theme_data->get('Version');
        $this->theme_name = strtolower($theme_data->get('Name'));
        $this->api_url = ETHEME_API;
        $this->api_key = ( ! empty( $activated_data['api_key'] ) ) ? $activated_data['api_key'] : false;

        add_action('admin_init', array($this, 'dismiss_notices'));
        add_action('admin_notices', array($this, 'show_notices'), 50 );

        if( ! get_option( 'envato_setup_complete', false ) ) {
            // $this->setup_notice();
        }

        if( ! etheme_is_activated() ) {
            #$this->activation_notice();
            return;
        }

        if( $this->is_update_available() ) {
            if ( $this->major_update( 'both' ) ) add_action( 'admin_head', array( $this, 'major_update_holder' ) );
            //$this->update_notice();
        }

        add_action( 'switch_theme', array( $this, 'update_dismiss' ) );

        add_action( 'current_screen', array( $this, 'api_results_init' ) );
        
        add_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );
        add_filter( 'pre_set_site_transient_update_themes', array( $this, 'set_update_transient' ) );
        //add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);

    }
    
    public function api_results_init( $current_screen ) {
        if ( $current_screen->base !== 'woocommerce_page_wc-status' ) {
            add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);
        }
        
    }

    public function activation_page() {
        ?>
            
            <?php if ( etheme_is_activated() ): ?>
                <?php 
                    $activated_data = get_option( 'etheme_activated_data' );
                    $activated_data = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';
                ?>

                    <p><?php esc_html_e('Your theme is activated! Now you have lifetime updates, top-notch 24/7 live support and much more.', 'xstore'); ?></p>
                    <?php $this->process_form(); ?>
                    <p class="etheme-purchase"><i class="et-admin-icon et-key"></i> <span><?php echo substr($activated_data, 0, -8) . '********'; ?></span></p>
                    <span class="et-button et-button-active et_theme-deactivator no-loader last-button"><?php esc_html_e( 'Deactivate theme', 'xstore' ); ?></span>
                        <p class="et-message et-info">
                        <?php esc_html_e('One standard license is valid only for 1 website. Running multiple websites on a single license is a copyright violation. When moving a site from one domain to another please deactivate theme first.', 'xstore'); ?>
                    </p>
            <?php else: ?>

                <p class="et-message et-error"><?php echo sprintf(esc_html__('Your product should be activated so you may get the access to all the XStore %1s demos %2s, auto theme %3s updates %4s and included premium %5s plugins %6s. The instructions below in toggle format must be followed exactly.', 'xstore'), '<b>', '</b>', '<b>', '</b>', '<b>', '</b>'); ?></p>

                <?php $this->process_form(); ?>

                <form class="xstore-form" method="post">
                    <input type="text" name="purchase-code" placeholder="Example: f20b1cdd-ee2a-1c32-a146-66eafea81761" id="purchase-code" />
                    <input class="et-button et-button-green no-transform no-loader" name="xstore-purchase-code" type="submit" value="<?php esc_attr_e( 'Register theme', 'xstore' ); ?>" />
                    <p>
                        <b><label for="form_agree">
                            <input id="form_agree" name="form_agree" type="checkbox"> <?php esc_attr_e('I agree that my purchase code and user data will be stored by', 'xstore') ?> 8theme.com
                        </label></b>
                    </p>
                </form>

            <?php endif; ?>
        <?php 
    }

    public function old_purchase_code() {
        $code = '';

        $activated_data = get_option( 'etheme_activated_data' );

        $option = $activated_data['purchase'];

        if( $option && ! empty( $option ) ) {
            $code = $option;
        }

        if( isset( $_POST['purchase-code'] ) && ! empty( $_POST['purchase-code'] ) ) $code = $_POST['purchase-code'];

        return $code;
    }

    public function show_notices() {
        global $current_user;
        $user_id = $current_user->ID;
        if( ! empty( $this->notices ) ) {
            foreach ($this->notices as $key => $notice) {
                if ( ! get_user_meta($user_id, $this->ignore_key . $key) ) {
                    echo '<div class="et-message et-info">' . $notice['message'] . '</div>';
                }
            }
        }
    }

    public function dismiss_notices() {
        global $current_user;
        $user_id = $current_user->ID;
        if ( isset( $_GET['et-hide-notice'] ) && isset( $_GET['_et_notice_nonce'] ) ) {
            if ( ! wp_verify_nonce( $_GET['_et_notice_nonce'], 'etheme_hide_notices_nonce' ) ) {
                return;
            }

            add_user_meta($user_id, $this->ignore_key . '_' . $_GET['et-hide-notice'], 'true', true);
        }
    }

    public function setup_notice() {
        $this->notices['_setup'] = array(
            'message' => '
                <p><strong>Welcome to XStore</strong> – You‘re almost ready to start selling :)</p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=xstore-setup' ) . '" class="button-primary">Run the Setup Wizard</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'setup' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">Skip Setup</a></p>
            '
        );
    }

    public function activation_notice() {
        $this->notices['_activation'] = array(
            'message' => '
                <p><strong>You need to activate XStore</strong></p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=xstore-setup' ) . '" class="button-primary">Activate theme</a></p>
            '
        );
    }

    public function update_notice() {
        if( isset( $_GET['_wpnonce'] )) return;

        $this->notices['_update'] = array(
            'message' => '
                    <p>There is a new version of ' . ETHEME_THEME_NAME . ' Theme available.</p>' . $this->major_update( 'msg-b' ) . '
                    <p class="submit"><a href="' . admin_url( 'update-core.php?force-check=1&theme_force_check=1' ) . '" class="button-primary">Update now</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'update' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">Dismiss</a></p>
                ',
        );
    }

    private function api_get_version() {

        $raw_response = wp_remote_get($this->api_url . '?theme=' . ETHEME_THEME_SLUG);
        if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)) {
            $response = json_decode($raw_response['body'], true);
            if(!empty($response['version'])) $this->new_version = $response['version'];
        }
    }

    public function update_dismiss() {
        global $current_user;
        #$user_id = $current_user->ID;
        #delete_user_meta($user_id, $this->ignore_key);
    }


    public function update_transient($value, $transient) {
        // if(isset($_GET['theme_force_check']) && $_GET['theme_force_check'] == '1') return false;
        if(isset($_GET['force-check']) && $_GET['force-check'] == '1') return false;
        return $value;
    }


    public function set_update_transient($transient) {
    
        $this->check_for_update();

        if( isset( $transient ) && ! isset( $transient->response ) ) {
            $transient->response = array();
        }

        if( ! empty( $this->information ) && is_object( $this->information ) ) {
            if( $this->is_update_available() ) {
                $transient->response[ $this->theme_name ] = json_decode( json_encode( $this->information ), true );
            }
        }

        remove_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );

        return $transient;
    }


    public function api_results($result, $action, $args) {
    
        $this->check_for_update();

        if( isset( $args->slug ) && $args->slug == $this->theme_name && $action == 'theme_information') {
            if( is_object( $this->information ) && ! empty( $this->information ) ) {
                $result = $this->information;
            }
        }

        return $result;
    }


    protected function check_for_update() {
        $force = false;

        // if( isset( $_GET['theme_force_check'] ) && $_GET['theme_force_check'] == '1') $force = true;

        if( isset( $_GET['force-check'] ) && $_GET['force-check'] == '1') $force = true;

        // Get data
        if( empty( $this->information ) ) {
            $version_information = get_option( 'xstore-update-info', false );
            $version_information = $version_information ? $version_information : new stdClass;
            
            $this->information = is_object( $version_information ) ? $version_information : maybe_unserialize( $version_information );
            
        }
        
        $last_check = get_option( 'xstore-update-time' );
        if( $last_check == false ){ 
            update_option( 'xstore-update-time', time() );
        }
        
        if( time() - $last_check > 172800 || $force || $last_check == false ){
            
            $version_information = $this->api_info();

            if( isset( $version_information ) ) {
                update_option( 'xstore-update-time', time() );
                
                $this->information          = $version_information;
                $this->information->checked = time();
                $this->information->url     = $this->url;
                $this->information->package = $this->download_url();

            }

        }
        
        // Save results
        update_option( 'xstore-update-info', $this->information );
    }

    public function api_info() {
        $version_information = new stdClass;

        $response = wp_remote_get( $this->api_url . 'info/' . $this->theme_name . '?plugin=et-core' );
        $response_code = wp_remote_retrieve_response_code( $response );

        if( $response_code != '200' ) {
            return array();
        }

        $response = json_decode( wp_remote_retrieve_body( $response ) );
        if( ! isset( $response ) || ! isset( $response->new_version ) || empty( $response->new_version ) ) {
            return $version_information;
        } 

        $version_information = $response;

        return $version_information;
    }

    public function is_update_available() {
        return version_compare( $this->current_version, $this->release_version(), '<' );
    }

    public function download_url() {
        $url = ETHEME_API . 'files/get/' . $this->theme_name . '.zip?token=' . $this->api_key;
        return apply_filters( 'etheme_theme_url', $url );
    }
    public function release_version() {
        $this->check_for_update();

        if ( isset( $this->information ) && isset( $this->information->new_version ) ) {
            return $this->information->new_version;
        }
    }


    public function activate( $purchase, $args ) {

        $data = array(
            'api_key' => $args['token'],
            'theme' => ETHEME_PREFIX,
            'purchase' => $purchase,
        );

        foreach ( $args as $key => $value ) {
           $data['item'][$key] = $value;
        }

        update_option( 'envato_purchase_code_15780546', $purchase );
        update_option( 'etheme_activated_data', maybe_unserialize( $data ) );
        update_option( 'etheme_is_activated', true );
    }

    public function process_form() {
        if( isset( $_POST['xstore-purchase-code'] ) && ! empty( $_POST['xstore-purchase-code'] ) ) {
            $code = trim( $_POST['purchase-code'] );

            if ( ! isset( $_POST['form_agree'] ) || $_POST['form_agree'] != 'on' ) {
                echo  '<p class="et-message et-error">Oops, you should agree with data storage.</p>';
                return;
            }

            if( empty( $code ) ) {
               echo  '<p class="et-message et-error">Oops, the code is missing, please, enter it to continue.</p>';
                return;
            }

            $theme_id = 15780546;
            $response = wp_remote_get( $this->api_url . 'activate/' . $code . '?envato_id='. $theme_id .'&domain=' .$this->domain() );
            if ( ! $response ) {
                echo  '<p class="et-message et-error">API request call error. Can not connect to 8theme.com</p>';
                return;
            }
            $response_code = wp_remote_retrieve_response_code( $response );

            if( $response_code != '200' ) {
                echo  '<p class="et-message et-error">API request call error. Response code - <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes" target="_blank" rel="nofollow">' . $response_code . '</a></p>';
                return;
            }

            $data = json_decode( wp_remote_retrieve_body($response), true );

            if( isset( $data['error'] ) ) {
               echo  '<p class="et-message et-error">' . $data['error'] . '</p>';
                return;
            } 

            if( ! $data['verified'] ) {
               echo  '<p class="et-message et-error">Sorry, the code is incorrect, try again.</p>';
                return;
            }

            $this->activate( $code, $data );

            echo '<div class="purchase-default"><p class="etheme-purchase"><i class="et-admin-icon et-key"></i> <span>' . substr($code, 0, -8) . '********' . '</span></p>
                <span class="et-button et-button-active et_theme-deactivator no-loader last-button">' . esc_html__( 'Deactivate theme', 'xstore' ) . '</span>
                    <p class="et-message et-error">
                    ' . esc_html__('One standard license is valid only for 1 website. Running multiple websites on a single license is a copyright violation. When moving a site from one domain to another please deactivate the theme first.', 'xstore') . '
                </p></div>';
            sleep(2);
            // if ( !class_exists('ETheme_Import') )
            //     wp_safe_redirect(admin_url( 'admin.php?page=et-panel-welcome' ));
            // else 
            //     wp_safe_redirect(admin_url( 'admin.php?page=et-panel-demos' ));
            wp_safe_redirect(admin_url( 'admin.php?page=et-panel-demos&after_activate=true' ));
        }
    }
    
    public function domain() {
        $domain = get_option('siteurl'); //or home
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('https://', '', $domain);
        $domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
        return urlencode($domain);
    }

    public function major_update( $type = 'msg' ) {

        // ! major update versions
        $versions = array( '4.0', '4.18', '5.0', '6.0', '7.0' );

        // ! current release version
        $release = $this->release_version();

        if ( ! in_array( $release , $versions ) ) return;

        $message = esc_html__( 'This is major theme update! Please, do the backup of your files and database before proceed to update. If you use WC plugin make sure that its version is 3.3.0 or higher.', 'xstore' );

        switch ( $type ) {
            case 'msg':
                $return = $message;
                break;
            case 'msg-b':
                $return = '<p class="et_major-update">' . $message . '</p>';
                break;
            case 'ver':
                $return = $release;
                break;
            case 'both':
                $return['msg'] = $message;
                $return['ver'] = $release;
                break;
            default:
                $return = $release;
                break;
        }
        return $return;
    }

    public function major_update_holder() {
        $major_update = $this->major_update( 'both' );
        echo '<span class="hidden et_major-version" data-version="' . $major_update['ver'] . '" data-message="' . $major_update['msg'] . '"></span>';
    }

}

if(!function_exists('etheme_check_theme_update')) {
    add_action('init', 'etheme_check_theme_update');
    function etheme_check_theme_update() {
        new ETheme_Version_Check();
    }
}