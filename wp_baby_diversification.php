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
		'label'               => __( 'food', 'wp_baby_diversification' ),
		'description'         => __( 'fisrt baby food', 'wp_baby_diversification' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'revisions', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'food', $args );

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

	register_taxonomy( 'classification', array( 'food' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'ab_wp_baby_food', 0 );