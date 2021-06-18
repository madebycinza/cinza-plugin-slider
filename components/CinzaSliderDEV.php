<?php
	
// Enqueue Babel Standalone - FOR DEV ONLY
wp_enqueue_script('babel', 'https://unpkg.com/@babel/standalone/babel.min.js');

?><script type="text/babel">
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
</script><?php






// https://rudrastyh.com/wordpress/rest-api-get-posts.html
// https://stackoverflow.com/questions/23740548/how-do-i-pass-variables-and-data-from-php-to-javascript

$response = wp_remote_get( add_query_arg( array(
	'per_page' => 2
), 'http://plugins.local/wp-json/wp/v2/posts' ) );

if( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {

	$remote_posts = json_decode( $response['body'] ); // our posts are here
	foreach( $remote_posts as $remote_post ) {

		// display post titles and excerpts
		echo '<h2>'. $remote_post->title->rendered . '</h2><p>' . $remote_post->excerpt->rendered . '</p>';
		// need more parameters? print_r( $remote_post )
		
	}
}	
?>