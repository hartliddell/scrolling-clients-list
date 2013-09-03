<?php 

/*
* Plugin Name: Scrolling Client List
* Description: A simple list of clients with scrolling marquee.
* Author: Leaping Fish 
* Author URI: http://www.leapingfish.io
* */

/******************************************
* ADD CUSTOM POST TYPE
******************************************/

function lf_clients_custom_post_type() {
	
	$labels = array(
		'name'               => _x( 'Clients', 'post type general name' ),
		'singular_name'      => _x( 'Client', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Client' ),
		'add_new_item'       => __( 'Add New Client' ),
		'edit_item'          => __( 'Edit Client' ),
		'new_item'           => __( 'New Client' ),
		'all_items'          => __( 'All Clients' ),
		'view_item'          => __( 'View Client' ),
		'search_items'       => __( 'Search Clients' ),
		'not_found'          => __( 'No clients found' ),
		'not_found_in_trash' => __( 'No clients found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Clients'
	);

	$args = array(
		'labels' 				=> $labels,
		'description' 	=> "Clients list",
		'public' 				=> true,
		'menu_position' => 5,
		'supports' 			=> array('title'),
		'has_archive' 	=> true,
		'rewrite' => array('slug' => 'clients_list')
	);

	register_post_type('lf_clients', $args);

}

add_action('init', 'lf_clients_custom_post_type');

/******************************************
* CREATE CUSTOM META BOX FOR CLIENT URL
******************************************/

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'lf_clients_url_setup' );
add_action( 'load-post-new.php', 'lf_clients_url_setup' );

/* Meta box setup function. */
function lf_clients_url_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'lf_add_clients_url' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'lf_save_client_url', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function lf_add_clients_url() {

	add_meta_box(
		'lf-client-url-class',			// Unique ID
		esc_html__( 'Client URL', 'example' ),		// Title
		'lf_client_url_meta_boxes',		// Callback function
		'lf_clients',					// Admin page (or post type)
		'normal',					// Context
		'default'					// Priority
	);
}

/* Display the post meta box. */
function lf_client_url_meta_boxes( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'lf_client_url_post_class_nonce' ); ?>

	<p>
		<input class="widefat" type="text" name="lf-client-url-class" id="lf-client-url-class" value="<?php echo esc_attr( get_post_meta( $object->ID, 'lf_client_url_class', true ) ); ?>" size="30" />
	</p>
<?php }

/* Save the meta box's post metadata. */
function lf_save_client_url( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['lf_client_url_post_class_nonce'] ) || !wp_verify_nonce( $_POST['lf_client_url_post_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['lf-client-url-class'] ) ? esc_url( $_POST['lf-client-url-class'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'lf_client_url_class';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}

/******************************************
* ADD SHORTCODE TO SHOW CLIENT URLS
******************************************/

//shortcode: [show_clients]
add_shortcode( 'show_clients', 'show_clients_func' );

function show_clients_func() {

	$args = array(
		'post_type' => 'lf_clients',
		'posts_per_page' => -1
	);
    
  $clients_output = '';
	$lf_clients = new WP_Query( $args );
	if( $lf_clients->have_posts() ) {

	$clients_output .= '<ul class="marquee">';
	
		while( $lf_clients->have_posts() ) {

	    $lf_clients->the_post();

	    $this_url = get_post_meta( get_the_ID(), 'lf_client_url_class', true );
	    
	    $li_item = '<li>';
	    $this_url ? $li_item .= '<a href="' . $this_url . '" target="_blank">' : ''; 
	    $li_item .= get_the_title();
			$this_url ? $li_item .= '</a>' : '';
	    $li_item .= '</li>';

	    $clients_output .= $li_item;
            
		}
	
	$clients_output .= '</ul>';

	}

return $clients_output;

}


function add_js_scroll_text_vert() {

	wp_register_script( 'scroll-text-vert', plugins_url( '/js/scroll-text-vert.js', __FILE__ ), '', '', true );
	wp_enqueue_script( 'scroll-text-vert' );

}

add_action( 'wp_enqueue_scripts', 'add_js_scroll_text_vert', 20 );

function add_css_scroll_text_vert() {

	wp_register_style( 'scroll-text-vert', plugins_url( '/css/scroll-text-vert.css', __FILE__ ), '', '', 'all' );
	wp_enqueue_style( 'scroll-text-vert' );

}

add_action( 'wp_enqueue_scripts', 'add_css_scroll_text_vert' );
