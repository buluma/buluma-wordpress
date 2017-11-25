<?php
/*
 * GS Behance Portfolio - Theme One (Projects)
 * @author Golam Samdani <samdani1997@gmail.com>
 * 
 */

$output .= '<div class="container">';
	$output .= '<div class="row">';

	foreach( $gs_behance_shots as $gs_behance_shot ) {
        if (is_array( $gs_behance_shot )) {

            foreach( $gs_behance_shot  as  $gs_beh_single_shot ){
                $output .= '<div class="col-md-'.$atts['column'].' beh-projects">';
                    $output .= '<a href="' . $gs_beh_single_shot[ 'url' ]. '" target="'. $gs_beh_link_tar .'">';
                    	$output .= '<img src="'.$gs_beh_single_shot['covers'][404].'"/>';
                    $output .= '</a>';

                $output .= '</div>'; // end col

            } // // foreach
        } // array
    } // foreach
    
    $output .= '</div>'; // end row
    do_action('gs_behance_custom_css');
$output .= '</div>'; // end container

return $output;