<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://costinbotez.co.uk
 * @since      1.0.0
 *
 * @package    Wp_Flg360
 * @subpackage Wp_Flg360/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Flg360
 * @subpackage Wp_Flg360/public
 * @author     Costin Botez <costibotez94@gmail.com>
 */
class Wp_Flg360_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-flg360-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-flg360-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Track Contact Form 7 forms submissions
	 *
	 * @since    1.0.0
	 */
	public function track_cf7_post() {

		if ( $_POST ) {
			if ( isset( $_POST['_wpcf7'] ) && ( !empty( $_POST['_wpcf7'] ) ) ) {	// if it's a CF7 submission

				$front_end_fields = array();	// empty array by default

				foreach ($_POST as $key => $value) {
					if ( strpos( $key, '_wpcf7' ) === false ) {	// filter ONLY front-end fields
						$front_end_fields[strtolower($key)] = $value;
					}
				}
				if ( $this->send_lead_to_flg($front_end_fields) === 1) {
					echo '<pre>'; print_r($front_end_fields); echo '</pre>'; exit;
				} else {
					echo '<pre>'; print_r(get_user_meta(9)); echo '</pre>'; exit;
				}
			}
		}
	}

	/**
	 * Send CF7 submissions to FLG360
	 *
	 * @since    1.0.0
	 */
	protected function send_lead_to_flg($front_end_fields) {

		$user_id = $this->create_user_into_wp($front_end_fields);
		// echo '<pre>'; print_r($user_id); echo '</pre>'; exit;
		if( $user_id > 0 ) {	// filter -1 (already exists) and -2(not valid email)
	        $key 		= get_option('wp_flg360_api_key');			// API Access key
	        $url 		= get_option('wp_flg360_api_url');			// API Request URL
	        $leadgroup 	= get_option('wp_flg360_api_leadgroup');	// API LeadGroup
	        $site 		= get_option('wp_flg360_api_site');			// API registered Site

	        $lead = array();										// empty lead by default

	        $lead['key'] 		= $key;
	        $lead['leadgroup'] 	= $leadgroup;
	        $lead['site'] 		= $site;
	        // $lead['introducer'] = $user_id;
	        // $lead['user']		= $user_id;
	        $lead['source']		= $front_end_fields['source'];
	        $lead['title'] 		= $front_end_fields['title'];
	        $lead['firstname'] 	= $front_end_fields['firstname'];
	        $lead['lastname'] 	= $front_end_fields['surname'];
	        $lead['jobtitle'] 	= $front_end_fields['occupation'];
	        $lead['phone1'] 	= $front_end_fields['mobile'];
	        $lead['phone2'] 	= $front_end_fields['mobile'];
	        $lead['email'] 		= $front_end_fields['email'];
	        $lead['address'] 	= stripslashes($front_end_fields['housenumber']);
	        $lead['postcode'] 	= $front_end_fields['postcode'];
	    	$lead['dobday'] 	= $front_end_fields['dobday'];
	        $lead['dobmonth'] 	= $front_end_fields['dobmonth'];
	        $lead['dobyear'] 	= $front_end_fields['dobyear'];
	        $lead['contactphone'] 		= ( !empty( $front_end_fields['mobile'] ) ? 'Yes' : 'No');
	        $lead['contactemail'] 		= ( !empty( $front_end_fields['email'] ) ? 'Yes' : 'No' );
	        $lead['contacttime'] 		= 'Anytime';
	        $lead['data1'] 		= $front_end_fields['living'];
	        $lead['data2'] 		= $front_end_fields['promocode'];
	        $lead['data3'] 		= $front_end_fields['addedcomments'];
	        $lead['data4'] 		= $front_end_fields['vehicle'];
	        $lead['data5'] 		= $front_end_fields['licence'];



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
	        curl_close($ch);

	        return 1;
	    }

	    return 0;

    }

    /**
	 * Send CF7 submissions to FLG360
	 *
	 * @since    1.0.0
	 */
	protected function create_user_into_wp($front_end_fields) {
		if( !empty( $front_end_fields['email'] ) && $this->is_valid_email( $front_end_fields['email'] ) ) {
		// return -1;

			$username = explode("@", $front_end_fields['email'])[0];
			$password = md5($front_end_fields['firstname'].$front_end_fields['surname'].rand(0, strlen($front_end_fields['email'])));
			$email_address = $front_end_fields['email'];

			$user_id = -1;

			if ( ! username_exists( $username ) ) {
				$user_id = wp_create_user( $username, $password, $email_address );
				$user = new WP_User( $user_id );
				$user->set_role( 'subscriber' );

			}

			if ( !is_wp_error($user_id) ) {
                foreach ($front_end_fields as $key => $value) {
                    if (!update_user_meta($user_id, 'lead_'.$key, $value, get_user_meta($user_id, 'lead_'.$key, $value))) {
                        add_user_meta($user_id, 'lead_'.$key, $value, true);
                    }
                }
				return $user_id;
            }
			return -1;
		}
		return -2;
	}

	/**
	 * Check if valid email
	 *
	 * @since    1.0.0
	 */
	protected function is_valid_email($email) {
        return(filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) ? true : false;
    }

}
