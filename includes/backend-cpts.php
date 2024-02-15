<?php
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Register CPT: cslider
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action( 'init', 'cslider_register_post_type' );
function cslider_register_post_type() {
	$labels = [
		'name'                     => esc_html__( 'Cinza Sliders', 'your-textdomain' ),
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
		'menu_name'                => esc_html__( 'Cinza Slider', 'your-textdomain' ),
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
	    'exclude_from_search' => true,
	    'publicly_queryable'  => false,
	    'show_ui'             => true,
	    'show_in_nav_menus'   => false,
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
	    'rewrite'             => ['with_front' => false],
	];

	register_post_type( 'cinza_slider', $args );
}

add_filter( 'manage_cinza_slider_posts_columns', 'set_custom_edit_cslider_columns' );
function set_custom_edit_cslider_columns($columns) {
    $columns['shortcode'] = __( 'Shortcode', 'your_text_domain' );
    return $columns;
}

add_action( 'manage_cinza_slider_posts_custom_column' , 'custom_cslider_column', 10, 2 );
function custom_cslider_column( $column, $post_id ) {
	switch ( $column ) {
		case 'shortcode' :
			cslider_meta_box_shortcode($post_id);
			break;
	}
}

add_filter ( 'manage_cinza_slider_posts_columns', 'add_cslider_columns', 99, 99 );
function add_cslider_columns ( $columns ) {
	unset($columns['title']);
	unset($columns['shortcode']);
	unset($columns['date']);
	unset($columns['rank_math_seo_details']);
	unset($columns['rank_math_title']);
	unset($columns['rank_math_description']);

	return array_merge ( $columns, array ( 
		'title' => __ ('Title'),
		'shortcode' => __ ( 'Shortcode' ),
		'date' => __('Date')
	) );
}

add_filter( 'the_content', 'cslider_post_content');
function cslider_post_content ( $content ) {
    if ( is_singular('cinza_slider') ) {
        return do_shortcode('[cinzaslider id="'. get_the_ID() .'"]');
    }
    return $content;
}

// Remove UI for Custom Fields metabox
add_action( 'admin_head' , 'cslider_remove_post_custom_fields' );
function cslider_remove_post_custom_fields() {
    remove_meta_box( 'postcustom' , 'cinza_slider' , 'normal' ); 
}

