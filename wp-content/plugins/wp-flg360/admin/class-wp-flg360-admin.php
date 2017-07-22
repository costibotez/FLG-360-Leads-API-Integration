<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://costinbotez.co.uk
 * @since      1.0.0
 *
 * @package    Wp_Flg360
 * @subpackage Wp_Flg360/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Flg360
 * @subpackage Wp_Flg360/admin
 * @author     Costin Botez <costibotez94@gmail.com>
 */
class Wp_Flg360_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The hook suffix of Admin Setting page
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_screen_hook_suffix
	 */
	private $plugin_screen_hook_suffix;

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'wp_flg360_api';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Flg360_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Flg360_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-flg360-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Flg360_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Flg360_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-flg360-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'FLG360 Integration', 'wp-flg360' ),
			__( 'FLG360 Integration', 'wp-flg360' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/wp-flg360-admin-display.php';
	}

	/**
	 * Register setting section for plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
		// Add a General section
		add_settings_section(
			$this->option_name . '_general',
			__( 'General Settings', 'wp-flg360' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->option_name . '_key',
			__( 'API Key', 'wp-flg360' ),
			array( $this, $this->option_name . '_key_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_key' )
		);

		add_settings_field(
			$this->option_name . '_url',
			__( 'Request URL', 'wp-flg360' ),
			array( $this, $this->option_name . '_url_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_url' )
		);

		add_settings_field(
			$this->option_name . '_leadgroup',
			__( 'LeadGroup', 'wp-flg360' ),
			array( $this, $this->option_name . '_leadgroup_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_leadgroup' )
		);

		add_settings_field(
			$this->option_name . '_site',
			__( 'Site', 'wp-flg360' ),
			array( $this, $this->option_name . '_site_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_site' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_key' );
		register_setting( $this->plugin_name, $this->option_name . '_url' );
		register_setting( $this->plugin_name, $this->option_name . '_leadgroup' );
		register_setting( $this->plugin_name, $this->option_name . '_site' );
	}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function wp_flg360_api_general_cb() {

		echo '<p>' . __( 'Please change the settings accordingly.', 'wp-flg360' ) . '</p>';

	}

	/**
	 * Render the API Key input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function wp_flg360_api_key_cb() {

		$api_key = get_option( $this->option_name . '_key' );
		echo '<input type="text" name="' . $this->option_name . '_key' . '" id="' . $this->option_name . '_key' . '" value="' . $api_key . '"/>';

	}

	/**
	 * Render the Request URL input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function wp_flg360_api_url_cb() {

		$url = get_option( $this->option_name . '_url' );
		echo '<input type="text" name="' . $this->option_name . '_url' . '" id="' . $this->option_name . '_url' . '" value="' . $url . '"/>';

	}

	/**
	 * Render the Request URL input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function wp_flg360_api_leadgroup_cb() {

		$leadgroup = get_option( $this->option_name . '_leadgroup' );
		echo '<input type="text" name="' . $this->option_name . '_leadgroup' . '" id="' . $this->option_name . '_leadgroup' . '" value="' . $leadgroup . '"/>';

	}

	/**
	 * Render the Request URL input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function wp_flg360_api_site_cb() {

		$site = get_option( $this->option_name . '_site' );
		echo '<input type="text" name="' . $this->option_name . '_site' . '" id="' . $this->option_name . '_site' . '" value="' . $site . '"/>';

	}

	/**
	 * Adding extra fields to User Profile
	 *
	 * @since  1.0.0
	 */
	public function extra_user_profile_fields( $user ) {

		$user_meta = get_user_meta( $user->ID ); ?>

	    <h3><?php _e('Lead information', 'wp-flg360'); ?></h3>
	    <table class="form-table">
	    <?php //echo '<pre>'; print_r($user_meta); ?>
	    <?php foreach ( $user_meta as $key => $value ) : ?>
	    	<?php if ( strpos( $key, 'lead_' ) !== false && $key != 'lead_data' ) : ?>
	    		<tr>
			        <th>
			        	<label for="<?php echo $key; ?>"><?php echo ucfirst( substr( $key, 5 ) ); ?></label>
			        </th>
			        <td>
			            <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( $value[0] ); ?>" class="regular-text" /><br />
			        </td>
			    </tr>
	    	<?php endif; ?>
	    <?php endforeach; ?>
	    </table>

	<?php }

	/**
	 * Adding extra fields to User Profile
	 *
	 * @since  1.0.0
	 */
	public function save_extra_user_profile_fields( $user_id ) {

	    if ( !current_user_can( 'edit_user', $user_id ) ) {
	        return false;
	    }
	    $user_meta = get_user_meta( $user_id );
	    foreach ( $user_meta as $key => $value ) {
	    	if ( strpos( $key, 'lead_' ) !== false && $key != 'lead_data' ) {
	    		update_user_meta( $user_id, $key, $_POST[$key] );
	    	}
	    }

	    $this->update_flg360_user_profile( $user_id );
	    // echo '<pre>'; print_r( $this->update_flg360_user_profile( $user_id ) ); echo '</pre>'; exit;

	}

	public function update_flg360_user_profile( $user_id ) {

		$key 		= get_option( $this->option_name . '_key' );			// API Access key
        $url 		= get_option( $this->option_name . '_url' );			// API Request URL
        $leadgroup 	= get_option( $this->option_name . '_leadgroup' );		// API LeadGroup
        $site 		= get_option( $this->option_name . '_site' );			// API registered Site

        $lead = array();										// empty lead by default

        $lead['key'] 		= $key;
        $lead['id']			= get_user_meta( $user_id, 'lead_key' , true );
        $lead['source']		= get_user_meta( $user_id, 'lead_source', true );
        $lead['title'] 		= get_user_meta( $user_id, 'lead_title', true );
        $lead['firstname'] 	= get_user_meta( $user_id, 'lead_firstname', true );
        $lead['lastname'] 	= get_user_meta( $user_id, 'lead_surname', true );
        $lead['jobtitle'] 	= get_user_meta( $user_id, 'lead_occupation', true );
        $lead['phone1'] 	= get_user_meta( $user_id, 'lead_mobile', true );
        $lead['phone2'] 	= get_user_meta( $user_id, 'lead_mobile', true );
        $lead['email'] 		= get_user_meta( $user_id, 'lead_email', true );
        $lead['address'] 	= get_user_meta( $user_id, 'lead_housenumber', true );
        $lead['postcode'] 	= get_user_meta( $user_id, 'lead_postcode', true );
    	$lead['dobday'] 	= get_user_meta( $user_id, 'lead_dobday', true );
        $lead['dobmonth'] 	= get_user_meta( $user_id, 'lead_dobmonth', true );
        $lead['dobyear'] 	= get_user_meta( $user_id, 'lead_dobyear', true );
        $lead['contactphone'] 		= ( !empty( get_user_meta( $user_id, 'lead_mobile', true ) ) ? 'Yes' : 'No');
        $lead['contactemail'] 		= ( !empty( get_user_meta( $user_id, 'lead_email', true ) ) ? 'Yes' : 'No' );
        $lead['contacttime'] 		= 'Anytime';
        $lead['data1'] 		= get_user_meta( $user_id, 'occupation', true );
        $lead['data2'] 		= get_user_meta( $user_id, 'living', true );
        $lead['data3'] 		= get_user_meta( $user_id, 'notbankrupt', true );
        $lead['data4'] 		= get_user_meta( $user_id, 'vehicle', true );
        $lead['data5'] 		= get_user_meta( $user_id, 'licence', true );

        $dom = new DOMDocument('1.0', 'iso-8859-1');
        $root = $dom->createElement('data');
        $dom->appendChild($root);
        $wrap = $dom->createElement('lead');
        foreach ($lead as $key => $data) {
            $element = $dom->createElement($key);
            $value = $dom->createTextNode($data);
            $element->appendChild($value);
            $wrap->appendChild($element);
        }
        $root->appendChild($wrap);
        $send_xml = $dom->saveXML();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send_xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        $result = curl_exec($ch);
        // echo '<pre>'; print_r($result); echo '</pre>'; exit;
        $output = array();
        $output['success'] = true;
        if (curl_errno($ch)) {
            $output['success'] = false;
            $output['message'] = 'ERROR from curl_errno -> ' . curl_errno($ch) . ': ' . curl_error($ch);
        } else {
            $returnCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            switch ($returnCode) {
                case 200:
                    $dom->loadXML($result);
                    if ($dom->getElementsByTagName('status')->item(0)->textContent == "0") {
                        //good request
                        $output['message'] = "<p> Response Status: Passed - Message: " . $dom->getElementsByTagName('message')->item(0)->textContent;
                        $output['message'] .= "<p> FLG NUMBER: " . $dom->getElementsByTagName('id')->item(0)->textContent;
                        $output['flgNo'] = $dom->getElementsByTagName('id')->item(0)->textContent;
                        update_user_meta( $user_id, 'lead_key', $output['flgNo'] );
                        return $output;
                    } else {
                        $output['success'] = false;
                        $output['message'] = "<p> API Connection: Success - Lead Entry: Failed - Reason: " . $dom->getElementsByTagName('message')->item(0)->textContent;
                    }
                    break;
                default:
                    $output['success'] = false;
                    $output['message'] = '<p>HTTP ERROR -> ' . $returnCode;
                    break;
            }
        }
        curl_close($ch);

        return $output;

	}

}
