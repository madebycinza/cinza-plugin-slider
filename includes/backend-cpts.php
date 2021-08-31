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
// Add Meta Boxex
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action('add_meta_boxes', 'cslider_add_fields_meta_boxes');
function cslider_add_fields_meta_boxes() {
	add_meta_box('cslider-options', 'Cinza Slider Options', 'cslider_meta_box_options', 'cinza_slider', 'normal', 'default');
	add_meta_box( 'cslider-fields', 'Cinza Slider Fields', 'cslider_meta_box_display', 'cinza_slider', 'normal', 'default');
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_settings
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_options( $post ) {
	global $post;
    $cslider_options = get_post_meta( $post->ID, '_cslider_options', true );
	wp_nonce_field( 'cslider_meta_box_nonce', 'cslider_meta_box_nonce' );
	
    ?>
	<table id="cslider-optionset" width="100%">
		<tbody>
			<tr>
				<td class="cslider-heading">
					<p class="no-margin-top">Behavior</p>
				</td>
			</tr>
            <tr>
                <td class="cslider-options">
                    <input type="checkbox" name="cslider_draggable" id="cslider_draggable" class="widefat cslider-draggable" value="1" <?php checked('1', $cslider_options['cslider_draggable']); ?> />
				</td>
				<td class="cslider-options">
                    <label for="cslider_draggable">draggable</label>
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
                    <input type="checkbox" name="cslider_freeScroll" id="cslider_freeScroll" class="widefat cslider-freeScroll" value="1" <?php checked('1', $cslider_options['cslider_freeScroll']); ?> />
                    <label for="cslider_freeScroll">freeScroll</label>
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
                    <input type="checkbox" name="cslider_wrapAround" id="cslider_wrapAround" class="widefat cslider-wrapAround" value="1" <?php checked('1', $cslider_options['cslider_wrapAround']); ?> />
                    <label for="cslider_wrapAround">wrapAround</label>
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
					<label for="cslider_groupCells">groupCells</label>
                    <input type="text" name="cslider_groupCells" id="cslider_groupCells" class="cslider-groupCells" value="<?php echo($cslider_options['cslider_groupCells']); ?>" />
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
					<label for="cslider_autoPlay">autoPlay</label>
                    <input type="text" name="cslider_autoPlay" id="cslider_autoPlay" class="cslider-autoPlay" value="<?php echo($cslider_options['cslider_autoPlay']); ?>" /> <span>milliseconds</span>
                </td>
            </tr>
			<tr>
				<td class="cslider-options">
					<label for="cslider_animation">animation</label>
					<select name="cslider_animation" id="cslider_animation" class="cslider-animation">
						<option value="slide" <?php selected( $cslider_options['cslider_animation'], 'slide' ); ?>>Slide</option>
						<option value="fade" <?php selected( $cslider_options['cslider_animation'], 'fade' ); ?>>Fade</option>
					</select>
				</td>
			</tr>
            <tr>
                <td class="cslider-options">
                    <input type="checkbox" name="cslider_pauseAutoPlayOnHover" id="cslider_pauseAutoPlayOnHover" class="widefat cslider-pauseAutoPlayOnHover" value="1" <?php checked('1', $cslider_options['cslider_pauseAutoPlayOnHover']); ?> />
                    <label for="cslider_pauseAutoPlayOnHover">pauseAutoPlayOnHover</label>
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
                    <input type="checkbox" name="cslider_adaptiveHeight" id="cslider_adaptiveHeight" class="widefat cslider-adaptiveHeight" value="1" <?php checked('1', $cslider_options['cslider_adaptiveHeight']); ?> />
                    <label for="cslider_adaptiveHeight">adaptiveHeight</label>
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
                    <input type="checkbox" name="cslider_watchCSS" id="cslider_watchCSS" class="widefat cslider-watchCSS" value="1" <?php checked('1', $cslider_options['cslider_watchCSS']); ?> />
                    <label for="cslider_watchCSS">watchCSS</label>
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
					<label for="cslider_dragThreshold">dragThreshold</label>
                    <input type="text" name="cslider_dragThreshold" id="cslider_dragThreshold" class="cslider-dragThreshold" value="<?php echo($cslider_options['cslider_dragThreshold']); ?>" /> <span>px</span>
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
                    <label for="cslider_selectedAttraction">selectedAttraction</label>
                    <input type="text" name="cslider_selectedAttraction" id="cslider_selectedAttraction" class="cslider-selectedAttraction" value="<?php echo($cslider_options['cslider_selectedAttraction']); ?>" />
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
                    <label for="cslider_friction">friction</label>
                    <input type="text" name="cslider_friction" id="cslider_friction" class="cslider-friction" value="<?php echo($cslider_options['cslider_friction']); ?>" />
                </td>
            </tr>
            <tr>
                <td class="cslider-options">
                    <label for="cslider_freeScrollFriction">freeScrollFriction</label>
                    <input type="text" name="cslider_freeScrollFriction" id="cslider_freeScrollFriction" class="cslider-freeScrollFriction" value="<?php echo($cslider_options['cslider_freeScrollFriction']); ?>" />
                </td>
            </tr>
			<tr>
				<td class="cslider-heading">
					<p>Images</p>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<input type="checkbox" name="cslider_lazyLoad" id="cslider_lazyLoad" class="widefat cslider-lazyLoad" value="1" <?php checked('1', $cslider_options['cslider_lazyLoad']); ?> />
					<label for="cslider_lazyLoad">lazyLoad</label>
				</td>
			</tr>
			<tr>
				<td class="cslider-heading">
					<p>Setup</p>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<input type="checkbox" name="cslider_setGallerySize" id="cslider_setGallerySize" class="widefat cslider-setGallerySize" value="1" <?php checked('1', $cslider_options['cslider_setGallerySize']); ?> />
					<label for="cslider_setGallerySize">setGallerySize</label>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<input type="checkbox" name="cslider_resize" id="cslider_resize" class="widefat cslider-resize" value="1" <?php checked('1', $cslider_options['cslider_resize']); ?> />
					<label for="cslider_resize">resize</label>
				</td>
			</tr>
			<tr>
				<td class="cslider-heading">
					<p>Cell</p>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<label for="cslider_cellAlign">cellAlign</label>
					<select name="cslider_cellAlign" id="cslider_cellAlign" class="cslider-cellAlign">
						<option value="center" <?php selected( $cslider_options['cslider_cellAlign'], 'center' ); ?>>Center</option>
						<option value="left" <?php selected( $cslider_options['cslider_cellAlign'], 'left' ); ?>>Left</option>
						<option value="right" <?php selected( $cslider_options['cslider_cellAlign'], 'right' ); ?>>Right</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<input type="checkbox" name="cslider_contain" id="cslider_contain" class="widefat cslider-contain" value="1" <?php checked('1', $cslider_options['cslider_contain']); ?> />
					<label for="cslider_contain">contain</label>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<input type="checkbox" name="cslider_percentPosition" id="cslider_percentPosition" class="widefat cslider-percentPosition" value="1" <?php checked('1', $cslider_options['cslider_percentPosition']); ?> />
					<label for="cslider_percentPosition">percentPosition</label>
				</td>
			</tr>
			<tr>
				<td class="cslider-heading">
					<p>UI</p>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<input type="checkbox" name="cslider_prevNextButtons" id="cslider_prevNextButtons" class="widefat cslider-prevNextButtons" value="1" <?php checked('1', $cslider_options['cslider_prevNextButtons']); ?> />
					<label for="cslider_prevNextButtons">prevNextButtons</label>
				</td>
			</tr>
			<tr>
				<td class="cslider-options">
					<input type="checkbox" name="cslider_pageDots" id="cslider_pageDots" class="widefat cslider-pageDots" value="1" <?php checked('1', $cslider_options['cslider_pageDots']); ?> />
					<label for="cslider_pageDots">pageDots</label>
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
	$cslider_fields = get_post_meta($post->ID, '_cslider_fields', true);
	wp_nonce_field( 'cslider_meta_box_nonce', 'cslider_meta_box_nonce' );

	?>
	<table id="cslider-fieldset" width="100%">
		<tbody><?php
			$icon_sort = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/icon-sort.png';
			$icon_remove = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/icon-remove.png';

			if ( $cslider_fields ) :
				foreach ( $cslider_fields as $field ) {?>
					<tr>
						<td class="cslider-fields">
							<label>Image URL</label>
							<input type="text" class="widefat cslider-img-url" name="cslider_url[]" value="<?php if ($field['cslider_url'] != '') echo esc_attr( $field['cslider_url'] ); else echo 'http://'; ?>" />
							<label>Content</label>
							<textarea type="text" class="widefat cslider-content" name="name[]" rows="4" cols="50"><?php if($field['name'] != '') echo esc_attr( $field['name'] ); ?></textarea>
						</td>
						<td class="cslider-buttons">
							<a class="button button-primary sort-row" href="#"><img src="<?php echo $icon_sort; ?>" alt="Sort Row" /></a>
							<a class="button button-primary remove-row" href="#"><img src="<?php echo $icon_remove; ?>" alt="Remove Row" /></a>
						</td>
					</tr><?php
				}
			else : ?>
			
			<!-- show a blank one -->
				<tr>
					<td class="cslider-fields">
						<label>Image URL</label>
						<input type="text" class="widefat cslider-img-url" name="cslider_url[]" />
						<label>Content</label>
						<textarea type="text" class="widefat cslider-content" name="name[]" rows="4" cols="50"></textarea>
					</td>
					<td class="cslider-buttons">
						<a class="button button-primary sort-row" href="#"><img src="<?php echo $icon_sort; ?>" alt="Sort Row" /></a>
						<a class="button button-primary remove-row" href="#"><img src="<?php echo $icon_remove; ?>" alt="Remove Row" /></a>
					</td>
				</tr><?php 
			endif; ?>
			
			<!-- empty hidden one for jQuery -->
			<tr class="empty-row screen-reader-text">
				<td class="cslider-fields">
					<label>Image URL</label>
					<input type="text" class="widefat cslider-img-url" name="cslider_url[]" />
					<label>Content</label>
					<textarea type="text" class="widefat cslider-content" name="name[]" rows="4" cols="50"></textarea>
				</td>
				<td class="cslider-buttons">
					<a class="button button-primary sort-row" href="#"><img src="<?php echo $icon_sort; ?>" alt="Sort Row" /></a>
					<a class="button button-primary remove-row" href="#"><img src="<?php echo $icon_remove; ?>" alt="Remove Row" /></a>
				</td>
			</tr>
		</tbody>
	</table>
	
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
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

	// Save _cslider_options
	$cslider_draggable = $_POST['cslider_draggable'];
	$cslider_freeScroll = $_POST['cslider_freeScroll'];
	$cslider_wrapAround = $_POST['cslider_wrapAround'];
	$cslider_groupCells = $_POST['cslider_groupCells'];
	$cslider_autoPlay = $_POST['cslider_autoPlay'];
	$cslider_animation = $_POST['cslider_animation'];
	$cslider_pauseAutoPlayOnHover = $_POST['cslider_pauseAutoPlayOnHover'];
	$cslider_adaptiveHeight = $_POST['cslider_adaptiveHeight'];
	$cslider_watchCSS = $_POST['cslider_watchCSS'];
	$cslider_dragThreshold = $_POST['cslider_dragThreshold'];
	$cslider_selectedAttraction = $_POST['cslider_selectedAttraction'];
	$cslider_friction = $_POST['cslider_friction'];
	$cslider_freeScrollFriction = $_POST['cslider_freeScrollFriction'];
	$cslider_lazyLoad = $_POST['cslider_lazyLoad'];
	$cslider_setGallerySize = $_POST['cslider_setGallerySize'];
	$cslider_resize = $_POST['cslider_resize'];
	$cslider_cellAlign = $_POST['cslider_cellAlign'];
	$cslider_contain = $_POST['cslider_contain'];
	$cslider_percentPosition = $_POST['cslider_percentPosition'];
	$cslider_prevNextButtons = $_POST['cslider_prevNextButtons'];
	$cslider_pageDots = $_POST['cslider_pageDots'];

	$new = array();
	$new['cslider_draggable'] = $cslider_draggable ? '1' : '0';
	$new['cslider_freeScroll'] = $cslider_freeScroll ? '1' : '0';
	$new['cslider_wrapAround'] = $cslider_wrapAround ? '1' : '0';
	$new['cslider_groupCells'] = $cslider_groupCells;
	$new['cslider_autoPlay'] = $cslider_autoPlay;
	$new['cslider_animation'] = $cslider_animation;
	$new['cslider_pauseAutoPlayOnHover'] = $cslider_pauseAutoPlayOnHover ? '1' : '0';
	$new['cslider_adaptiveHeight'] = $cslider_adaptiveHeight ? '1' : '0';
	$new['cslider_watchCSS'] = $cslider_watchCSS ? '1' : '0';
	$new['cslider_dragThreshold'] = $cslider_dragThreshold;
	$new['cslider_selectedAttraction'] = $cslider_selectedAttraction;
	$new['cslider_friction'] = $cslider_friction;
	$new['cslider_freeScrollFriction'] = $cslider_freeScrollFriction;
	$new['cslider_lazyLoad'] = $cslider_lazyLoad ? '1' : '0';
	$new['cslider_setGallerySize'] = $cslider_setGallerySize;
	$new['cslider_resize'] = $cslider_resize;
	$new['cslider_cellAlign'] = $cslider_cellAlign;
	$new['cslider_contain'] = $cslider_contain ? '1' : '0';
	$new['cslider_percentPosition'] = $cslider_percentPosition ? '1' : '0';
	$new['cslider_prevNextButtons'] = $cslider_prevNextButtons ? '1' : '0';
	$new['cslider_pageDots'] = $cslider_pageDots ? '1' : '0';

	update_post_meta($post_id, '_cslider_options', $new);

	// Save _cslider_fields
	$cslider_urls = $_POST['cslider_url'];
	$contents = $_POST['name'];

	$new = array();
	$old = get_post_meta($post_id, '_cslider_fields', true);
	$count = count( $cslider_urls );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $cslider_urls[$i] != '' ) :
			$new[$i]['cslider_url'] = stripslashes( $cslider_urls[$i] );
			$new[$i]['name'] = stripslashes( strip_tags( $contents[$i] ) );
		endif;
	}

	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, '_cslider_fields', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, '_cslider_fields', $old );
}

?>