<?php
	/* 
	custom column preview
	*************************/

// actions
add_action( 'manage_posts_custom_column', 'ta_review_post_data_row', 10, 2);			// manage review rating column

// filters
add_filter( 'manage_edit-post_columns', 'ta_review_post_header_columns', 10, 1);		// add title

// adding review rating column
function ta_review_post_header_columns($columns)
{
    if (!isset($columns['ratings']))
        $columns['ratings'] = "Rating";
   
    return $columns;
}

function ta_review_post_data_row($column_name, $post_id) {
	global $post;
	$custom = get_post_custom();
	
    switch($column_name)
    {
        case 'ratings':       

			$rating = $custom["ta_post_review_rating"][0];
			if ($rating) {
			$rating_star = $rating * 20;

			echo '
					<div class="ta_rating result rating">
					<div class="result" style="width:' . $rating_star . '%;" title="' . $rating . '">' . $rating . '</div>
					</div>';
			}	else {
						echo '
								<div class="ta_rating result rating" title="Not a review!"></div>';
				}
            break;
                       
        default:
            break;
    }
}
?>