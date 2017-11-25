<?php
/*
Plugin Name:    Good Reads Books
Plugin URI:     https://davidsword.ca/wordpress-plugins/
Description:    Showcase currently reading and recently read Goodreads books on your website.
Version:        1.1
Author:         davidsword
Author URI:     https://davidsword.ca/
License:        GPLv3
License URI:    https://www.gnu.org/licenses/quick-guide-gplv3.en.html
Text Domain:    goodrds
*/

// HUSTON ..we have lift off..
add_action( 'init', 'goodrds' );
function goodrds() {
	global $goodrds;
	$goodrds = new goodrds();
}

class goodrds {
	public $menu_id;
	
	/**
	 * Plugin initialization
	 *
	 * @since 1.0
	 */
	public function __construct() {
		
		// load localization
		load_plugin_textdomain( 'goodrds' );

		// admin menu
		add_action( 'admin_menu', array( $this, 'goodrds_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'goodrds_settings' ) );
		
		// admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'goodrds_admin_scripts' ) );
		
		// front end scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'goodrds_scripts' ) );
		
		// cronjob
		add_action('wp', array( $this,'goodrds_schedule_cron'));
		add_action('goodrds_cronjob', array( $this,'goodrds_get'));
		
		// shortcode
		add_shortcode('goodreads', array( $this,'goodrds_show'));
		
		// grab the options, use for entire object
		$this->goodrds_options = $this->goodrds_options();
	}
	
	/**
	 * Add Resources to front end
	 *
	 * @since 1.0
	 */
	function goodrds_scripts() {
		wp_register_style( 'goodrds_css', plugins_url('goodrds.css', __FILE__), false, '0.1' );
		wp_enqueue_style( 'goodrds_css' );

		wp_register_script( 'goodrds_js', plugins_url('goodrds.js', __FILE__), array('jquery'), '0.1', true );
		wp_enqueue_script( 'goodrds_js' );
	}
	
	/**
	 * Add Resources to front end
	 *
	 * @since 1.0
	 */
	function goodrds_admin_scripts() {
		// only on our backend page
		if (get_current_screen()->base == 'settings_page_goodrds') {
	        wp_register_style( 'goodrds_css', plugins_url('goodrds-admin.css', __FILE__), false, '0.1' );
	        wp_enqueue_style( 'goodrds_css' );

	        wp_register_script( 'goodrds_js', plugins_url('goodrds-admin.js', __FILE__), array('jquery'), '0.1', true );
	        wp_enqueue_script( 'goodrds_js' );
	    }
	}
	
	/**
	 * HTML Settings Page
	 *
	 * @since 1.0
	 */
	function goodrds_interface() {
	    if ( !current_user_can( 'manage_options' ) ) 
	        wp_die( __('You do not have sufficient permissions to access this page.','goodrds') );
		?>
		<div class='wrap' id='goodrds'> 
			<h2><?php _e('Good Reads Books','goodrds') ?></h2>
			<?php if (!function_exists('curl_version')) : ?>
				<div class='settings-error error'><p><?php _e("This plugin requires your server to have CURL enabled. You'll need to contact your hosting provider for assistance. See plugin FAQ for more information.",'goodrds') ?></p></div>	
			<?php endif ?>

			<form method="post" action="options.php">
			    <?php
				// every time we load or save this page, lets refresh our api data
				// but not more than once every few seconds (as per their API restriction)
				if ( false === ( $value = get_transient( 'goodrds_getLimit' ) ) ) {
					$this->goodrds_get();
				    set_transient( 'goodrds_getLimit', 'manuallyReloaded', 2 );
				}	
				
				// get our settings
				wp_nonce_field('goodrds_options');
				settings_fields('goodrds_option-group');
				$goodrds_options = $this->goodrds_options;
			    ?>			   
			    <input type="hidden" name="action" value="update" />
			    <input type="hidden" name="page_options" value="goodrds_options" />
				<table border=0 cellpadding=2 cellspacing="2" class='form-table'>
					<tr>
						<th>
							<strong><?php _e('Goodreads API Key','goodrds') ?>:</strong>
						</th>
						<td colspan=2>
							<input class='code' name='goodrds_options[apikey]' placeholder='###################' value='<?php echo $goodrds_options['apikey'] ?>' />
							<p class='description'>
								<?php _e('Get an API Key Here:','goodrds') ?> <a href='https://www.goodreads.com/api/keys' target='_Blank'>goodreads.com/api/keys</a>
								<?php _e('Set whatever "Application name" and "Company name" you want, create and paste the generated key above.','goodrds') ?>
							</p>
						</td>
					</tr>
					<tr>
						<th>
							<strong><?php _e('Goodreads User ID','goodrds') ?>:</strong>
						</th>
						<td colspan=2>
							<input  class='code' name='goodrds_options[user]' placeholder='########-user-name' value='<?php echo $goodrds_options['user'] ?>' />
							<p class='description'>
								<?php _e('Click "View Profile" in the top right of','goodrds') ?>
								 <a href='https://goodreads.com/' target='_Blank'>goodreads.com</a>, 
								<?php _e('then copy/paste from the URL:','goodrds') ?> <code>goodreads.com/user/show/<span style='background:yellow;'>12345678-username</span></code><br />
								<?php _e('While in your profile, in Settings make sure "Who Can View My Profile" is set to public.','goodrds') ?>
							</p>
						</td>
					</tr>
					<tr>
						<th>
							<strong><?php _e('Show','goodrds') ?>:</strong>
						</th>
						<td colspan=2>
							<select name='goodrds_options[show]'>
								<?php echo $this->goodrds_listnumbers($goodrds_options['show']) ?>
							</select> <?php _e('Books Total','goodrds') ?>
						</td>
					</tr>
					<tr>
						<th>
							<strong><?php _e('Show Credit','goodrds') ?>:</strong>
						</th>
						<td colspan=2>
							<?php
								$credit = (isset($goodrds_options['credit']) && $goodrds_options['credit'] == '1') ? 'checked' : '';
							?>
							<label><input type='checkbox' value='1' name='goodrds_options[credit]' <?php echo $credit ?> /> Show "Goodreads" credit</label>
							<p class='description'><?php _e('The Goodreads API agreement requires the showing of "Goodreads" with their content. Wordpress agreement requires the option to toggle them with the default off. Since the API key is yours, the decision is yours.','goodrds') ?>
						</td>
					</tr>
					
					
					
					<?php 
						$currentExpt = count($goodrds_options['exceptions']['ids']);
						$showExpt = ($currentExpt > 0) ? $currentExpt : 1;
						for ($e = 0; $e != $showExpt; $e++) { ?>
					<tr class="exceptions">
						<td>
							<?php if ($e == 0) { ?><p><strong><?php _e('Image Exceptions:','goodrds') ?></strong></p><?php } ?>
						</td>
						<td width=200>
							#<input class='code' type="text" name="goodrds_options[exceptions][ids][]" value='<?php echo $goodrds_options['exceptions']['ids'][$e] ?>' placeholder='Book ID'>
						</td>
						<td>
							<input class='code' type="text" name="goodrds_options[exceptions][urls][]" value='<?php echo $goodrds_options['exceptions']['urls'][$e] ?>'placeholder='https://'> <?php if ($e > 0) { ?><a href="#">[x]</a><?php } ?>
						</td>
					</tr>
					<?php } ?>
					<tr id='last'>
						<td></td>
						<td colspan=2>
							<button class="add_field_button">+ <?php _e('Add Another','goodrds') ?></button>
							<p class='description'><?php _e('Due to licensing, sadly some image covers are not available through Goodreads API.
								If a books image is blank, you can define it here with your own cover.','goodrds') ?></p>
						</td>
					</tr>
				</table>
			    <p class="submit">
			         <input type="submit" class="button-primary" value="<?php _e('Save Settings','goodrds') ?>" />
			    </p>
			</form>
			<p id='streetcred'>
				<?php _e('Plugin By','goodrds') ?> 
				<a href='https://davidsword.ca/' target='_Blank'>David Sword</a>
			</p>
	    </div>    
	    <?php
	}
	
	/**
	 * Our main front-end function, show books
	 *
	 * @since 1.0
	 */
	function goodrds_show() {
		
		// if we have valid options, else our shortcode should spit instructions
		if (!$this->goodrds_haveInfo())
			return "<div id='goodrds' class='error'>".__("Good Reads Books plugin requires you to setup a proper API key and USERNAME in your wp-admin > Settings > Good Reads Books",'goodrds')."</div>";	
		
		if (!function_exists('curl_version'))
			return "<div id='goodrds' class='error'>".__("Good Reads Books plugin requires your server to have CURL enabled. You'll need to contact your hosting provider for assistance. See plugin FAQ for more information.",'goodrds')."</div>";
			
		// get the json returns for the API and parse them
		$goodrds_reading_json = get_option('goodrds_reading_json');
		$goodrds_read_json = get_option('goodrds_read_json');
		if (empty($goodrds_reading_json) || empty($goodrds_read_json))
			return "<div id='goodrds' class='error'>".__("Good Reads Books can't get anything back from the Goodreads API. Make sure your Goodreads profile is set to Public in Settings. If you're still having issues, please contact plugin author.",'goodrds')."</div>";	
		
		$reading 	= new SimpleXMLElement($goodrds_reading_json);
		$read 		= new SimpleXMLElement($goodrds_read_json);
		$image_fix  = array();
		
		// options for showing
		$goodrds_options = $this->goodrds_options;
		$showbooks  = $goodrds_options['show'];
		
		$cReading = count($reading->reviews->review);
		$cRead = $showbooks - $cReading;
		
		// start the showcase
		$return = "
		<div id='goodrds'>
			<div id='goodrds_headings'>";
				$return .= ($cReading > 0) ? "<h5 id='reading' style='flex:{$cReading}'>".__('Reading','goodrds')."</h5>" : '';
				$return .= ($cRead > 0)    ? "<h5 id='read' style='flex:{$cRead}'>".__('Recently Read','goodrds')."</h5>" : '';
				$return .= "
			</div><!--/goodrds_headings-->
			<div id='goodrds_shelves'>\n";
		
		// CURRENTLY READING
		foreach ($reading->reviews->review as $reading) {
			if (!$img = $this->goodrds_image_exceptions($reading->book->id))
				$img = $reading->book->image_url;
			$return .= "<a target='_Blank' href='https://www.goodreads.com/book/show/{$reading->book->id}' title='{$reading->book->title} - #{$reading->book->id}' class='book reading' style='background-image:url({$img});'></a>";
			$showbooks--;
		}
		
		// RECENTLY READ
		#->id
		#->book->id
		#->book->title
		#->book->average_rating
		#->book->authors->author->name
		foreach ($read->reviews->review as $read) {
			if ($showbooks > 0) {
				if (!$img = $this->goodrds_image_exceptions($read->book->id))
					$img = $read->book->image_url;
				$return .= "<a target='_Blank' href='https://www.goodreads.com/book/show/{$read->book->id}' title='{$read->book->title} - #{$read->book->id}' class='book read' style='background-image:url({$img});'></a>";
				$showbooks--;
			}
		}
		$return .= "
		</div><!--/goodrds_shelves-->";
		
		if (isset($goodrds_options['credit']) && $goodrds_options['credit'] == '1')
			$return .= "<a href='https://goodreads.com/' target='_Blank' id='goodrds_poweredby'>".__('Goodreads')."</a>";
		
		$return .= "
		</div><!--/goodrds-->";
		return $return;
			
	}
	
	/**
	 * Custom Images
	 *
	 * send in id, get out exception cover if have
	 *
	 * @since 1.0
	 */
	function goodrds_image_exceptions($id) {
		
		$key = false;
		$id = str_replace(array('#','id_'), '', $id);
		foreach ($this->goodrds_options['exceptions']['ids'] as $k => $exID) {
			$exID = str_replace(array('#','id_'), '', $exID);
			if ($exID == $id)
				$key = $k;
		}
		if ($key)
			return $this->goodrds_options['exceptions']['urls'][$key];
			
		return $key; //false
	}
	
	/**
	 * Call to the GoodReads API with our options
	 *
	 * returns json page, stored in options
	 *
	 * @since 1.0
	 */
	function goodrds_get() {
		
		$options = $this->goodrds_options;
		
		// make sure we've got all we need to connect, if not, dont bother
		if (!$this->goodrds_haveInfo($options)) return;
		
		// get reading
		$reading_shelf = "https://www.goodreads.com/review/list/".$options['user'].".xml?key=".
		                 $options['apikey']."&v=2&shelf=currently-reading&sort=date_started&order=d&page=1&per_page=".$options['show'];
		$reading_json  = $this->goodrds_phoneHome($reading_shelf);
		delete_option('goodrds_reading_json');
		add_option('goodrds_reading_json',$reading_json);
		
		// get read
		$read_shelf = "https://www.goodreads.com/review/list/".$options['user'].".xml?key=".   
		              $options['apikey']."&v=2&shelf=read&sort=date_started&order=d&page=1&per_page=".$options['show'];	
		$read_json  = $this->goodrds_phoneHome($read_shelf);
		delete_option('goodrds_read_json');
		add_option('goodrds_read_json',$read_json);
	}
	
	
	/**
	 * CURL helper
	 *
	 * @since 1.0
	 */
	function goodrds_phoneHome($url) {
		$curl_options = array(
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER 			=> false,
			CURLOPT_FOLLOWLOCATION	=> false,
			CURLOPT_ENCODING	 	=> "",
			CURLOPT_AUTOREFERER 	=> true,
			CURLOPT_CONNECTTIMEOUT 	=> 8,
			CURLOPT_TIMEOUT 		=> 8,
			CURLOPT_MAXREDIRS 		=> 3,
			CURLOPT_SSL_VERIFYHOST	=> false,
			CURLOPT_USERAGENT		=> "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13"
		);
		// make the curl request
		$ch = curl_init($url);
		curl_setopt_array($ch, $curl_options);
		$content = curl_exec( $ch );
		if ( curl_error($ch) ) 
			echo 'Curl error:' . curl_error($ch) . "<br />";
		curl_close( $ch );
		return $content;
	}
	
	
	/**
	 * SELECT number helper
	 *
	 * @since 1.0
	 */
	function goodrds_listnumbers($preselect) {
		$return = '';
		for ($i = 1; $i <= 10; $i++) {
			$pre = ($preselect == $i) ? " selected" : '';
			$return .= "<option{$pre}>{$i}</option>";
		}
		return $return;
	}
	
	/**
	 * Options helper
	 *
	 * @since 1.0
	 */
	function goodrds_options() {
		$goodrds_options = get_option('goodrds_options');		
		// okay we have the options, but lets make sure they're actually there.
		if (!is_array($goodrds_options))
			$goodrds_options = array(
				'apikey' => '',
				'user'   => '',
				'show'   => '8',
				'credit' => '',
				'exceptions' => array(
								'ids' => array(''),
								'urls' => array('')
								)
			);
		return $goodrds_options;
	}
	
	/**
	 * Validator helper
	 *
	 * @since 1.0
	 */
	function goodrds_haveInfo($options = '') {
		if (empty($options))
			$options = $this->goodrds_options;
			
		return (is_array($options) && isset($options['apikey']) && !empty($options['apikey']) && isset($options['user']) && !empty($options['user'])) ? true : false;
	}
	
	/**
	 * CRON job
	 *
	 * As per GoodReads API agreement, content MUST refresh daily
	 *
	 * @since 1.0
	 */
	function goodrds_schedule_cron() {
	  if ( !wp_next_scheduled( 'goodrds_cronjob' ) )
	    wp_schedule_event(time(), 'daily', 'goodrds_cronjob');
	}
	
	/**
	 * SETTINGS page helper
	 *
	 * @since 1.0
	 */
	function goodrds_settings() {
	    register_setting( 'goodrds_option-group', 'goodrds_options' );
	}

	/**
	 * MENU page helper
	 *
	 * @since 1.0
	 */
	public function goodrds_admin_menu() {
		$this->menu_id = add_options_page( __( 'Good Reads Books', 'goodrds' ), __( 'Good Reads Books', 'goodrds' ), 'administrator', 'goodrds', array($this, 'goodrds_interface') );
	}
	
}
?>