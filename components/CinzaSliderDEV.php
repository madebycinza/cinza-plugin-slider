<?php	
	/*  Notes on how to deal with React js file in the plugin:

		In backend-shortcodes.php, enable only one:
		- Prod: wp_enqueue_script('cinza-slider');
		- Dev:  Include_once( CSLIDER_PATH . 'components/CinzaSliderDEV.php' );
				Enqueue Babel Standalone from https://unpkg.com/@babel/standalone/babel.min.js

		For production:
		- Need to precompile javascript with babeljs.io/repl/
		- Then copy the compiled version back to CinzaSlider.js
	*/

	wp_enqueue_script('babel', 'https://unpkg.com/@babel/standalone/babel.min.js');

	/*  
		https://rudrastyh.com/wordpress/rest-api-get-posts.html
		https://stackoverflow.com/questions/23740548/how-do-i-pass-variables-and-data-from-php-to-javascript
	*/

	$response = wp_remote_get( add_query_arg( array(
		'per_page' => 2
	), 'http://plugins.local/wp-json/wp/v2/cinza_slider' ) );

	if( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {
		$remote_posts = json_decode( $response['body'] );

		foreach( $remote_posts as $remote_post ) {
			echo '<h2>'. $remote_post->title->rendered . '</h2>';
			echo '<p>'. $remote_post->meta->_cinza_slide_images . '</p>';
			echo '<p>'. $remote_post->meta->_cinza_slide_contents . '</p>';
			//print_r( $remote_post ); // To see everything from the $response
		}
	}
?>

<script type="text/babel">
	class CinzaSlider extends React.Component {
		
		constructor(props) {
			super(props);
			this.state = {
				name: "Vinicius",
			};
		}
		
		render() {
			return (
				<div>
					<p>Are you okay with Cookies? {this.state.name}</p>
				</div>
			)
		}
	}
	
	ReactDOM.render(<CinzaSlider />, document.querySelector(".cslider-container"));
</script>