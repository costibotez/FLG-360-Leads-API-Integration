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

			// echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
				$front_end_fields = array();	// empty array by default
				if( !empty( $_SERVER['HTTP_REFERER'] ) ) {
					$front_end_fields['tracking'] = explode("?", $_SERVER['HTTP_REFERER']);
					if( isset( $front_end_fields['tracking'][1] ) ) {
						$front_end_fields['tracking'] = $front_end_fields['tracking'][1];
					} else {
						unset($front_end_fields['tracking']);
					}
				}
				foreach ($_POST as $key => $value) {
					if ( strpos( $key, '_wpcf7' ) === false ) {	// filter ONLY front-end fields
						$front_end_fields[strtolower($key)] = $value;
					}
				}
				// Debug - test in NetWork area for bugs
				// if ( $this->send_lead_to_flg($front_end_fields) === 1) {
					// echo '<pre>'; print_r($this->get_vars); echo '</pre>'; exit;
				// } else {
				// 	echo '<pre>'; print_r(get_user_meta(9)); echo '</pre>'; exit;
				// }
				$this->send_lead_to_flg($front_end_fields);
				// echo '<pre>'; print_r($front_end_fields); echo '</pre>'; exit;
				// echo '<pre>'; print_r($this->send_lead_to_flg($front_end_fields)); echo '</pre>'; exit;
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
		// echo '<pre>'; print_r(explode('&',$front_end_fields['tracking'])[2]); echo '</pre>'; exit;
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
	        if( isset( $front_end_fields['tracking'] ) ) {
	        	if( strpos($front_end_fields['tracking'], 'gclid') !== false ) {	// Google PPC
	        		$term = explode('=', $front_end_fields['tracking'])[1];
	        		$lead['source']	= 'Google';
	        		$lead['medium']	= 'PPC';
	        		$lead['term']	= $term;
	        	} else {															// Email marketing
		        	$tracking_pairs = explode('&',$front_end_fields['tracking']);
		        	if( strpos($tracking_pairs[0], '=') !== false ) {
		        		$source = explode('=', $tracking_pairs[0])[1];
		        		$lead['source']	= $source;
		        	}
		        	if( strpos($tracking_pairs[1], '=') !== false ) {
		        		$medium = explode('=', $tracking_pairs[1])[1];
		        		$lead['medium']	= $medium;
		        	}
		        	if( strpos($tracking_pairs[2], '=') !== false ) {
		        		$term = explode('=', $tracking_pairs[2])[1];
		        		$lead['term'] = $term;
		        	}

		        	if( !isset( $lead['source'] ) || empty( $lead['source'] ) ||
	        			!isset( $lead['medium'] ) || empty( $lead['medium'] ) ||
	        			!isset( $lead['term'] ) || empty( $lead['term'] ) ) {
		        		unset( $lead['source'] );
		        		unset( $lead['medium'] );
		        		unset( $lead['term'] );

		        	}
		        }
	        }

	        if( !isset( $lead['source'] ) || empty( $lead['source'] ) ||
	        	!isset( $lead['medium'] ) || empty( $lead['medium'] ) ||
	        	!isset( $lead['term'] ) || empty( $lead['term'] ) ) {

	        	if( isset( $front_end_fields['source'] ) ) {
		        	$lead['source'] = $front_end_fields['source'];
		        	switch ($front_end_fields['source']) {
		        		case 'Facebook':
		        			$lead['medium']	= 'Referral';
		        			break;
		        		case 'Email':
		        			$lead['medium']	= 'Referral';
		        			break;
		        		case 'Radio':
		        			$lead['medium']	= 'Referral';
		        			break;
		        		case 'Google':
		        			$lead['medium']	= 'Organic';
		        			break;
		        		case 'Bing':
		        			$lead['medium']	= 'Organic';
		        			break;
		        		case 'Buses':
		        			$lead['medium']	= 'Referral';
		        			break;
		        		default:
		        			$lead['medium']	= 'Referral';
		        			break;
		        	}
		        	$lead['term'] = 'None';
		        } else {
		        	$lead['source']	= 'Google';
		        	$lead['medium']	= 'Organic';
		        	$lead['term'] = 'None';
		        }
	        }
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
	        $lead['data1'] 		= $front_end_fields['occupation'];
	        $lead['data2'] 		= $front_end_fields['living'];
	        $lead['data3'] 		= $front_end_fields['notbankrupt'];
	        $lead['data4'] 		= $front_end_fields['vehicle'];
	        $lead['data5'] 		= $front_end_fields['licence'];
	        if( isset( $front_end_fields['source'] ) && !empty( $front_end_fields['source'] ) ) {
	        	$lead['data6'] 		= $front_end_fields['source'];
	        }
	        if( isset( $front_end_fields['promocode'] ) && !empty( $front_end_fields['promocode'] ) ) {
	        	$lead['data7'] 		= $front_end_fields['promocode'];
	        }
	        if( isset( $front_end_fields['addedcomments'] ) && !empty( $front_end_fields['addedcomments'] ) ) {
	        	$lead['data8'] 		= $front_end_fields['addedcomments'];
	        }

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
	            error_log("\n [" . date("Y/m/d h:i:sa") . "] ERROR from curl_errno -> " . curl_errno($ch) . ': ' . curl_error($ch), 3, plugin_dir_path( __FILE__ ) . 'php.log');
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
	                        // update_user_meta( $user_id, 'lead_key', $output['flgNo'] );
	                        return $output;
	                    } else {
	                        $output['success'] = false;
	                        $output['message'] = "<p> API Connection: Success - Lead Entry: Failed - Reason: " . $dom->getElementsByTagName('message')->item(0)->textContent . ' For user: ' . $lead['firstname'] . ' ' . $lead['lastname'];
	                        error_log("\n [" . date("Y/m/d h:i:sa") . "] API Connection: Success - Lead Entry: Failed - Reason: " . $dom->getElementsByTagName('message')->item(0)->textContent . ' For user: ' . $lead['firstname'] . ' ' . $lead['lastname'] . ' ' . $lead['source'], 3, plugin_dir_path( __FILE__ ) . 'php.log');
	                    }
	                    break;
	                default:
	                    $output['success'] = false;
	                    $output['message'] = 'HTTP ERROR -> ' . $returnCode;
	                    error_log("\n [" . date("Y/m/d h:i:sa") . "] HTTP ERROR -> " . $returnCode, 3, plugin_dir_path( __FILE__ ) . 'php.log');
	                    break;
	            }
	        }
	        curl_close($ch);

	        return $output;
	    }
	    error_log("\n [" . date("Y/m/d h:i:sa") . "] Mandatory fields for user: " .
	    	         'First name: ' . $front_end_fields['firstname'] . ' ' .
	    	         'Last name: ' . $front_end_fields['surname'] . ' ' .
	    	         'Post code: ' . $front_end_fields['postcode'] . ' ' .
	    	         'Mobile: ' . $front_end_fields['mobile'] . ' ' .
	    	         'Housenumber: ' . $front_end_fields['housenumber'] , 3, plugin_dir_path( __FILE__ ) . 'php.log');

	    return 0;

    }

    /**
	 * Create user into WP before converting into lead
	 *
	 * @since    1.0.0
	 */
	protected function create_user_into_wp( $front_end_fields ) {
		if( !empty( $front_end_fields['form_id'] ) && $front_end_fields['form_id'] == '7930') {
			if( !empty( $front_end_fields['firstname'] ) && !empty( $front_end_fields['surname'] ) && !empty( $front_end_fields['postcode'] ) && !empty( $front_end_fields['mobile'] ) && !empty( $front_end_fields['housenumber'] ) && !empty( $front_end_fields['email'] ) && $this->is_valid_email( $front_end_fields['email'] ) ) {

				// $username = explode("@", $front_end_fields['email'])[0];
				// $password = md5($front_end_fields['firstname'].$front_end_fields['surname'].rand(0, strlen($front_end_fields['email'])));
				// $email_address = $front_end_fields['email'];

				// $user_id = -1;

				// if ( ! username_exists( $username ) ) {
				// 	$user_id = wp_create_user( $username, $password, $email_address );
				// 	$user = new WP_User( $user_id );
				// 	$user->set_role( 'subscriber' );

				// }

				// if ( !is_wp_error($user_id) ) {
	   //              foreach ($front_end_fields as $key => $value) {
	   //                  if (!update_user_meta($user_id, 'lead_'.$key, $value, get_user_meta($user_id, 'lead_'.$key, $value))) {
	   //                      add_user_meta($user_id, 'lead_'.$key, $value, true);
	   //                  }
	   //              }
				// 	return $user_id;
	   //          }
				// return -1;
				return 1;
			}
			return -2;
		} else {
			if( !empty( $front_end_fields['firstname'] ) && !empty( $front_end_fields['surname'] ) && !empty( $front_end_fields['email'] ) && $this->is_valid_email( $front_end_fields['email'] ) ) {

				// $username = explode("@", $front_end_fields['email'])[0];
				// $password = md5($front_end_fields['firstname'].$front_end_fields['surname'].rand(0, strlen($front_end_fields['email'])));
				// $email_address = $front_end_fields['email'];

				// $user_id = -1;

				// if ( ! username_exists( $username ) ) {
				// 	$user_id = wp_create_user( $username, $password, $email_address );
				// 	$user = new WP_User( $user_id );
				// 	$user->set_role( 'subscriber' );

				// }

				// if ( !is_wp_error($user_id) ) {
	   //              foreach ($front_end_fields as $key => $value) {
	   //                  if (!update_user_meta($user_id, 'lead_'.$key, $value, get_user_meta($user_id, 'lead_'.$key, $value))) {
	   //                      add_user_meta($user_id, 'lead_'.$key, $value, true);
	   //                  }
	   //              }
				// 	return $user_id;
	   //          }
				// return -1;
				return 1;
			}
			return -2;
		}
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
