<?php
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Register CPT: cinza_slider
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action( 'init', 'cslider_register_post_type' );
function cslider_register_post_type() {
	$labels = [
		'name'                     => esc_html__( 'Sliders', 'your-textdomain' ),
		'singular_name'            => esc_html__( 'Slider', 'your-textdomain' ),
		'add_new'                  => esc_html__( 'Add New', 'your-textdomain' ),
		'add_new_item'             => esc_html__( 'Add new slider', 'your-textdomain' ),
		'edit_item'                => esc_html__( 'Edit Slider', 'your-textdomain' ),
		'new_item'                 => esc_html__( 'New Slider', 'your-textdomain' ),
		'view_item'                => esc_html__( 'View Slider', 'your-textdomain' ),
		'view_items'               => esc_html__( 'View Sliders', 'your-textdomain' ),
		'search_items'             => esc_html__( 'Search Sliders', 'your-textdomain' ),
		'not_found'                => esc_html__( 'No sliders found', 'your-textdomain' ),
		'not_found_in_trash'       => esc_html__( 'No sliders found in Trash', 'your-textdomain' ),
		'parent_item_colon'        => esc_html__( 'Parent Slider:', 'your-textdomain' ),
		'all_items'                => esc_html__( 'All Sliders', 'your-textdomain' ),
		'archives'                 => esc_html__( 'Slider Archives', 'your-textdomain' ),
		'attributes'               => esc_html__( 'Slider Attributes', 'your-textdomain' ),
		'insert_into_item'         => esc_html__( 'Insert into slider', 'your-textdomain' ),
		'uploaded_to_this_item'    => esc_html__( 'Uploaded to this slider', 'your-textdomain' ),
		'featured_image'           => esc_html__( 'Featured image', 'your-textdomain' ),
		'set_featured_image'       => esc_html__( 'Set featured image', 'your-textdomain' ),
		'remove_featured_image'    => esc_html__( 'Remove featured image', 'your-textdomain' ),
		'use_featured_image'       => esc_html__( 'Use as featured image', 'your-textdomain' ),
		'menu_name'                => esc_html__( 'Sliders', 'your-textdomain' ),
		'filter_items_list'        => esc_html__( 'Filter sliders list', 'your-textdomain' ),
		'filter_by_date'           => esc_html__( '', 'your-textdomain' ),
		'items_list_navigation'    => esc_html__( 'Sliders list navigation', 'your-textdomain' ),
		'items_list'               => esc_html__( 'Sliders list', 'your-textdomain' ),
		'item_published'           => esc_html__( 'Slider published', 'your-textdomain' ),
		'item_published_privately' => esc_html__( 'Slider published privately', 'your-textdomain' ),
		'item_reverted_to_draft'   => esc_html__( 'Slider reverted to draft', 'your-textdomain' ),
		'item_scheduled'           => esc_html__( 'Slider scheduled', 'your-textdomain' ),
		'item_updated'             => esc_html__( 'Slider updated', 'your-textdomain' ),
		'text_domain'              => esc_html__( 'your-textdomain', 'your-textdomain' ),
	];
	$args = [
		'label'               => esc_html__( 'Sliders', 'your-textdomain' ),
		'labels'              => $labels,
		'description'         => '',
		'public'              => true,
		'hierarchical'        => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => false,
		'show_in_rest'        => true,
		'query_var'           => false,
		'can_export'          => true,
		'delete_with_user'    => false,
		'has_archive'         => false,
		'rest_base'           => '',
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-admin-generic',
		'menu_position'       => '',
		'capability_type'     => 'post',
		'supports'            => ['title', 'revisions', 'custom-fields'],
		'taxonomies'          => [],
		'rewrite'             => [
			'with_front' => false,
		],
	];

	register_post_type( 'cinza_slider', $args );
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Add Meta Box: cinza_slider_meta_boxes
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action( "admin_init", "add_cinza_slider_meta_boxes" );
function add_cinza_slider_meta_boxes() {
    add_meta_box(
        "cinza_slider_meta_boxes", 	// $id
        "Slides", 					// $title
        "cinza_slider_meta_boxes", 	// $callback
        "cinza_slider", 			// $screen
        "normal", 					// $context
        "high" 						// $priority
    );
}

$meta_args = array(
    'type'         => 'string',
    'description'  => 'A meta key associated with a string meta value.',
    'single'       => true,
    'show_in_rest' => true,
);
register_post_meta( 'cinza_slider', '_cinza_slide_images', $meta_args );

$meta_args = array(
    'type'         => 'string',
    'description'  => 'A meta key associated with a string meta value.',
    'single'       => true,
    'show_in_rest' => true,
);
register_post_meta( 'cinza_slider', '_cinza_slide_contents', $meta_args );

function cinza_slider_meta_boxes(){
    global $post;
    $slide = get_post_custom( $post->ID );
    $slide_image = $slide[ "_cinza_slide_images" ][ 0 ];
    $slide_content = $slide[ "_cinza_slide_contents" ][ 0 ];

	?>
	<div id="tab-login-content" class="cslider-tab-container">
		<div class="cslider-tab-section">
			<h3>Slide 1</h3>
			
			<div class="cslider-field">
				<label>Image</label>
				<input type="text" id="cinza_slide_X_image" name="_cinza_slide_images" value="<?php echo htmlspecialchars_decode( $slide_image ); ?>" />
			</div>
			
			<div class="cslider-field">
				<label>Content</label>
				<textarea id="cinza_slide_X_content" name="_cinza_slide_contents" rows="4" cols="50"><?php 
					echo htmlspecialchars_decode( $slide_content ); 
				?></textarea>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'save_post', 'save_cinza_slider_meta_boxes' );
function save_cinza_slider_meta_boxes(){
    global $post;
    
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( get_post_status( $post->ID ) === 'auto-draft' ) ) {
        return;
    }
    
    update_post_meta( $post->ID, "_cinza_slide_images", sanitize_text_field( $_POST[ "_cinza_slide_images" ] ) );
    update_post_meta( $post->ID, "_cinza_slide_contents", sanitize_text_field( $_POST[ "_cinza_slide_contents" ] ) );
}