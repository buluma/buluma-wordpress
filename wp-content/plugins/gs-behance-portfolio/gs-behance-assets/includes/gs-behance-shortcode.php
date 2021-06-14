<?php

// -- Getting values from setting panel
function gs_behance_getoption( $option, $section, $default = '' ) {
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

// -- Shortcode [gs_behance]

add_shortcode('gs_behance','gs_behance_shortcode');

function gs_behance_shortcode( $atts ) {

	$gs_beh_user = gs_behance_getoption('gs_beh_user', 'gs_behance_settings', 'lazutina');
		$gs_beh_acc_token = gs_behance_getoption('gs_beh_acc_token', 'gs_behance_settings', '');
		$gs_beh_tot_projects = gs_behance_getoption('gs_beh_tot_projects', 'gs_behance_settings', '');
		$gs_beh_cols = gs_behance_getoption('gs_beh_cols', 'gs_behance_settings', 3);
		$gs_beh_theme = gs_behance_getoption('gs_beh_theme', 'gs_behance_settings', 'gs_beh_theme1');
		$gs_beh_link_tar = gs_behance_getoption('gs_beh_link_tar', 'gs_behance_settings', '_blank');

	//Check for missing information
    if (empty( $gs_beh_user )) {
        return '<div class="gs_beh_error">Enter a userid with shortcode or in <b><i>GS PLugins > GS Behance Settings > Behance User</i></b></div>';
    }
    if (empty( $gs_beh_acc_token )) {
        return '<div class="gs_beh_error">Enter an API key at <b>Behance Access Token</b> field in <b><i>GS PLugins > GS Behance Settings</i></b></div>';
    }
    
	$atts = shortcode_atts(
		array(
			'userid'	=> $gs_beh_user,
			'count' 	=> $gs_beh_tot_projects,
			'column'	=> $gs_beh_cols,
			'theme'		=> $gs_beh_theme
    ), $atts );


	$gsbeh_url = "https://www.behance.net/v2/users/".$atts['userid']."/projects?api_key=".$gs_beh_acc_token."&per_page=".$atts['count']."&page=1"; // page for offset
	$gsbeh_response = wp_remote_get( $gsbeh_url, array( 'sslverify' => false ) );
	$gsbeh_xml = wp_remote_retrieve_body( $gsbeh_response );
	$gsbeh_json = json_decode( $gsbeh_xml ,true);
	$gs_behance_shots = $gsbeh_json;

	if(is_array($gsbeh_response) && array_key_exists('body', $gsbeh_response)) 
	{
        $gsbeh_response = json_decode($gsbeh_response['body']);

        if ($gsbeh_response->http_code == 200) {
        	// Great success & execute theme!
        } elseif ($gsbeh_response->http_code == 429) {
            return '<p>You have reached the rate limit of 150 requests per hour, set by Behance</p>';
        } elseif ($gsbeh_response->http_code == 403) {
            return '<p>The API key added at <b>Behance Access Token</b> is incorrect, please double check and try again.</p>';
        } elseif ($gsbeh_response->http_code == 404) {
            return '<p>The Behance user you added at <b><i>GS PLugins > GS Behance Settings > Behance User / Shortcode</i></b> - is NOT found, Please recheck & add valid Behance user.</p>';
        } else {
            return '<p>Something went wrong. Please contact the plugin author.</p>';
        }
    }

	$output = '';
	$output .= '<div class="gs_beh_area '.$atts['theme'].'">';

		if ( $atts['theme'] == 'gs_beh_theme1' ) {
			include GSBEH_FILES_DIR . '/includes/templates/gs_behance_structure_one.php';
		} 

	$output .= '</div>'; // end gs_drib_area
	return $output;
} // end function