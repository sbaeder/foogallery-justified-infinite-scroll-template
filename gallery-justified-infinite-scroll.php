<?php
/*
 * FooGallery Justified Infinite Scroll gallery template
 * This is the template that is run when a FooGallery shortcode is rendered to the frontend
 */

global $current_foogallery;				//the ID of the FooGallery currently being rendered to the frontend
global $current_foogallery_arguments;	//the current shortcode args  (so far, not used here)

/*
 * Create a local version of the function that will "filter" the thumbnails (i.e. we may not want dynamic 
 * regeneration of the thumbnails depending on the size of the originals since the cache will regenerate them fairly often
 * and that can slow things down significantly for large galleries).  Create the list in the admin code and store the
 * thumbnail name...Allow "Dynamic Regeneration" as one of the choices
 *
 * To "replace" the existing filter objects tagged as 'foogallery_attachment_resize_thumbnail', we save the filters 
 * and then restore them later. 
 */

//Get/Set the filter code from http://blog.wlindley.com/2012/08/temporarily-overriding-a-wordpress-filter/
if(!function_exists('justified_infinite_scroll_get_filter')) {
	function justified_infinite_scroll_get_filter($tag) {
	# Returns the current state of the given WordPress filter.
		global $wp_filter;
		return $wp_filter[$tag];
	}
};
if(!function_exists('justified_infinite_scroll_set_filter')) {
	function justified_infinite_scroll_set_filter($tag, $saved) {
		# Sets the given WordPress filter to a state saved by get_filter.
		remove_all_filters($tag);
		foreach ($saved as $priority => $func_list) {
			foreach ($func_list as $func_name => $func_args) {
				add_filter($tag,$func_args['function'], $priority, $func_args['accepted_args']);
			}
		}
	};
}; 

// Original filter code from foogallery includes/class-foogallery-attachment.php and includes/class-thumbnails.php
// Modified to return a pre-built thumbnail -OR- call WPThumb to dynamically create one.

if(!function_exists('justified_infinite_scroll_foogallery_attachment_resize_thumbnail')) {
	function justified_infinite_scroll_foogallery_attachment_resize_thumbnail( $original_image_src, $args, $thumbnail_object ) {
		// Some defaults for the dynamic generation...
		$arg_defaults = array(
			'thumb_size'              => 'large',
			'width'                   => 0,
			'height'                  => 0,
			'jpeg_quality'            => intval( foogallery_get_setting( 'thumb_jpeg_quality' ) ),
			'thumb_resize_animations' => foogallery_get_setting( 'thumb_resize_animations' )
		);
		$args = wp_parse_args( $args, $arg_defaults );
		$thumb_size = $args['thumb_size'];
		if ( $thumb_size != 'dynamic') { 		//check if we are going to use the thumbnails that we already have
			//check to make sure we have a valid ID, else return the original image
			if ( $thumbnail_object->ID > 0 ) {
				$thumbnail_attributes = wp_get_attachment_image_src( $thumbnail_object->ID, $thumb_size );
				return $thumbnail_attributes[0];
			} else {
				return $original_image_src ;
			}
		} else {
			// To do dynamic generation, we need either a width or a height. 
			//If nothing is given then default to the thumb width setting in Settings->Media
			$width  = (int)$args['width'];
			$height = (int)$args['height'];
			if ( 0 == $width && 0 == $height ) {
				$args['width'] = (int)get_option( 'thumbnail_size_w' );
			}	
			return wpthumb( $original_image_src, $args );
		};
	};
};

// First - save existing filter(s), and replace with our local version...
$was_filter = justified_infinite_scroll_get_filter('foogallery_attachment_resize_thumbnail');
remove_all_filters('foogallery_attachment_resize_thumbnail');
add_filter('foogallery_attachment_resize_thumbnail', 'justified_infinite_scroll_foogallery_attachment_resize_thumbnail', 99, 3);

// Now, initialize variables from the gallery settings
$height         = foogallery_gallery_template_setting( 'row_height', '150' );
$max_row_height = foogallery_gallery_template_setting( 'max_row_height', '125%' );
if ( strpos( $max_row_height, '%' ) !== false ) {		//convert pure number to a string value and account for a percentage
	$max_row_height = '"' . $max_row_height . '"';
};
$margins        = foogallery_gallery_template_setting( 'margins', '1' );
$caption_source = foogallery_gallery_template_setting( 'caption_source', 'title' );
if (foogallery_gallery_template_setting( 'caption_source', '' ) == 'none') { $captions = false; } else {	$captions = true; };
$thumb_size     = foogallery_gallery_template_setting( 'thumbnail_size', 'dynamic');
$thumb_link     = foogallery_gallery_template_setting( 'thumbnail_link', 'image' );
$lightbox       = foogallery_gallery_template_setting( 'lightbox', 'unknown' );
$page_mode      = foogallery_gallery_template_setting( 'advance-page-mode', 'page' );
$first_page     = foogallery_gallery_template_setting( 'first_page', '10' );
$per_page       = foogallery_gallery_template_setting( 'per_page', '10' );
$footer_offset  = foogallery_gallery_template_setting( 'footer_offset', '300' );
$total_count    = sizeof( $current_foogallery->attachments() );
// Set the args to be used to generate the html code (image links, etc.)
$args = array(
	'height' => $height,
	'link' => $thumb_link,
	'thumb_size' => $thumb_size
);

