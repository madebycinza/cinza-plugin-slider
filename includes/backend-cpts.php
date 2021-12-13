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
		'rewrite'             => ['with_front' => false],
	];

	register_post_type( 'cinza_slider', $args );
}


add_filter( 'manage_cinza_slider_posts_columns', 'set_custom_edit_cinza_slider_columns' );
function set_custom_edit_cinza_slider_columns($columns) {
    $columns['shortcode'] = __( 'Shortcode', 'your_text_domain' );
    return $columns;
}

add_action( 'manage_cinza_slider_posts_custom_column' , 'custom_cinza_slider_column', 10, 2 );
function custom_cinza_slider_column( $column, $post_id ) {
	switch ( $column ) {
		case 'shortcode' :
			echo('[cinza_slider id="'.$post_id.'"]');
			break;
	}
}

add_filter ( 'manage_cinza_slider_posts_columns', 'add_cinza_slider_columns' );
function add_cinza_slider_columns ( $columns ) {
	unset($columns['title']);
	unset($columns['shortcode']);
	unset($columns['date']);

	return array_merge ( $columns, array ( 
		'title' => __ ('Title'),
		'shortcode' => __ ( 'Shortcode' ),
		'date' => __('Date')
	) );
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Add Meta Boxex
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

add_action('add_meta_boxes', 'cslider_add_fields_meta_boxes');
function cslider_add_fields_meta_boxes() {
	add_meta_box('cslider-options', 'Options', 'cslider_meta_box_options', 'cinza_slider', 'normal', 'default');
	add_meta_box('cslider-fields', 'Slider Cells', 'cslider_meta_box_display', 'cinza_slider', 'normal', 'default');
	add_meta_box('cslider-static', 'Static Layer', 'cslider_meta_box_static', 'cinza_slider', 'normal', 'default');
	add_meta_box('cslider-documentation', 'Documentation', 'cslider_meta_box_doc', 'cinza_slider', 'side', 'default');
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_options
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_options( $post ) {
	global $post;
    $cslider_options = get_post_meta( $post->ID, '_cslider_options', true );
	wp_nonce_field( 'cslider_meta_box_nonce', 'cslider_meta_box_nonce' );
	
	// Set default values
	$temp_minHeight = 300;
	$temp_maxHeight = 500;
	$temp_fullWidth = 0;
	$temp_setGallerySize = 1;
	$temp_adaptiveHeight = 1;
	$temp_prevNextButtons = 1;
	$temp_pageDots = 1;
	$temp_draggable = 1;
	$temp_animation = 'slide';
	$temp_autoPlay = '0';
	$temp_pauseAutoPlayOnHover = 1;
	$temp_wrapAround = 1;
	$temp_freeScroll = 0;
	$temp_groupCells = '1';
	$temp_cellAlign = 'center';
	$temp_resize = 1;
	$temp_contain = 1;
	$temp_percentPosition = 1;
	$temp_watchCSS = 0;
	$temp_dragThreshold = '3';
	$temp_selectedAttraction = '0.025';
	$temp_friction = '0.28';
	$temp_freeScrollFriction = '0.075';
	
	// Get saved values
	if ( !empty($cslider_options) ) {
		$temp_minHeight = esc_attr($cslider_options['cslider_minHeight']);
		$temp_maxHeight = esc_attr($cslider_options['cslider_maxHeight']);
		$temp_fullWidth = $cslider_options['cslider_fullWidth'];
		$temp_setGallerySize = $cslider_options['cslider_setGallerySize'];
		$temp_adaptiveHeight = $cslider_options['cslider_adaptiveHeight'];
		$temp_draggable = $cslider_options['cslider_draggable'];
		$temp_prevNextButtons = $cslider_options['cslider_prevNextButtons'];
		$temp_pageDots = $cslider_options['cslider_pageDots'];
		$temp_animation = $cslider_options['cslider_animation'];
		$temp_autoPlay = esc_attr($cslider_options['cslider_autoPlay']);
		$temp_pauseAutoPlayOnHover = $cslider_options['cslider_pauseAutoPlayOnHover'];
		$temp_wrapAround = $cslider_options['cslider_wrapAround'];
		$temp_freeScroll = $cslider_options['cslider_freeScroll'];
		$temp_groupCells = esc_attr($cslider_options['cslider_groupCells']);
		$temp_cellAlign = $cslider_options['cslider_cellAlign'];
		$temp_resize = $cslider_options['cslider_resize'];
		$temp_contain = $cslider_options['cslider_contain'];
		$temp_percentPosition = $cslider_options['cslider_percentPosition'];
		$temp_watchCSS = $cslider_options['cslider_watchCSS'];
		$temp_dragThreshold = esc_attr($cslider_options['cslider_dragThreshold']);
		$temp_selectedAttraction = esc_attr($cslider_options['cslider_selectedAttraction']);
		$temp_friction = esc_attr($cslider_options['cslider_friction']);
		$temp_freeScrollFriction = esc_attr($cslider_options['cslider_freeScrollFriction']);
	}

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
                    <input type="text" name="cslider_minHeight" id="cslider_minHeight" class="cslider-minHeight" value="<?php echo $temp_minHeight; ?>" /> <span>px</span>
                </td>
                <td class="cslider-options col-3">
					Manually sets the slider min-height in pixels. Set value to zero to disable this option.
                </td>
            </tr>
			<tr>
            	<td class="cslider-options col-1">
                    <label for="cslider_maxHeight">maxHeight</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_maxHeight" id="cslider_maxHeight" class="cslider-maxHeight" value="<?php echo $temp_maxHeight; ?>" /> <span>px</span>
                </td>
                <td class="cslider-options col-3">
					Manually sets the slider max-height in pixels. Set value to zero to disable this option.
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
					Force full width.
                </td>
			</tr>
			<tr>
				<td class="cslider-options col-1">
					<label for="cslider_setGallerySize">setGallerySize</label>
				</td>
				<td class="cslider-options col-2">
					<input type="checkbox" name="cslider_setGallerySize" id="cslider_setGallerySize" class="widefat cslider-setGallerySize" value="1" <?php checked('1', $temp_setGallerySize); ?> />
				</td>
                <td class="cslider-options col-3">
					Sets the height of the carousel to the height of the tallest cell.
                </td>
			</tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_adaptiveHeight">adaptiveHeight</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_adaptiveHeight" id="cslider_adaptiveHeight" class="widefat cslider-adaptiveHeight" value="1" <?php checked('1', $temp_adaptiveHeight); ?> />
                </td>
                <td class="cslider-options col-3">
                    Changes height of carousel to fit height of selected slide.
                </td>
            </tr>
		</tbody>
	</table>

	<table id="cslider-optionset" width="100%">
		<thead>
			<tr>
				<td class="cslider-options" colspan="3">
					<p>Controls</p>
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
                    Creates and enables previous & next buttons. Enabled by default prevNextButtons: true.
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
                    Creates and enables page dots. Enabled by default pageDots: true.
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
					Enables dragging and flicking. Enabled by default when carousel has 2 or more slides draggable: '>1'.
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
                    Slides or fades between transitioning. Fade functionality uses the flickity-fade package.
                </td>
			</tr>
			<tr>
                <td class="cslider-options col-1">
					<label for="cslider_autoPlay">autoPlay</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_autoPlay" id="cslider_autoPlay" class="cslider-autoPlay" value="<?php echo $temp_autoPlay; ?>" /> <span>ms</span>
                </td>
                <td class="cslider-options col-3">
                    Automatically advances to the next cell. Set value to zero to disable this option.
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
                    Auto-playing will pause when the user hovers over the carousel.
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
                    <input type="text" name="cslider_groupCells" id="cslider_groupCells" class="cslider-groupCells" value="<?php echo $temp_groupCells; ?>" />
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
                    Align cells within the carousel element.
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
                    Adjusts sizes and positions when window is resized. Enabled by default resize: true.
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
                    Contains cells to carousel element to prevent excess scroll at beginning or end. Has no effect if wrapAround: true.
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
                    Sets positioning in percent values, rather than pixel values. If your cells do not have percent widths, we recommended percentPosition: false.
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
                    <label for="cslider_watchCSS">watchCSS</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="checkbox" name="cslider_watchCSS" id="cslider_watchCSS" class="widefat cslider-watchCSS" value="1" <?php checked('1', $temp_watchCSS); ?> />
                </td>
                <td class="cslider-options col-3">
                    You can enable and disable Flickity with CSS. watchCSS option watches the content of :after of the carousel element. Flickity is enabled if :after content is 'flickity'.
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
					<label for="cslider_dragThreshold">dragThreshold</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_dragThreshold" id="cslider_dragThreshold" class="cslider-dragThreshold" value="<?php echo $temp_dragThreshold; ?>" /> <span>px</span>
                </td>
                <td class="cslider-options col-3">
					The number of pixels a mouse or touch has to move before dragging begins. Increase dragThreshold to allow for more wiggle room for vertical page scrolling on touch devices. Default dragThreshold: 3.
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_selectedAttraction">selectedAttraction</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_selectedAttraction" id="cslider_selectedAttraction" class="cslider-selectedAttraction" value="<?php echo $temp_selectedAttraction; ?>" />
                </td>
                <td class="cslider-options col-3">
                    selectedAttraction attracts the position of the slider to the selected cell. Higher attraction makes the slider move faster. Lower makes it move slower. Default selectedAttraction: 0.025.
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_friction">friction</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_friction" id="cslider_friction" class="cslider-friction" value="<?php echo $temp_friction; ?>" />
                </td>
                <td class="cslider-options col-3">
                    friction slows the movement of slider. Higher friction makes the slider feel stickier and less bouncy. Lower friction makes the slider feel looser and more wobbly. Default friction: 0.28.
                </td>
            </tr>
            <tr>
                <td class="cslider-options col-1">
                    <label for="cslider_freeScrollFriction">freeScrollFriction</label>
				</td>
				<td class="cslider-options col-2">
                    <input type="text" name="cslider_freeScrollFriction" id="cslider_freeScrollFriction" class="cslider-freeScrollFriction" value="<?php echo $temp_freeScrollFriction; ?>" />
                </td>
                <td class="cslider-options col-3">
                    Slows movement of slider when freeScroll: true. Higher friction makes the slider feel stickier. Lower friction makes the slider feel looser. Default freeScrollFriction: 0.075.
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
			$icon_sort = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/icon-move.png';
			$icon_delete = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/icon-delete.png';
			$icon_remove = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/icon-remove.png';
			$preview_placeholder = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/preview-placeholder.jpg';

			if ( $cslider_fields ) {
				$count = 0;
				foreach ( $cslider_fields as $field ) {
					$count++; ?>
					<tr class="slide-row">
						<td class="cslider-preview">
							<?php 
								if (!empty( esc_attr($field['cslider_img_id']) )) {
									$cslider_img_url = wp_get_attachment_image_src( $attachment_id = esc_attr($field['cslider_img_id']), $size = 'full')[0];
									$cslider_img_preview = wp_get_attachment_image_src( $attachment_id = esc_attr($field['cslider_img_id']), $size = 'large')[0];
								}
							?>
							<label>Preview</label>
							<div class="cslider-img-preview" style="background-image: url('<?php echo $preview_placeholder; ?>');">
								<div class="cslider-img-preview-inner" style="background-image: url('<?php echo $cslider_img_preview; ?>');"></div>
							</div>
							<div class="cslider-buttons">
								<a class="button move-slide" href="#/"><img src="<?php echo $icon_sort; ?>" alt="Move Slide" />Move</a>
								<a class="button delete-slide" href="#/"><img src="<?php echo $icon_delete; ?>" alt="Delete Slide" />Delete</a>
							</div>
						</td>
						<td class="cslider-content">
							<label>Image</label>
							<div class="img-details">
								<input type="text" class="widefat cslider-img-url" name="cslider_img_url[]" value="<?php echo $cslider_img_url; ?>" readonly />
								<a class="button remove-img" href="#/"><img src="<?php echo $icon_remove; ?>" alt="Remove Image" /></a>
								<input type="text" class="widefat cslider-img-id" name="cslider_img_id[]" value="<?php echo esc_attr( $field['cslider_img_id'] ); ?>" />
								<input type="button" class="button button-primary cslider-img-btn" value="Select Image" />
							</div>
							<label>Content</label>
							<textarea type="text" class="widefat cslider-content" name="cslider_content[]"><?php echo esc_attr( $field['cslider_content'] ); ?></textarea>
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
						<div class="cslider-img-preview" style="background-image: url('<?php echo $preview_placeholder; ?>');">
							<div class="cslider-img-preview-inner" style="background-image: url();"></div>
						</div>
						<div class="cslider-buttons">
							<a class="button move-slide" href="#"><img src="<?php echo $icon_sort; ?>" alt="Move Slide" />Move</a>
							<a class="button delete-slide" href="#"><img src="<?php echo $icon_delete; ?>" alt="Delete Slide" />Delete</a>
						</div>
					</td>
					<td class="cslider-content">
						<label>Image</label>
						<div class="img-details">
							<input type="text" class="widefat cslider-img-url" name="cslider_img_url[]" readonly />
							<a class="button remove-img" href="#"><img src="<?php echo $icon_remove; ?>" alt="Remove Image" /></a>
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
					<div class="cslider-img-preview" style="background-image: url('<?php echo $preview_placeholder; ?>');">
						<div class="cslider-img-preview-inner" style="background-image: url();"></div>
					</div>
					<div class="cslider-buttons">
						<a class="button move-slide" href="#"><img src="<?php echo $icon_sort; ?>" alt="Move Slide" />Move</a>
						<a class="button delete-slide" href="#"><img src="<?php echo $icon_delete; ?>" alt="Delete Slide" />Delete</a>
					</div>
				</td>
				<td class="cslider-content">
					<label>Image</label>
					<div class="img-details">
						<input type="text" class="widefat cslider-img-url" name="cslider_img_url[]" readonly />
						<a class="button remove-img" href="#"><img src="<?php echo $icon_remove; ?>" alt="Remove Image" /></a>
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
	<p id="add-slide-p"><a id="add-slide" class="button button-primary" href="#">Add slide</a></p>
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
	
	// Get saved values
	if ( !empty($cslider_static) ) {
		$temp_static_content = esc_attr($cslider_static['cslider_static_content']);
		$temp_static_overlay = esc_attr($cslider_static['cslider_static_overlay']);
	}

	?>
	<table id="cslider-fieldset" width="100%">
		<tbody>
			<tr class="slide-static">
				<td class="cslider-content">
					<label>Content</label>
					<textarea type="text" class="widefat cslider-content" name="cslider_static_content"><?php echo $temp_static_content; ?></textarea>
					<label>Overlay Color</label>
					<input type="text" class="widefat cslider-overlay" name="cslider_static_overlay" value="<?php echo $temp_static_overlay; ?>" />
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Meta Box: _cslider_doc
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cslider_meta_box_doc( $post ) {
	?><a href="https://flickity.metafizzy.co/options.html" target="_blank" class="preview button">Flickity documentation</a><?php
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
	$cslider_minHeight = $_POST['cslider_minHeight'];
	$cslider_maxHeight = $_POST['cslider_maxHeight'];
	$cslider_fullWidth = $_POST['cslider_fullWidth'];
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
	$cslider_setGallerySize = $_POST['cslider_setGallerySize'];
	$cslider_resize = $_POST['cslider_resize'];
	$cslider_cellAlign = $_POST['cslider_cellAlign'];
	$cslider_contain = $_POST['cslider_contain'];
	$cslider_percentPosition = $_POST['cslider_percentPosition'];
	$cslider_prevNextButtons = $_POST['cslider_prevNextButtons'];
	$cslider_pageDots = $_POST['cslider_pageDots'];

	$new = array();
	$new['cslider_minHeight'] = empty($cslider_minHeight) ? '0' : wp_strip_all_tags($cslider_minHeight);
	$new['cslider_maxHeight'] = empty($cslider_maxHeight) ? '0' : wp_strip_all_tags($cslider_maxHeight);
	$new['cslider_fullWidth'] = $cslider_fullWidth ? '1' : '0';
	$new['cslider_draggable'] = $cslider_draggable ? '1' : '0';
	$new['cslider_freeScroll'] = $cslider_freeScroll ? '1' : '0';
	$new['cslider_wrapAround'] = $cslider_wrapAround ? '1' : '0';
	$new['cslider_groupCells'] = empty($cslider_groupCells) ? '1' : wp_strip_all_tags($cslider_groupCells);
	$new['cslider_autoPlay'] = empty($cslider_autoPlay) ? '0' : wp_strip_all_tags($cslider_autoPlay);
	$new['cslider_animation'] = wp_strip_all_tags($cslider_animation);
	$new['cslider_pauseAutoPlayOnHover'] = $cslider_pauseAutoPlayOnHover ? '1' : '0';
	$new['cslider_adaptiveHeight'] = $cslider_adaptiveHeight ? '1' : '0';
	$new['cslider_watchCSS'] = $cslider_watchCSS ? '1' : '0';
	$new['cslider_dragThreshold'] = empty($cslider_dragThreshold) ? '3' : wp_strip_all_tags($cslider_dragThreshold);
	$new['cslider_selectedAttraction'] = empty($cslider_selectedAttraction) ? '0.025' : wp_strip_all_tags($cslider_selectedAttraction);
	$new['cslider_friction'] = empty($cslider_friction) ? '0.28' : wp_strip_all_tags($cslider_friction);
	$new['cslider_freeScrollFriction'] = empty($cslider_freeScrollFriction) ? '0.075' : wp_strip_all_tags($cslider_freeScrollFriction);
	$new['cslider_setGallerySize'] = $cslider_setGallerySize ? '1' : '0';
	$new['cslider_resize'] = $cslider_resize ? '1' : '0';
	$new['cslider_cellAlign'] = wp_strip_all_tags($cslider_cellAlign);
	$new['cslider_contain'] = $cslider_contain ? '1' : '0';
	$new['cslider_percentPosition'] = $cslider_percentPosition ? '1' : '0';
	$new['cslider_prevNextButtons'] = $cslider_prevNextButtons ? '1' : '0';
	$new['cslider_pageDots'] = $cslider_pageDots ? '1' : '0';

	update_post_meta($post_id, '_cslider_options', $new);

	// Save _cslider_fields
	$cslider_imgs_id = $_POST['cslider_img_id'];
	$cslider_contents = $_POST['cslider_content'];
	$cslider_links = $_POST['cslider_link'];
	$cslider_link_targets = $_POST['cslider_link_target'];

	$new = array();
	$old = get_post_meta($post_id, '_cslider_fields', true);
	$count_imgs = count($cslider_imgs);
	$count_contents = count($cslider_contents);

	if ($count_imgs > $count_contents) $count = $count_imgs;
	else $count = $count_contents;

	for ( $i = 0; $i < $count; $i++ ) {
		if ( $cslider_imgs_id[$i] != '' || $cslider_contents[$i] != '' ) :
			$new[$i]['cslider_img_id'] = stripslashes( $cslider_imgs_id[$i] );
			$new[$i]['cslider_content'] = stripslashes( $cslider_contents[$i] );
			$new[$i]['cslider_link'] = stripslashes( $cslider_links[$i] );
			$new[$i]['cslider_link_target'] = stripslashes( $cslider_link_targets[$i] );
		endif;
	}

	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, '_cslider_fields', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, '_cslider_fields', $old );

	// Save _cslider_static
	$cslider_static_content = $_POST['cslider_static_content'];
	$cslider_static_overlay = $_POST['cslider_static_overlay'];

	$new = array();
	$new['cslider_static_content'] = $cslider_static_content;
	$new['cslider_static_overlay'] = $cslider_static_overlay;

	update_post_meta($post_id, '_cslider_static', $new);
}

?>