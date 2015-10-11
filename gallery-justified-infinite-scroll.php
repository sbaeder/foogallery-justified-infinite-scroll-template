<?php
/**
 * FooGallery Justified Infinite Scroll gallery template
 * This is the template that is run when a FooGallery shortcode is rendered to the frontend
 */

global $current_foogallery;	//the ID of the FooGallery currently being rendered to the frontend
global $current_foogallery_arguments;	//the current shortcode args  (so far, not used here)

$page_mode      = foogallery_gallery_template_setting( 'advance-page-mode', 'page' );
$first_page     = foogallery_gallery_template_setting( 'first_page', '10' );
$per_page       = foogallery_gallery_template_setting( 'per_page', '10' );
$footer_offset  = foogallery_gallery_template_setting( 'footer_offset', '300' );

$height         = foogallery_gallery_template_setting( 'row_height', '150' );
$max_row_height = foogallery_gallery_template_setting( 'max_row_height', '125%' );
$margins        = foogallery_gallery_template_setting( 'margins', '1' );
$captions       = foogallery_gallery_template_setting( 'captions', '' ) == 'on';
$gutter_width   = foogallery_gallery_template_setting( 'gutter_width', '10' );
$thumb_link     = foogallery_gallery_template_setting( 'thumbnail_link', 'image' );
$lightbox       = foogallery_gallery_template_setting( 'lightbox', 'unknown' );
$caption_source = foogallery_gallery_template_setting( 'caption_source', 'title' );
$total_count    = sizeof( $current_foogallery->attachments() );

if ( strpos( $max_row_height, '%' ) !== false ) {
	$max_row_height = '"' . $max_row_height . '"';
};
//none doesn't work well inside the justified gallery...(bug inside default template)
//so for now, reset to "custom"
if ($thumb_link == 'none') {
 	$args = array(
		'height' => $height,
		'link' => 'custom',
		'custom_link' => '#',
	);
	
} else {
	$args = array(
		'height' => $height,
		'link' => $thumb_link
	);
	
};

?>

<div data-justified-options='{ "rowHeight": <?php echo $height; ?>, 
	"maxRowHeight": <?php echo $max_row_height; ?>, 
	"margins": <?php echo $margins; ?>, 
	"captions": <?php echo $captions ? 'true' : 'false'; ?> }' 
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
		echo $attachment->html( $args );
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
	<button id="fg-load_more_button-<?php echo $current_foogallery->ID; ?>" class="read-more load-more button-square button-medium">More ...</button>
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
			echo  esc_html ( $attachment->html( $args ) );
			if ($j == $per_page) { $j = 0; }	//reset the page counter for the next "group"
		};
		echo "'];" ;
		?>

		var this_gallery = "#foogallery-gallery-<?php echo $current_foogallery->ID; ?>";
<?php
if ($page_mode == "page") {
?>		jQuery("#fg-load_more_button-<?php echo $current_foogallery->ID; ?>" ).on('click', function() {
            if (galleries.length >= 1) {
                var images = galleries.splice(0, 1);
                var image_html = jQuery("#fg-load_more_holder-<?php echo $current_foogallery->ID; ?>").html(images).text();
                jQuery(this_gallery).append(image_html);
                jQuery(this_gallery).justifiedGallery('norewind');
                if (galleries.length == 0) {
                    jQuery("#fg-load_more_button-<?php echo $current_foogallery->ID; ?>").css('display', 'none');
                }
            }
        });
<?php
} else {
?>		jQuery(window).scroll(function() {
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
				}
			}
		});
<?php
}
?>
</script>