// Remove CPT from SEO sitemap and set robots to noindex nofollow (for Rank Math SEO plugin)
if ( in_array( 'seo-by-rank-math/rank-math.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// https://rankmath.com/kb/make-theme-rank-math-compatible/#exclude-post-type-from-sitemap
	add_filter( 'rank_math/sitemap/exclude_post_type', function ($exclude, $type) {
	    if ($type === 'cinza_slider') {
	        $exclude = true;
	    }
	    return $exclude;
	}, 10, 2);	

	// https://support.rankmath.com/ticket/cpt-noindex/
	add_filter( 'rank_math/frontend/robots', function( $robots ) {
		if(get_post_type() == 'cinza_slider') {
			$robots['index'] = 'noindex';
			$robots['follow'] = 'nofollow';
		}
		return $robots;
	});
}

// [Possible future addition] Remove CPT from SEO sitemap (for Yoast SEO plugin)
// https://developer.yoast.com/features/xml-sitemaps/api/#exclude-specific-posts
// https://wordpress.org/support/topic/exclude-multiple-post-types-from-sitemap/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Add Meta Boxes
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action( 'add_meta_boxes', 'cslider_add_fields_meta_boxes', 99, 99 );
function cslider_add_fields_meta_boxes() {
	add_meta_box('cslider-options', 'Options', 'cslider_meta_box_options', 'cinza_slider', 'normal', 'default');
	add_meta_box('cslider-fields', 'Slider Cells', 'cslider_meta_box_display', 'cinza_slider', 'normal', 'default');
	add_meta_box('cslider-static', 'Static Layer', 'cslider_meta_box_static', 'cinza_slider', 'normal', 'default');
	add_meta_box('cslider-shortcode', 'Shortcode', 'cslider_meta_box_shortcode', 'cinza_slider', 'side', 'default');
	add_meta_box('cslider-documentation', 'Documentation', 'cslider_meta_box_doc', 'cinza_slider', 'side', 'default');
	add_meta_box('cslider-credits', 'Developers', 'cslider_meta_box_credits', 'cinza_slider', 'side', 'default');
	remove_meta_box( 'rank_math_metabox' , 'cinza_slider' , 'normal' ); 
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_options
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_options( $post ) {
	global $post;
    $cslider_options = get_post_meta( $post->ID, '_cslider_options', true );
	wp_nonce_field( 'cslider_meta_box_nonce', 'cslider_meta_box_nonce' );
	
	// Set default values
	$temp_minHeight = isset($cslider_options['cslider_minHeight']) ? esc_attr($cslider_options['cslider_minHeight']) : '300';
	$temp_maxHeight = isset($cslider_options['cslider_maxHeight']) ? esc_attr($cslider_options['cslider_maxHeight']) : '500';
	$temp_fullWidth = isset($cslider_options['cslider_fullWidth']) ? esc_attr($cslider_options['cslider_fullWidth']) : '0';
	$temp_setGallerySize = isset($cslider_options['cslider_setGallerySize']) ? esc_attr($cslider_options['cslider_setGallerySize']) : '1';
	$temp_adaptiveHeight = isset($cslider_options['cslider_adaptiveHeight']) ? esc_attr($cslider_options['cslider_adaptiveHeight']) : '1';
	
	$temp_prevNextButtons = isset($cslider_options['cslider_prevNextButtons']) ? esc_attr($cslider_options['cslider_prevNextButtons']) : '1';
	$temp_pageDots = isset($cslider_options['cslider_pageDots']) ? esc_attr($cslider_options['cslider_pageDots']) : '1';
	$temp_draggable = isset($cslider_options['cslider_draggable']) ? esc_attr($cslider_options['cslider_draggable']) : '1';
	$temp_hash = isset($cslider_options['cslider_hash']) ? esc_attr($cslider_options['cslider_hash']) : '0';
	$temp_mfAccessibility = isset($cslider_options['cslider_mfAccessibility']) ? esc_attr($cslider_options['cslider_mfAccessibility']) : '1';
	$temp_rfAccessibility = isset($cslider_options['cslider_rfAccessibility']) ? esc_attr($cslider_options['cslider_rfAccessibility']) : '0';
	
	$temp_animation = isset($cslider_options['cslider_animation']) ? esc_attr($cslider_options['cslider_animation']) : 'slide';
	$temp_autoPlay = isset($cslider_options['cslider_autoPlay']) ? esc_attr($cslider_options['cslider_autoPlay']) : '0';
	$temp_pauseAutoPlayOnHover = isset($cslider_options['cslider_pauseAutoPlayOnHover']) ? esc_attr($cslider_options['cslider_pauseAutoPlayOnHover']) : '1';
	$temp_wrapAround = isset($cslider_options['cslider_wrapAround']) ? esc_attr($cslider_options['cslider_wrapAround']) : '1';
	$temp_freeScroll = isset($cslider_options['cslider_freeScroll']) ? esc_attr($cslider_options['cslider_freeScroll']) : '0';
	
	$temp_groupCells = isset($cslider_options['cslider_groupCells']) ? esc_attr($cslider_options['cslider_groupCells']) : '1';
	$temp_cellAlign = isset($cslider_options['cslider_cellAlign']) ? esc_attr($cslider_options['cslider_cellAlign']) : 'left';
	$temp_imgFit = isset($cslider_options['cslider_imgFit']) ? esc_attr($cslider_options['cslider_imgFit']) : 'cover';
	$temp_resize = isset($cslider_options['cslider_resize']) ? esc_attr($cslider_options['cslider_resize']) : '1';
	$temp_contain = isset($cslider_options['cslider_contain']) ? esc_attr($cslider_options['cslider_contain']) : '1';
	$temp_percentPosition = isset($cslider_options['cslider_percentPosition']) ? esc_attr($cslider_options['cslider_percentPosition']) : '1';
	
	$temp_lazyLoad = isset($cslider_options['cslider_lazyLoad']) ? esc_attr($cslider_options['cslider_lazyLoad']) : '0';
	$temp_watchCSS = isset($cslider_options['cslider_watchCSS']) ? esc_attr($cslider_options['cslider_watchCSS']) : '0';
	$temp_dragThreshold = isset($cslider_options['cslider_dragThreshold']) ? esc_attr($cslider_options['cslider_dragThreshold']) : '3';
	$temp_selectedAttraction = isset($cslider_options['cslider_selectedAttraction']) ? esc_attr($cslider_options['cslider_selectedAttraction']) : '0.025';
	$temp_friction = isset($cslider_options['cslider_friction']) ? esc_attr($cslider_options['cslider_friction']) : '0.28';
	$temp_freeScrollFriction = isset($cslider_options['cslider_freeScrollFriction']) ? esc_attr($cslider_options['cslider_freeScrollFriction']) : '0.075';
	
	?>
	<table id="cslider-optionset" width="100%">
		<thead>
			<tr>
				<td class="cslider-options" colspan="3">
					<p>Size</p>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
            	<td class="cslider-options col-1">
                    <label for="cslider_minHeight">minHeight</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_minHeight" id="cslider_minHeight" class="cslider-minHeight" value="<?php echo esc_attr($temp_minHeight); ?>" /> <span>px</span>
                </td>
                <td class="cslider-options col-3">
					Sets the slider min-height in pixels.</em>
                </td>
            </tr>
			<tr>
            	<td class="cslider-options col-1">
                    <label for="cslider_maxHeight">maxHeight</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_maxHeight" id="cslider_maxHeight" class="cslider-maxHeight" value="<?php echo esc_attr($temp_maxHeight); ?>" /> <span>px</span>
                </td>
                <td class="cslider-options col-3">
					Sets the slider max-height in pixels.</em>
                </td>
            </tr>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_fullWidth">fullWidth</label>
				</td>
				<td class="cslider-options col-2">
					<input type="checkbox" name="cslider_fullWidth" id="cslider_fullWidth" class="widefat cslider-fullWidth" value="1" <?php checked('1', $temp_fullWidth); ?> />
				</td>
                <td class="cslider-options col-3">
					Force full width. <em>(<strong>Note: </strong>CSS only method. Might not work correctly depending on your setup)</em>
                </td>
			</tr>
			<tr class="height-setting-row">
				<td class="cslider-options col-1">
					<p><label for="cslider_setGallerySize">setGallerySize</label></p>
					<p><label for="cslider_adaptiveHeight">adaptiveHeight</label></p>
				</td>
				<td class="cslider-options col-2">
					<p><input type="checkbox" name="cslider_setGallerySize" id="cslider_setGallerySize" class="widefat cslider-setGallerySize" value="1" <?php checked('1', $temp_setGallerySize); ?> /></p>
					<p><input type="checkbox" name="cslider_adaptiveHeight" id="cslider_adaptiveHeight" class="widefat cslider-adaptiveHeight" value="1" <?php checked('1', $temp_adaptiveHeight); ?> /></p>
				</td>
                <td class="cslider-options col-3">
					<p><strong>setGallerySize OFF + adaptiveHeight OFF</strong><br />
					The height of the slider and all cells will be fixed equal to the value set in minHeight.</p>
					
					<p><strong>setGallerySize OFF + adaptiveHeight ON</strong><br />
					The height of the slider and all cells will be fixed equal to the value set in maxHeight.</p>
					
					<p><strong>setGallerySize ON + adaptiveHeight OFF</strong><br />
					The height of the slider will be fixed and equal to the height of the tallest cell, limited by minHeight and maxHeight.</p>
					
					<p><strong>setGallerySize ON + adaptiveHeight ON</strong><br />
					The height of the slider will be dynamic and change based on the height of the selected cell, limited by minHeight and maxHeight.</p>
                </td>
			</tr>
		</tbody>
	</table>

	<table id="cslider-optionset" width="100%">
		<thead>
			<tr>
				<td class="cslider-options" colspan="3">
					<p>Navigation</p>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_prevNextButtons">prevNextButtons</label>
				</td>
				<td class="cslider-options col-2">
					<input type="checkbox" name="cslider_prevNextButtons" id="cslider_prevNextButtons" class="widefat cslider-prevNextButtons" value="1" <?php checked('1', $temp_prevNextButtons); ?> />
				</td>
                <td class="cslider-options col-3">
                    Creates and enables previous & next buttons.
                </td>
			</tr>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_pageDots">pageDots</label>
				</td>
				<td class="cslider-options col-2">
					<input type="checkbox" name="cslider_pageDots" id="cslider_pageDots" class="widefat cslider-pageDots" value="1" <?php checked('1', $temp_pageDots); ?> />
				</td>
                <td class="cslider-options col-3">
                    Creates and enables page dots.
                </td>
			</tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_draggable">draggable</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_draggable" id="cslider_draggable" class="widefat cslider-draggable" value="1" <?php checked('1', $temp_draggable); ?> />
                </td>
                <td class="cslider-options col-3">
					Enables dragging and flicking. <em>(<strong>Note: </strong>Enabling this feature will make static layer unselectable)</em>
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_hash">hash</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_hash" id="cslider_hash" class="widefat cslider-hash" value="1" <?php checked('1', $temp_hash); ?> />
                </td>
                <td class="cslider-options col-3">
					Enables hash navigation. See Flickity documentation for more details.</em>
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_mfAccessibility">mfAccessibility</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_mfAccessibility" id="cslider_mfAccessibility" class="widefat cslider-mfAccessibility" value="1" <?php checked('1', $temp_mfAccessibility); ?> />
                </td>
                <td class="cslider-options col-3">
					Enables Metafizzy keyboard navigation. Users can tab to a slider, and pressing left & right keys to change cells.
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_rfAccessibility">rfAccessibility</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_rfAccessibility" id="cslider_rfAccessibility" class="widefat cslider-rfAccessibility" value="1" <?php checked('1', $temp_rfAccessibility); ?> />
                </td>
                <td class="cslider-options col-3">
					Enables Razorfrog keyboard navigation style and script. <em>(<strong>Note:</strong> Made for sliders that display one slide cell at a time)</em>
                </td>
            </tr>
		</tbody>
	</table>

	<table id="cslider-optionset" width="100%">
		<thead>
			<tr>
				<td class="cslider-options" colspan="3">
					<p>Transitions</p>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_animation">animation</label>
				</td>
				<td class="cslider-options col-2">
					<select name="cslider_animation" id="cslider_animation" class="cslider-animation">
						<option value="slide" <?php selected( $temp_animation, 'slide' ); ?>>Slide</option>
						<option value="fade" <?php selected( $temp_animation, 'fade' ); ?>>Fade</option>
					</select>
				</td>
                <td class="cslider-options col-3">
                    Slides or fades between transitioning. <em>Fade functionality uses the flickity-fade package.</em>
                </td>
			</tr>
			<tr>
                <td class="cslider-options col-1">
					<label for="cslider_autoPlay">autoPlay</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_autoPlay" id="cslider_autoPlay" class="cslider-autoPlay" value="<?php echo esc_attr($temp_autoPlay); ?>" /> <span>ms</span>
                </td>
                <td class="cslider-options col-3">
                    Automatically advances to the next cell. <em>Set value to zero to disable this option.</em>
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_pauseAutoPlayOnHover">pauseAutoPlayOnHover</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_pauseAutoPlayOnHover" id="cslider_pauseAutoPlayOnHover" class="widefat cslider-pauseAutoPlayOnHover" value="1" <?php checked('1', $temp_pauseAutoPlayOnHover); ?> />
                </td>
                <td class="cslider-options col-3">
                    Auto-playing will pause when the user hovers over the slider.
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_wrapAround">wrapAround</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_wrapAround" id="cslider_wrapAround" class="widefat cslider-wrapAround" value="1" <?php checked('1', $temp_wrapAround); ?> />
                </td>
                <td class="cslider-options col-3">
                    At the end of cells, wrap-around to the other end for infinite scrolling.
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_freeScroll">freeScroll</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_freeScroll" id="cslider_freeScroll" class="widefat cslider-freeScroll" value="1" <?php checked('1', $temp_freeScroll); ?> />
                </td>
                <td class="cslider-options col-3">
                    Enables content to be freely scrolled and flicked without aligning cells to an end position.
                </td>
            </tr>
		</tbody>
	</table>

	<table id="cslider-optionset" width="100%">
		<thead>
			<tr>
				<td class="cslider-options" colspan="3">
					<p>Cells</p>
				</td>
			</tr>
		</thead>
		<tbody>
            <tr>
                <td class="cslider-options col-1">
					<label for="cslider_groupCells">groupCells</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_groupCells" id="cslider_groupCells" class="cslider-groupCells" value="<?php echo esc_attr($temp_groupCells); ?>" />
                </td>
                <td class="cslider-options col-3">
                    Groups cells together in slides. Flicking, page dots, and previous/next buttons are mapped to group slides, not individual cells. is-selected class is added to the multiple cells in the selected slide.
                </td>
            </tr>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_cellAlign">cellAlign</label>
				</td>
				<td class="cslider-options col-2">
					<select name="cslider_cellAlign" id="cslider_cellAlign" class="cslider-cellAlign">
						<option value="center" <?php selected( $temp_cellAlign, 'center' ); ?>>Center</option>
						<option value="left" <?php selected( $temp_cellAlign, 'left' ); ?>>Left</option>
						<option value="right" <?php selected( $temp_cellAlign, 'right' ); ?>>Right</option>
					</select>
				</td>
                <td class="cslider-options col-3">
                    Align cells within the slider element.
                </td>
			</tr>

			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_imgFit">imgFit</label>
				</td>
				<td class="cslider-options col-2">
					<select name="cslider_imgFit" id="cslider_imgFit" class="cslider-imgFit">
						<option value="cover" <?php selected( $temp_imgFit, 'cover' ); ?>>Cover</option>
						<option value="contain" <?php selected( $temp_imgFit, 'contain' ); ?>>Contain</option>
					</select>
				</td>
                <td class="cslider-options col-3">
                    Align cells within the slider element.
                </td>
			</tr>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_resize">resize</label>
				</td>
				<td class="cslider-options col-2">
					<input type="checkbox" name="cslider_resize" id="cslider_resize" class="widefat cslider-resize" value="1" <?php checked('1', $temp_resize); ?> />
				</td>
                <td class="cslider-options col-3">
                    Adjusts sizes and positions when window is resized.
                </td>
			</tr>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_contain">contain</label>
				</td>
				<td class="cslider-options col-2">
					<input type="checkbox" name="cslider_contain" id="cslider_contain" class="widefat cslider-contain" value="1" <?php checked('1', $temp_contain); ?> />
				</td>
                <td class="cslider-options col-3">
                    Contains cells to slider element to prevent excess scroll at beginning or end. <em>Has no effect if wrapAround: true.</em>
                </td>
			</tr>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_percentPosition">percentPosition</label>
				</td>
				<td class="cslider-options col-2">
					<input type="checkbox" name="cslider_percentPosition" id="cslider_percentPosition" class="widefat cslider-percentPosition" value="1" <?php checked('1', $temp_percentPosition); ?> />
				</td>
                <td class="cslider-options col-3">
                    Sets positioning in percent values, rather than pixel values.
                </td>
			</tr>
		</tbody>
	</table>

	<table id="cslider-optionset" width="100%">
		<thead>
			<tr>
				<td class="cslider-options" colspan="3">
					<p>Advanced</p>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
                <td class="cslider-options col-1">
                    <label for="cslider_lazyLoad">lazyLoad</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_lazyLoad" id="cslider_lazyLoad" class="widefat cslider-lazyLoad" value="1" <?php checked('1', $temp_lazyLoad); ?> />
                </td>
                <td class="cslider-options col-3">
                    Loads cell images when a cell is selected.
                </td>
            </tr>
			<tr>
                <td class="cslider-options col-1">
                    <label for="cslider_watchCSS">watchCSS</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_watchCSS" id="cslider_watchCSS" class="widefat cslider-watchCSS" value="1" <?php checked('1', $temp_watchCSS); ?> />
                </td>
                <td class="cslider-options col-3">
                    You can enable and disable Flickity with CSS. watchCSS option watches the content of :after of the slider element. <em>Flickity is enabled if :after content is 'flickity'.</em>
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
					<label for="cslider_dragThreshold">dragThreshold</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_dragThreshold" id="cslider_dragThreshold" class="cslider-dragThreshold" value="<?php echo esc_attr($temp_dragThreshold); ?>" /> <span>px</span>
                </td>
                <td class="cslider-options col-3">
					The number of pixels a mouse or touch has to move before dragging begins. Increase dragThreshold to allow for more wiggle room for vertical page scrolling on touch devices. <em>Default dragThreshold: 3.</em>
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_selectedAttraction">selectedAttraction</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_selectedAttraction" id="cslider_selectedAttraction" class="cslider-selectedAttraction" value="<?php echo esc_attr($temp_selectedAttraction); ?>" />
                </td>
                <td class="cslider-options col-3">
                    selectedAttraction attracts the position of the slider to the selected cell. Higher attraction makes the slider move faster. Lower makes it move slower. <em>Default selectedAttraction: 0.025.</em>
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_friction">friction</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_friction" id="cslider_friction" class="cslider-friction" value="<?php echo esc_attr($temp_friction); ?>" />
                </td>
                <td class="cslider-options col-3">
                    friction slows the movement of slider. Higher friction makes the slider feel stickier and less bouncy. Lower friction makes the slider feel looser and more wobbly. <em>Default friction: 0.28.</em>
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_freeScrollFriction">freeScrollFriction</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_freeScrollFriction" id="cslider_freeScrollFriction" class="cslider-freeScrollFriction" value="<?php echo esc_attr($temp_freeScrollFriction); ?>" />
                </td>
                <td class="cslider-options col-3">
                    Slows movement of slider when freeScroll: true. Higher friction makes the slider feel stickier. Lower friction makes the slider feel looser. <em>Default freeScrollFriction: 0.075.</em>
                </td>
            </tr>
		</tbody>
	</table>
    <?php
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_fields
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_display() {
	global $post;
	$cslider_options = get_post_meta( $post->ID, '_cslider_options', true );
	$cslider_fields = get_post_meta($post->ID, '_cslider_fields', true);
	wp_nonce_field( 'cslider_meta_box_nonce', 'cslider_meta_box_nonce' );

	$id_count = rand(123, 321);
	if(isset($cslider_options['cslider_id_count'])) {
		$id_count = $cslider_options['cslider_id_count'];	
	}
	
	$temp_imgFit = 'cover';
	if (isset($cslider_options['cslider_imgFit'])) {
		$temp_imgFit = $cslider_options['cslider_imgFit'];
	}

	?>
	<input type="hidden" name="cslider_id_count" id="cslider_id_count" value="<?php echo esc_attr($id_count); ?>" readonly />
	<table id="cslider-fieldset" class="cslider-fieldset-sortable" width="100%">
		<tbody><?php
			$preview_placeholder = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/preview-placeholder.jpg';
			if ( $cslider_fields ) {
				$count = 0;
				foreach ( $cslider_fields as $field ) {
					$count++; 
					
					$existing_cell_id = '';
					if(isset($field['cslider_cell_id'])) {
						$existing_cell_id = $field['cslider_cell_id'];	
					}

					?>
					<tr class="slide-row">
						<td class="cslider-preview">
							<?php 
								$cslider_img_url = '';
								$cslider_img_preview = '';
								if (!empty( esc_attr($field['cslider_img_id']) )) {
									$cslider_img_url = wp_get_attachment_image_src( $attachment_id = esc_attr($field['cslider_img_id']), $size = 'full')[0];
									$cslider_img_preview = wp_get_attachment_image_src( $attachment_id = esc_attr($field['cslider_img_id']), $size = 'large')[0];
								}
							?>
							<label>Preview</label>
							<div class="cslider-img-preview" style="background-image: url('<?php echo esc_attr($preview_placeholder); ?>');">
								<?php if(empty($cslider_img_preview)) { ?> 
									<div class="cslider-img-preview-inner"></div> <?php
								} else { ?>
									<div class="cslider-img-preview-inner" style="background-image: url('<?php echo esc_attr($cslider_img_preview); ?>'); background-color: #f6f7f7; background-size: <?php echo esc_attr($temp_imgFit); ?>;"></div><?php
								} ?>
							</div>
							<label>Slide ID</label>
							<input type="text" class="cslider_cell_id" name="cslider_cell_id[]" value="<?php echo $existing_cell_id; ?>" readonly />
							<div class="cslider-buttons">
								<a class="button move-slide" href="#/"><span class="icon icon-move"></span>Move</a>
								<a class="button delete-slide" href="#/"><span class="icon icon-bin"></span>Delete</a>
							</div>
						</td>
						<td class="cslider-content">
							<label>Image</label>
							<div class="img-details">
								<input type="text" class="widefat cslider-img-url" name="cslider_img_url[]" value="<?php echo esc_attr($cslider_img_url); ?>" readonly />
								<a class="button remove-img" href="#/"><span class="icon icon-cross"></span></a>
								<input type="text" class="widefat cslider-img-id" name="cslider_img_id[]" value="<?php echo esc_attr( $field['cslider_img_id'] ); ?>" />
								<input type="button" class="button button-primary cslider-img-btn" value="Select Image" />
							</div>
							<label>Content</label>
							<textarea type="text" class="widefat cslider-content" name="cslider_content[]"><?php echo esc_html( $field['cslider_content'] ); ?></textarea>
							<label>Link</label>
							<div class="link-details">
								<input type="text" class="widefat cslider-link" name="cslider_link[]" value="<?php echo esc_attr( $field['cslider_link'] ); ?>" />
								<select name="cslider_link_target[]" class="cslider-link-target">
									<option value="same" <?php selected( $field['cslider_link_target'], 'same' ); ?>>Open in same tab</option>
									<option value="new" <?php selected( $field['cslider_link_target'], 'new' ); ?>>Open in new tab</option>
								</select>
							</div>
						</td>
					</tr><?php
				}
			} else { 
				?>
				<!-- show a blank one -->
				<tr class="slide-row">
					<td class="cslider-preview">
						<label>Preview</label>
						<div class="cslider-img-preview" style="background-image: url('<?php echo esc_attr($preview_placeholder); ?>');">
							<div class="cslider-img-preview-inner" style="background-image: url(); background-size: <?php echo esc_attr($temp_imgFit); ?>;"></div>
						</div>
						<label>Slide ID</label>
						<input type="text" class="cslider_cell_id" name="cslider_cell_id[]" value="<?php echo "slider-cell-" . esc_attr($id_count); ?>" readonly />
						<div class="cslider-buttons">
							<a class="button move-slide" href="#/"><span class="icon icon-move"></span>Move</a>
							<a class="button delete-slide" href="#/"><span class="icon icon-bin"></span>Delete</a>
						</div>
					</td>
					<td class="cslider-content">
						<label>Image</label>
						<div class="img-details">
							<input type="text" class="widefat cslider-img-url" name="cslider_img_url[]" readonly />
							<a class="button remove-img" href="#/"><span class="icon icon-cross"></span></a>
							<input type="text" class="widefat cslider-img-id" name="cslider_img_id[]" />
							<input type="button" class="button button-primary cslider-img-btn" value="Select Image" />
						</div>
						<label>Content</label>
						<textarea type="text" class="widefat cslider-content" name="cslider_content[]"></textarea>
						<label>Link</label>
						<div class="link-details">
							<input type="text" class="widefat cslider-link" name="cslider_link[]" />
							<select name="cslider_link_target[]" class="cslider-link-target">
								<option value="same">Open in same tab</option>
								<option value="new">Open in new tab</option>
							</select>
						</div>
					</td>
				</tr><?php 
			} ?>
			
			<!-- empty hidden one for jQuery -->
			<tr class="empty-row screen-reader-text slide-row">
				<td class="cslider-preview">
					<label>Preview</label>
					<div class="cslider-img-preview" style="background-image: url('<?php echo esc_attr($preview_placeholder); ?>');">
						<div class="cslider-img-preview-inner" style="background-image: url(); background-size: <?php echo esc_attr($temp_imgFit); ?>;"></div>
					</div>
					<label>Slide ID</label>
					<input type="text" class="cslider_cell_id" name="cslider_cell_id[]" value="" readonly />
					<div class="cslider-buttons">
						<a class="button move-slide" href="#/"><span class="icon icon-move"></span>Move</a>
						<a class="button delete-slide" href="#/"><span class="icon icon-bin"></span>Delete</a>
					</div>
				</td>
				<td class="cslider-content">
					<label>Image</label>
					<div class="img-details">
						<input type="text" class="widefat cslider-img-url" name="cslider_img_url[]" readonly />
						<a class="button remove-img" href="#/"><span class="icon icon-cross"></span></a>
						<input type="text" class="widefat cslider-img-id" name="cslider_img_id[]" />
						<input type="button" class="button button-primary cslider-img-btn" value="Select Image" />
					</div>
					<label>Content</label>
					<textarea type="text" class="widefat cslider-content" name="cslider_content[]"></textarea>
					<label>Link</label>
					<div class="link-details">
						<input type="text" class="widefat cslider-link" name="cslider_link[]" />
						<select name="cslider_link_target[]" class="cslider-link-target">
							<option value="same">Open in same tab</option>
							<option value="new">Open in new tab</option>
						</select>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	
	<p id="add-slide-p"><a id="add-slide" class="button button-primary" href="#"><span class="icon icon-cross"></span> Add slide</a></p>
	<?php
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_static
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_static() {
	global $post;
    $cslider_static = get_post_meta( $post->ID, '_cslider_static', true );
	wp_nonce_field( 'cslider_meta_box_nonce', 'cslider_meta_box_nonce' );

	// Set default values
	$temp_static_content = '';
	$temp_static_overlay = '';
	$temp_static_gradient = '';
	
	// Get saved values
	if ( !empty($cslider_static) ) {
		$temp_static_content = isset($cslider_static['cslider_static_content']) ? esc_attr($cslider_static['cslider_static_content']) : '';
		$temp_static_overlay = isset($cslider_static['cslider_static_overlay']) ? esc_attr($cslider_static['cslider_static_overlay']) : '';
		$temp_static_gradient = isset($cslider_static['cslider_static_gradient']) ? esc_attr($cslider_static['cslider_static_gradient']) : '0';
	}

	?>
	<table id="cslider-fieldset" width="100%">
		<tbody>
			<tr class="slide-static">
				<td class="cslider-content">
					<label>Content</label>
					<textarea type="text" class="widefat cslider-content" name="cslider_static_content"><?php echo esc_html($temp_static_content); ?></textarea>
					
					<label>Overlay Color</label>
					<input type="text" class="widefat cslider-overlay" name="cslider_static_overlay" value="<?php echo esc_attr($temp_static_overlay); ?>" placeholder="rgba(0, 0, 0, 0.3)" />
					
					<div class="cslider-static-gradient-checkbox">
						<input type="checkbox" name="cslider_static_gradient" id="cslider_static_gradient" value="1" <?php checked('1', $temp_static_gradient); ?> />
						<label for="cslider_static_gradient">Enable browser compatibility for gradients</label>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_shortcode
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_shortcode( $post ) {
	$slider_SC = '[cinzaslider id=&quot;'. get_the_ID() .'&quot;]';
	$slider_ID = 'cinza-slider-' . get_the_ID();
	
	?>
	<div class="cslider_shortcode_copy">
		<input type="text" value="<?php echo $slider_SC; ?>" class="cslider_shortcode_copy_input" id="<?php echo $slider_ID; ?>" readonly />
		<a class="preview button button-primary" onclick="cslider_copy_shortcode('<?php echo $slider_ID; ?>')"><span class="icon icon-edit-copy"></span> Copy</a>
	</div>
	<?php
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_doc
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_doc( $post ) {
	?><a href="https://flickity.metafizzy.co/options.html" target="_blank" class="preview button">Metafizzy Flickity doc</a><?php
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_doc
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_credits( $post ) {
	$metafizzy_logo = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/metafizzy-icon.png';
	$cinza_logo = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/cinza-icon-pink.png';
	$razorfrog_logo = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/razorfrog-icon-turquoise.png';
	
	?><a href="https://metafizzy.co/" class="button" target="_blank">
		<img src="<?php echo $metafizzy_logo; ?>" />
		<span>Metafizzy</span>
	</a>
	<a href="https://cinza.io/" class="button" target="_blank">
		<img src="<?php echo $cinza_logo; ?>" />
		<span>Cinza</span>
	</a>
	<a href="https://razorfrog.com/" class="button" target="_blank">
		<img src="<?php echo $razorfrog_logo; ?>" />
		<span>Razorfrog</span>
	</a><?php
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Save Meta Boxes
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action('save_post', 'cslider_save_fields_meta_boxes');
function cslider_save_fields_meta_boxes($post_id) {
	if ( ! isset( $_POST['cslider_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['cslider_meta_box_nonce'], 'cslider_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;

	// Get all _cslider_options from fields
	$cslider_minHeight 			  = isset($_POST['cslider_minHeight']) ? sanitize_text_field($_POST['cslider_minHeight']) : '';
	$cslider_maxHeight 			  = isset($_POST['cslider_maxHeight']) ? sanitize_text_field($_POST['cslider_maxHeight']) : '';
	$cslider_fullWidth 			  = isset($_POST['cslider_fullWidth']) ? sanitize_key($_POST['cslider_fullWidth']) : '';
	$cslider_setGallerySize 	  = isset($_POST['cslider_setGallerySize']) ? sanitize_key($_POST['cslider_setGallerySize']) : '';
	$cslider_adaptiveHeight 	  = isset($_POST['cslider_adaptiveHeight']) ? sanitize_key($_POST['cslider_adaptiveHeight']) : '';
	
	$cslider_prevNextButtons 	  = isset($_POST['cslider_prevNextButtons']) ? sanitize_key($_POST['cslider_prevNextButtons']) : '';
	$cslider_pageDots 			  = isset($_POST['cslider_pageDots']) ? sanitize_key($_POST['cslider_pageDots']) : '';
	$cslider_draggable 			  = isset($_POST['cslider_draggable']) ? sanitize_key($_POST['cslider_draggable']) : '';
	$cslider_hash	 			  = isset($_POST['cslider_hash']) ? sanitize_key($_POST['cslider_hash']) : '';
	$cslider_mfAccessibility	  = isset($_POST['cslider_mfAccessibility']) ? sanitize_key($_POST['cslider_mfAccessibility']) : '';
	$cslider_rfAccessibility	  = isset($_POST['cslider_rfAccessibility']) ? sanitize_key($_POST['cslider_rfAccessibility']) : '';
	
	$cslider_animation 			  = isset($_POST['cslider_animation']) ? sanitize_text_field($_POST['cslider_animation']) : '';
	$cslider_autoPlay 			  = isset($_POST['cslider_autoPlay']) ? sanitize_text_field($_POST['cslider_autoPlay']) : '';
	$cslider_pauseAutoPlayOnHover = isset($_POST['cslider_pauseAutoPlayOnHover']) ? sanitize_key($_POST['cslider_pauseAutoPlayOnHover']) : '';
	$cslider_wrapAround 		  = isset($_POST['cslider_wrapAround']) ? sanitize_key($_POST['cslider_wrapAround']) : '';
	$cslider_freeScroll 		  = isset($_POST['cslider_freeScroll']) ? sanitize_key($_POST['cslider_freeScroll']) : '';
	
	$cslider_groupCells 		  = isset($_POST['cslider_groupCells']) ? sanitize_text_field($_POST['cslider_groupCells']) : '';
	$cslider_cellAlign 			  = isset($_POST['cslider_cellAlign']) ? sanitize_text_field($_POST['cslider_cellAlign']) : '';
	$cslider_imgFit 			  = isset($_POST['cslider_imgFit']) ? sanitize_text_field($_POST['cslider_imgFit']) : '';
	$cslider_resize 			  = isset($_POST['cslider_resize']) ? sanitize_key($_POST['cslider_resize']) : '';
	$cslider_contain 			  = isset($_POST['cslider_contain']) ? sanitize_key($_POST['cslider_contain']) : '';
	$cslider_percentPosition 	  = isset($_POST['cslider_percentPosition']) ? sanitize_key($_POST['cslider_percentPosition']) : '';
	
	$cslider_lazyLoad 			  = isset($_POST['cslider_lazyLoad']) ? sanitize_key($_POST['cslider_lazyLoad']) : '';
	$cslider_watchCSS 			  = isset($_POST['cslider_watchCSS']) ? sanitize_key($_POST['cslider_watchCSS']) : '';
	$cslider_dragThreshold 		  = isset($_POST['cslider_dragThreshold']) ? sanitize_text_field($_POST['cslider_dragThreshold']) : '';
	$cslider_selectedAttraction   = isset($_POST['cslider_selectedAttraction']) ? sanitize_text_field($_POST['cslider_selectedAttraction']) : '';
	$cslider_friction 			  = isset($_POST['cslider_friction']) ? sanitize_text_field($_POST['cslider_friction']) : '';
	$cslider_freeScrollFriction   = isset($_POST['cslider_freeScrollFriction']) ? sanitize_text_field($_POST['cslider_freeScrollFriction']) : '';

	$cslider_id_count 			  = isset($_POST['cslider_id_count']) ? sanitize_text_field($_POST['cslider_id_count']) : '';

	// Validations before saving
	$new_options = array();
	$new_options['cslider_minHeight'] = empty($cslider_minHeight) ? '0' : wp_strip_all_tags($cslider_minHeight);
	$new_options['cslider_maxHeight'] = empty($cslider_maxHeight) ? '0' : wp_strip_all_tags($cslider_maxHeight);
	$new_options['cslider_fullWidth'] = $cslider_fullWidth ? '1' : '0';
	$new_options['cslider_setGallerySize'] = $cslider_setGallerySize ? '1' : '0';
	$new_options['cslider_adaptiveHeight'] = $cslider_adaptiveHeight ? '1' : '0';
	
	$new_options['cslider_prevNextButtons'] = $cslider_prevNextButtons ? '1' : '0';
	$new_options['cslider_pageDots'] = $cslider_pageDots ? '1' : '0';
	$new_options['cslider_draggable'] = $cslider_draggable ? '1' : '0';
	$new_options['cslider_hash'] = $cslider_hash ? '1' : '0';
	$new_options['cslider_mfAccessibility'] = $cslider_mfAccessibility ? '1' : '0';
	$new_options['cslider_rfAccessibility'] = $cslider_rfAccessibility ? '1' : '0';
	
	$new_options['cslider_animation'] = wp_strip_all_tags($cslider_animation);
	$new_options['cslider_autoPlay'] = empty($cslider_autoPlay) ? '0' : wp_strip_all_tags($cslider_autoPlay);
	$new_options['cslider_pauseAutoPlayOnHover'] = $cslider_pauseAutoPlayOnHover ? '1' : '0';
	$new_options['cslider_wrapAround'] = $cslider_wrapAround ? '1' : '0';
	$new_options['cslider_freeScroll'] = $cslider_freeScroll ? '1' : '0';
	
	$new_options['cslider_groupCells'] = empty($cslider_groupCells) ? '1' : wp_strip_all_tags($cslider_groupCells);
	$new_options['cslider_cellAlign'] = wp_strip_all_tags($cslider_cellAlign);
	$new_options['cslider_imgFit'] = wp_strip_all_tags($cslider_imgFit);
	$new_options['cslider_resize'] = $cslider_resize ? '1' : '0';
	$new_options['cslider_contain'] = $cslider_contain ? '1' : '0';
	$new_options['cslider_percentPosition'] = $cslider_percentPosition ? '1' : '0';
	
	$new_options['cslider_lazyLoad'] = $cslider_lazyLoad ? '1' : '0';
	$new_options['cslider_watchCSS'] = $cslider_watchCSS ? '1' : '0';
	$new_options['cslider_dragThreshold'] = empty($cslider_dragThreshold) ? '3' : wp_strip_all_tags($cslider_dragThreshold);
	$new_options['cslider_selectedAttraction'] = empty($cslider_selectedAttraction) ? '0.025' : wp_strip_all_tags($cslider_selectedAttraction);
	$new_options['cslider_friction'] = empty($cslider_friction) ? '0.28' : wp_strip_all_tags($cslider_friction);
	$new_options['cslider_freeScrollFriction'] = empty($cslider_freeScrollFriction) ? '0.075' : wp_strip_all_tags($cslider_freeScrollFriction);

	// Get all _cslider_fields
	$cslider_cells_id = isset($_POST['cslider_cell_id']) ? $_POST['cslider_cell_id'] : '';
	$cslider_imgs_id = isset($_POST['cslider_img_id']) ? $_POST['cslider_img_id'] : '';
	$cslider_contents = isset($_POST['cslider_content']) ? $_POST['cslider_content'] : '';
	$cslider_links = isset($_POST['cslider_link']) ? $_POST['cslider_link'] : '';
	$cslider_link_targets = isset($_POST['cslider_link_target']) ? $_POST['cslider_link_target'] : '';

	$new_fields = array();
	$old_fields = get_post_meta($post_id, '_cslider_fields', true);
	$count_imgs = count($cslider_imgs_id);
	$count_contents = count($cslider_contents);

	if ($count_imgs > $count_contents) {
		$count = $count_imgs;
	} else {
		$count = $count_contents;
	}

	for ( $i = 0; $i < $count; $i++ ) {
		if ( $cslider_imgs_id[$i] != '' || $cslider_contents[$i] != '' ) :
			$new_fields[$i]['cslider_cell_id'] = empty($cslider_cells_id[$i]) ? 'slider-cell-'.++$cslider_id_count : sanitize_text_field( $cslider_cells_id[$i] );
			$new_fields[$i]['cslider_img_id'] = sanitize_text_field( $cslider_imgs_id[$i] );
			$new_fields[$i]['cslider_content'] = wp_filter_post_kses( $cslider_contents[$i] );
			$new_fields[$i]['cslider_link'] = esc_url_raw( $cslider_links[$i] );
			$new_fields[$i]['cslider_link_target'] = sanitize_text_field( $cslider_link_targets[$i] );
		endif;
	}
	
	// Save _cslider_options
	$new_options['cslider_id_count'] = wp_strip_all_tags($cslider_id_count);
	update_post_meta($post_id, '_cslider_options', $new_options);
	
	// Save _cslider_fields
	if ( !empty( $new_fields ) && $new_fields != $old_fields )
		update_post_meta( $post_id, '_cslider_fields', $new_fields );
	elseif ( empty($new_fields) && $old_fields )
		delete_post_meta( $post_id, '_cslider_fields', $old_fields );

	// Save _cslider_static
	$cslider_static_content = isset($_POST['cslider_static_content']) ? wp_filter_post_kses($_POST['cslider_static_content']) : '';
	$cslider_static_overlay = isset($_POST['cslider_static_overlay']) ? sanitize_text_field($_POST['cslider_static_overlay']) : '';
	$cslider_static_gradient = isset($_POST['cslider_static_gradient']) ? sanitize_key($_POST['cslider_static_gradient']) : '';

	$new = array();
	$new['cslider_static_content'] = $cslider_static_content;
	$new['cslider_static_overlay'] = $cslider_static_overlay;
	$new['cslider_static_gradient'] = $cslider_static_gradient ? '1' : '0';

	update_post_meta($post_id, '_cslider_static', $new);
}

?>