//future enhancement to allow setting this parameter...hard code for now.
$last_row = "justify" ;
$last_row = '"' . $last_row . '"';

?>
<div data-justified-options='{ "rowHeight": <?php echo $height; ?>, 
	"maxRowHeight": <?php echo $max_row_height; ?>, "margins": <?php echo $margins; ?>, 
	"captions": <?php echo $captions ? 'true' : 'false'; ?>, "lastrow": <?php echo $last_row; ?> }' 
	id="foogallery-gallery-<?php echo $current_foogallery->ID; ?>" 
	class="<?php echo foogallery_build_class_attribute( $current_foogallery, 'foogallery-lightbox-' . $lightbox, 'foogallery-justified-loading' ); ?>">
<?php 
	/* Output the first page of the picture thumbnails here in the html "div" to 
	 * make sure all the other css stuff is output, etc.  We will load dynamically
	 * all the rst of the pages in the /script stuff below.
	 */
	if ($first_page < 1) {$first_page = $total_count + 1 ;} //do them all if "0"
	if ($per_page < 1) {$per_page = $total_count + 1 ;}     //do them all if "0"
	$i = 0;

	foreach ( $current_foogallery->attachments() as $attachment ) {
		++$i;
		if ($i > $first_page) {break; };
		if ( 'title' == $caption_source ) {
			$attachment->alt = $attachment->title;
		} else if ( 'caption' == $caption_source ) {
			$attachment->alt = $attachment->caption;
		}
		if ($thumb_link == 'none') echo "<div>"; //no link means wrap in <div>
		echo $attachment->html( $args );
		if ($thumb_link == 'none') echo "</div>"; //no link means wrap in <div>
	}
?>
</div>
<?php
/* Note, to make the inifinte scrolling work right, we need a hidden place holder to put the html we will 
 * generate and add to the <div> we just created above that actually *is* the gallery
 * [idea was "lifted" from the smar-grid-galerry at http://topdevs.net/smart-grid-gallery/]
 */
?>
<div id="fg-load_more_holder-<?php echo $current_foogallery->ID; ?>" style="display:none"></div>
<?php
/*
 * For now, make it be a button to show more, but also be able to add scrolling...
 */
if ($page_mode == "page") {?>
	<button id="fg-load_more_button-<?php echo $current_foogallery->ID; 
	?>" style="margin-left:auto;margin-right:auto;display:block" class="read-more load-more button-square button-medium">Show More ...</button>
<?php }?>
<script type="text/javascript">
var galleries = [
<?php
		$i = 0;	$j = 0;
		foreach ( $current_foogallery->attachments() as $attachment ) {
			++$i;
			if ($i <= $first_page) {continue; };
			++$j;
			if ($j == 1) { //On a new "page"
				if ($i == ($first_page + 1)) { //Really the "first"" new page
					echo "'";
				}else {
					echo "' ,  '" ;
				};
			};
			if ( 'title' == $caption_source ) {
				$attachment->alt = $attachment->title;
			} else if ( 'caption' == $caption_source ) {
				$attachment->alt = $attachment->caption;
			}
			if ($thumb_link == 'none') echo esc_html("<div>"); //no link means wrap in <div>
			echo  esc_html ( $attachment->html( $args ) );
			if ($thumb_link == 'none') echo esc_html("</div>"); //no link means wrap in <div>
			if ($j == $per_page) { $j = 0; }	//reset the page counter for the next "group"
		};
		echo "'];" ;
?>
var this_gallery = "#foogallery-gallery-<?php echo $current_foogallery->ID; ?>";
<?php
if ($page_mode == "page") {
?>
jQuery("#fg-load_more_button-<?php echo $current_foogallery->ID; ?>" ).on('click', function() {
if (galleries.length >= 1) {
var images = galleries.splice(0, 1);
var image_html = jQuery("#fg-load_more_holder-<?php echo $current_foogallery->ID; ?>").html(images).text();
jQuery(this_gallery).append(image_html);
jQuery(this_gallery).justifiedGallery('norewind');
if (galleries.length == 0) {
jQuery("#fg-load_more_button-<?php echo $current_foogallery->ID; ?>").css('display', 'none');
}}});
<?php
} else {
?>
jQuery(window).scroll(function() {
if (galleries.length >= 1) {
var scroll_top = jQuery(window).scrollTop();
var scroll_bottom = scroll_top + jQuery(window).height();
var gallery_top = jQuery(this_gallery).offset().top;
var gallery_height = jQuery(this_gallery).innerHeight();
var gallery_bottom = gallery_top + gallery_height;
gallery_bottom = gallery_bottom - <?php echo $footer_offset; ?>;
if (gallery_bottom <= scroll_bottom) {
var images = galleries.splice(0, 1);
var image_html = jQuery("#fg-load_more_holder-<?php echo $current_foogallery->ID; ?>").html(images).text();
jQuery(this_gallery).append(image_html);
jQuery(this_gallery).justifiedGallery('norewind');
}}});
<?php
}
// Reset this filter to go back to the original
justified_infinite_scroll_set_filter('foogallery_attachment_resize_thumbnail', $was_filter );
?>
</script>
