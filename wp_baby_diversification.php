<?php
/**
 * @package wp_baby_diversification
 */
/*
Plugin Name: WP Baby Diversification
Description: You can add all what your baby as already tast, when and did he like it. Help you keep track
Author: ArnaudBan
Version: 1.0
Author URI: http://arnaudban.me
*/

// Register Custom Post Type
function ab_wp_baby_food() {

	$labels = array(
		'name'                => _x( 'Foods', 'Post Type General Name', 'wp_baby_diversification' ),
		'singular_name'       => _x( 'Food', 'Post Type Singular Name', 'wp_baby_diversification' ),
		'menu_name'           => __( 'Baby Food', 'wp_baby_diversification' ),
		'parent_item_colon'   => __( 'Parent Food:', 'wp_baby_diversification' ),
		'all_items'           => __( 'All foods', 'wp_baby_diversification' ),
		'view_item'           => __( 'View Food', 'wp_baby_diversification' ),
		'add_new_item'        => __( 'Add New food', 'wp_baby_diversification' ),
		'add_new'             => __( 'New food', 'wp_baby_diversification' ),
		'edit_item'           => __( 'Edit Food', 'wp_baby_diversification' ),
		'update_item'         => __( 'Update food', 'wp_baby_diversification' ),
		'search_items'        => __( 'Search foods', 'wp_baby_diversification' ),
		'not_found'           => __( 'No foods found', 'wp_baby_diversification' ),
		'not_found_in_trash'  => __( 'No Food found in Trash', 'wp_baby_diversification' ),
	);
	$args = array(
		'label'                 => __( 'food', 'wp_baby_diversification' ),
		'description'           => __( 'fisrt baby food', 'wp_baby_diversification' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'author', 'revisions', 'thumbnail'),
		'hierarchical'          => false,
		'register_meta_box_cb'  => 'ab_food_metabox',
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_in_admin_bar'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'baby-food', $args );

	$labels = array(
		'name'              => _x( 'Classifications', 'taxonomy general name' , 'wp_baby_diversification' ),
		'singular_name'     => _x( 'Classification', 'taxonomy singular name' , 'wp_baby_diversification' ),
		'search_items'      => __( 'Search classifications' , 'wp_baby_diversification' ),
		'all_items'         => __( 'All classifications' , 'wp_baby_diversification' ),
		'parent_item'       => __( 'Parent classification' , 'wp_baby_diversification' ),
		'parent_item_colon' => __( 'Parent classification:' , 'wp_baby_diversification' ),
		'edit_item'         => __( 'Edit classification' , 'wp_baby_diversification' ),
		'update_item'       => __( 'Update classification' , 'wp_baby_diversification' ),
		'add_new_item'      => __( 'Add New classification' , 'wp_baby_diversification' ),
		'new_item_name'     => __( 'New classification Name' , 'wp_baby_diversification' ),
		'menu_name'         => __( 'classification' , 'wp_baby_diversification' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'classification' ),
	);

	register_taxonomy( 'classification', array( 'baby-food' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'ab_wp_baby_food', 0 );

// Add the food metabox
function ab_food_metabox(){
	add_meta_box(
                    'ab_food_meta',
                    __( 'Food meta', 'wp_baby_diversification' ),
                    'ab_food_meta_content',
                    'baby-food',
                    'normal',
                    'high'
    );
}

// Food metabox content display
function ab_food_meta_content(){
	$food_meta_date = get_post_meta( get_the_ID() , 'ab_food_meta_first_date', true);
	$food_meta_note = get_post_meta( get_the_ID() , 'ab_food_meta_note', true);

	// Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ab_food_meta_nonce' );

    ?>
    <table class="form-table">
    	<tbody>
    		<tr>
    			<th>
    				<label for="ab_food_meta_date">
    					<?php  _e("First time date", 'wp_baby_diversification' ); ?>
    				</label>
    			</th>
    			<td>
    				<input type="date" id="ab_food_meta_date" name="ab_food_meta_first_date" value="<?php if( $food_meta_date ) echo $food_meta_date; ?>" />
    			</td>
    		</tr>
    		<tr>
    			<th>
    				<label for="ab_food_meta_note">
    					<?php  _e("Baby's appreciation", 'wp_baby_diversification' ); ?>
    				</label>
    			</th>
    			<td>
    				<input type="number" id="ab_food_meta_note" name="ab_food_meta_note" min="0" max="5" value="<?php if( $food_meta_note ) echo $food_meta_note; ?>" />
    			</td>
    		</tr>
    	</tbody>
    </table>
   	<?php
}

/**
 * Save the food metabox
 * @param  int $post_id
 */
function ab_save_food_metabox( $post_id ) {

    // verify this came from the our screen and with proper authorization,
    if ( isset( $_POST['ab_food_meta_nonce'] ) && wp_verify_nonce( $_POST['ab_food_meta_nonce'], plugin_basename( __FILE__ ) ) ){

        // Check permissions
        if ( current_user_can( 'edit_page', $post_id ) ){

            //update post meta
            if( isset( $_POST['ab_food_meta_first_date'] ) ){
            	$food_meta_date = esc_attr( $_POST['ab_food_meta_first_date'] );
                update_post_meta($post_id, 'ab_food_meta_first_date', $food_meta_date);
            }
            if( isset( $_POST['ab_food_meta_note'] ) && is_numeric( $_POST['ab_food_meta_note'] )){
            	$food_meta_note = intval( $_POST['ab_food_meta_note'] );
            	if ( $food_meta_note < 0 ) $food_meta_note = 0; 
            	if ( $food_meta_note > 5 ) $food_meta_note = 5; 
                update_post_meta($post_id, 'ab_food_meta_note', $food_meta_note);
            }

        }

    }
}
add_action( 'save_post', 'ab_save_food_metabox' );


/**
 * Display all the baby food
 */
function ab_display_baby_food_table( $attr ){

	$baby_food_query_args = array(
			'post_type'      => 'baby-food',
			'posts_per_page' => -1,
			'post_meta' => 'ab_food_meta_first_date',
			'orderby' => 'meta_key',
			'order' => 'DESC',
		);

	$baby_food = new WP_Query( $baby_food_query_args );

	$baby_food_table = '';

	if( $baby_food->have_posts() ){

		$baby_food_table .= '<table>';
		
		$baby_food_table .= '<tbody>';
		$baby_food_table .= '<tr>';

		$baby_food_table .= '<th>';
		$baby_food_table .= __('Food', 'wp_baby_diversification');
		$baby_food_table .= '</th>';

		$baby_food_table .= '<th>';
		$baby_food_table .= __('First taste date', 'wp_baby_diversification');
		$baby_food_table .= '</th>';

		$baby_food_table .= '<th>';
		$baby_food_table .= __('Note', 'wp_baby_diversification');
		$baby_food_table .= '</th>';

		$baby_food_table .= '<th>';
		$baby_food_table .= __('Comment', 'wp_baby_diversification');
		$baby_food_table .= '</th>';

		$baby_food_table .= '</tr>';


		while ( $baby_food->have_posts()) {
			$baby_food->the_post();
			$baby_food_table .= '<tr>';

			$baby_food_table .= '<td>';
			$baby_food_table .= get_the_title();
			$baby_food_table .= '</td>';

			$baby_food_table .= '<td>';

			$the_first_taste_date = get_post_meta( get_the_ID(), 'ab_food_meta_first_date', true );

			if( $the_first_taste_date ){
				$baby_food_table .= mysql2date( get_option( 'date_format' ), $the_first_taste_date );
			}
			$baby_food_table .= '</td>';

			$baby_food_table .= '<td>';
			$baby_food_table .= get_post_meta( get_the_ID(), 'ab_food_meta_note', true );
			$baby_food_table .= '</td>';

			$baby_food_table .= '<td>';
			$baby_food_table .= get_the_content();
			$baby_food_table .= '</td>';

			$baby_food_table .= '</tr>';
			
		}

		wp_reset_postdata();
		$baby_food_table .= '</tbody>';



		$baby_food_table .= '</table>';
	}

	return $baby_food_table;

}
add_shortcode( 'baby-food', 'ab_display_baby_food_table' );