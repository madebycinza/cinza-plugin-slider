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
	

	$cslider_fields = get_post_meta('37', '_cslider_fields', true);
	$cs_images = array();
	$cs_contents = array();
	$i = 0;

	foreach ( $cslider_fields as $field ) {
		$cs_images[$i] = $field['url'];
		$cs_contents[$i] = $field['name'];
		$i++;
	}
	//echo json_encode($cs_images);
	//echo json_encode($cs_contents);
?>

<script type="text/babel">
	let images = <?php echo json_encode($cs_images); ?>;
	let contents = <?php echo json_encode($cs_contents); ?>;
	const delay = 5000;

	function Slideshow() {
	const [index, setIndex] = React.useState(0);
	const sliderCount = images.length - 1;
	const timeoutRef = React.useRef(null);

	function resetTimeout() {
		if (timeoutRef.current) {
		clearTimeout(timeoutRef.current);
		}
	}

	React.useEffect(() => {
		resetTimeout();
		timeoutRef.current = setTimeout(
		() =>
			setIndex((prevIndex) =>
				prevIndex === sliderCount ? 0 : prevIndex + 1
			),
		delay
		);

		return () => {
		resetTimeout();
		};
	}, [index]);

	return (
		<div className="slideshow">
		<div
			className="slideshowSlider"
			style={{ transform: `translate3d(${-index * 100}%, 0, 0)` }}
		>
			{images.map((image, index) => (
			<div
				className="slide"
				key={index}
				style={{'backgroundImage': `url(${image})`}}
			>
				{contents[index]}
			</div>
			))}
		</div>

		<div className="slideshowDots">
			{images.map((_, idx) => (
			<div
				key={idx}
				className={`slideshowDot${index === idx ? " active" : ""}`}
				onClick={() => {
					setIndex(idx);
				}}
			></div>
			))}
		</div>

		<div className="slideshowArrows">
			<div
				className='slideshowArrow slideshowArrowPrev'
				onClick={() => {
					setIndex(index === 0 ? sliderCount : index - 1);
				}}
			>Previous Slide</div>
			<div
				className='slideshowArrow slideshowArrowNext'
				onClick={() => {
					setIndex(index === sliderCount ? 0 : index + 1);
				}}
			>Next Slide</div>
		</div>
		</div>
	);
	}

	ReactDOM.render(<Slideshow />, document.getElementById("cslider-container"));
</script>

<style>

/* Slideshow */

.slideshow {
	margin: 0 auto;
	overflow: hidden;
	width: 100%;
}

.slideshowSlider {
	white-space: nowrap;
	transition: ease 1000ms;
}

.slide {
	display: inline-block;
	height: 400px;
	width: 100%;
	background-size: cover;
	background-position: center;
}

/* Buttons */

.slideshowDots {
	text-align: center;
}

.slideshowDot {
	display: inline-block;
	height: 20px;
	width: 20px;
	border-radius: 50%;
	cursor: pointer;
	margin: 15px 7px 0px;
	background-color: pink;
	transition: 0.3s ease-in-out;
}

.slideshowDot.active {
	background-color: red;
}

.slideshowArrows {
	display: flex;
    justify-content: space-between;
    align-items: center;
}

.slideshowArrow {
	display: inline-block;
    height: 100px;
    width: 100px;
	cursor: pointer;
	color: #FFF;
}

.slideshowArrowPrev {
    background: purple;
}

.slideshowArrowNext {
    background: blue;
}

</style>