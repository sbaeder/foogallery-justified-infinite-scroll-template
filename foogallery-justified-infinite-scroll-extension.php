<?php
/**
 * FooGallery Justified Infinite Scroll Extension
 *
 * Use the Justified formating, but page images based on specified page limit
 *
 * @package   Justified_Infinite_Scroll_Template_FooGallery_Extension
 * @author    Scott Baeder
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Scott Baeder
 *
 * @wordpress-plugin
 * Plugin Name: FooGallery - Justified Infinite Scroll
 * Description: Uses the original Justified template and js engine, but adds "infinite scroll" functionality based on specified page limits
 * Version:     1.0.1
 * Author:      Scott Baeder
 * Author URI:  
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* ORIGINAL INFORMATION
 * 
 * FooGallery
 *
 * The Most Intuitive and Extensible Gallery Creation and Management Tool Ever Created for WordPress.
 *
 * -package   FooGallery
 * -author    Brad Vincent <brad@fooplugins.com>
 * -license   GPL-2.0+
 * -link      https://github.com/fooplugins/foogallery
 * -copyright 2013 FooPlugins LLC
 *
 * -wordpress-plugin
 * -Plugin Name: FooGallery
 * -Plugin URI:  https://github.com/fooplugins/foogallery
 * -Description: Better Image Galleries for WordPress
 * -Version:     1.2.7
 * -Author:      FooPlugins
 * -Author URI:  http://fooplugins.com
 * -Text Domain: foogallery
 * -License:     GPL-2.0+
 * -License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * -Domain Path: /languages
 */

if ( !class_exists( 'Justified_Infinite_Scroll_Template_FooGallery_Extension' ) ) {

	define('JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION_URL', plugin_dir_url( __FILE__ ));
	define('JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION_VERSION', '1.0.1');

	require_once( 'foogallery-justified-infinite-scroll-init.php' );

	class Justified_Infinite_Scroll_Template_FooGallery_Extension {
		/**
		 * Wire up everything we need to run the extension
		 */
		function __construct() {
			add_filter( 'foogallery_gallery_templates', array( $this, 'add_template' ) );
			add_filter( 'foogallery_gallery_templates_files', array( $this, 'register_myself' ) );
			add_filter( 'foogallery_located_template-justified-infinite-scroll', array( $this, 'enqueue_dependencies' ) );
		}

		/**
		 * Register myself so that all associated JS and CSS files can be found and automatically included
		 * @param $extensions
		 *
		 * @return array
		 */
		function register_myself( $extensions ) {
			$extensions[] = __FILE__;
			return $extensions;
		}

		/**
		 * Enqueue any script or stylesheet file dependencies that your gallery template relies on
		 */
		function enqueue_dependencies() {
		/*
		 * We add the justifiedGallery code here, instead of INCLUDING IT DIRECTLY into the js file for the extension
		 * It seemed a bit weird to do that, but it is how they do it in the default "extension"...Scott 10/3/2015
		*/		
//			$js = JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION_URL . '/js/jquery.justifiedGallery.js';
			$js = JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION_URL . '/js/jquery.justifiedGallery.min.js';
			wp_enqueue_script( 'jquery-justified-gallery', $js, array('jquery') );

			$css = JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION_URL . '/css/justifiedGallery.min.css';
			wp_enqueue_style( 'justifiedGallery', $css );
		
		}


// copied from the 	foogallery\includes\admin\class-gallery-metabox-fields.php code to make pull down for sizes, and add "dynamic"
// to the array...
		function get_thumb_size_choices() {
			global $_wp_additional_image_sizes;
			$sizes = array();
			$sizes[ 'dynamic' ] = 'Dynamically Generated ';
			foreach( get_intermediate_image_sizes() as $s ){
				$sizes[ $s ] = array( 0, 0 );
				if ( in_array( $s, array( 'thumbnail', 'medium', 'large', ) ) ){
					$sizes[ $s ] = $s . ' - (' . get_option( $s . '_size_w' ) . 'x' . get_option( $s . '_size_h' ) . ')';
				} else {
					if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
						$sizes[ $s ] = $s . ' - (' . $_wp_additional_image_sizes[ $s ]['width'] . 'x' . $_wp_additional_image_sizes[ $s ]['height'] . ')';
				}
			}
			return $sizes;
		}

		
		
		/*
		 * Add our gallery template to the list of templates available for every gallery
		 * @param $gallery_templates
		 *
		 * @return array
		 */
		function add_template( $gallery_templates ) {

			$gallery_templates[] = array(
				'slug'        => 'justified-infinite-scroll',
				'name'        => __( 'Justified Gallery with Infinite Scroll', 'foogallery-justified-infinite-scroll'),
			//	'preview_css' => JUSTIFIED_INFINITE_SCROLL_FOOGALLERY_EXTENSION_URL . 'css/foogallery-justified-infinite-scroll.css',
			//	'admin_js'	  => JUSTIFIED_INFINITE_SCROLL_FOOGALLERY_EXTENSION_URL . 'js/admin-gallery-justified-infinite-scroll.js',
				'fields'	  => array(
					array(
						'id'	  => 'help',
						'title'	  => __( 'Tip', 'foogallery-justified-infinite-scroll' ),
						'type'	  => 'html',
						'help'	  => true,
						'desc'	  => __( 'This fooGallery template uses the popular <a href="http://miromannino.com/projects/justified-gallery/" target="_blank">Justified Gallery jQuery Plugin</a> under the hood. You can control the number in the initial display as well as the number added for each infinite scroll event. You can also specify thumbnail captions by setting the alt text for your attachments.', 'foogallery-justified-infinite-scroll' ),
					),
					array(
						'id'      => 'row_height',
						'title'   => __( 'Row Height', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'Choose the height of your thumbnails. Thumbnails will be generated on the fly and cached once generated.', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'number',
						'class'   => 'small-text',
						'default' => 150,
						'step'    => '10',
						'min'     => '0',
					),
					array(
						'id'      => 'max_row_height',
						'title'   => __( 'Max Row Height', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'A number (e.g 200) which specifies the maximum row height in pixels. A negative value for no limits. Alternatively, use a percentage (e.g. 200% which means that the row height cannot exceed 2 * rowHeight)', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'text',
						'class'   => 'small-text',
						'default' => '125%'
					),
					array(
						'id'      => 'margins',
						'title'   => __( 'Margins', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'The spacing between your thumbnails.', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'number',
						'class'   => 'small-text',
						'default' => 1,
						'step'    => '1',
						'min'     => '0',
					),
					array(
						'section' => __( 'Thumbnail Settings', 'foogallery-justified-infinite-scroll' ),
						'id'      => 'thumbnail_size',
						'title'   => __( 'Thumbnail Size', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'The size of each thumbnail in the gallery.', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'select',
						'default' => 'dynamic',
						'choices' => $this->get_thumb_size_choices(),
					),
					array(
						'id'      => 'caption_source', 
						'title'   => __( 'Caption Source', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'Pull captions from either the attachment Title, Caption or Alt Text.', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'radio',
						'default' => 'title',
						'spacer'  => '<span class="spacer"></span>',
						'choices' => array(
							'none'   =>__( 'None', 'foogallery-justified-infinite-scroll' ),
							'title'  => __( 'Attachment Title', 'foogallery-justified-infinite-scroll' ),
							'caption'=> __( 'Attachment Caption', 'foogallery-justified-infinite-scroll' ),
							'alt'    => __( 'Attachment Alt Text', 'foogallery-justified-infinite-scroll' )
						)
					),
					array(
						'id'      => 'thumbnail_link',
						'title'   => __( 'Thumbnail Link', 'foogallery-justified-infinite-scroll' ),
						'default' => 'image' ,
						'type'    => 'thumb_link',
						'spacer'  => '<span class="spacer"></span>',
						'desc'	  => __( 'You can choose to link each thumbnail to the full size image, or to the image\'s attachment page.', 'foogallery-justified-infinite-scroll' ),
					),
					array(
						'id'      => 'lightbox',
						'title'   => __( 'Lightbox', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'Choose which lightbox you want to display images with. The lightbox will only work if you set the thumbnail link to "Full Size Image".', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'lightbox',
					),
					array(
						'section' => __( 'Infinite Scroll Settings', 'foogallery-justified-infinite-scroll' ),
						'id'      => 'advance-page-mode',
						'title'   => __( 'Page / Scroll Mode', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'You can choose to add the next "page" of images when the user clicks a button, or scrolls to the end of the display', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'radio',
						'choices' => array(
							'scroll'  => __( 'Scroll', 'foogallery-justified-infinite-scroll' ),
							'page'   => __( 'Page', 'foogallery-justified-infinite-scroll' )
						),
						'spacer'  => '<span class="spacer"></span>',
						'default' => 'scroll'
					),
					array(
						'id'      => 'first_page',
						'title'   => __( 'Initial display', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'The number of items to display in the initial gallery.  Use 0 to display all images.', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'number',
						'class'   => 'small-text',
						'default' => 15,
						'step'    => '1',
						'min'     => '0',
					),
					array(
						'id'      => 'per_page',
						'title'   => __( 'Number each additional page', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'The number of items to add to the display on each page or whenever a scroll event occurs. Use 0 to add all remaining images to the gallery on the first event.', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'number',
						'class'   => 'small-text',
						'default' => 15,
						'step'    => '1',
						'min'     => '0',
					),
					array(
						'id'      => 'footer_offset',
						'title'   => __( 'Footer / scroll offset', 'foogallery-justified-infinite-scroll' ),
						'desc'    => __( 'The number of pixels to subtract from the gallery bottom in order to force scrolling to add more images before the end of the page has been shown.', 'foogallery-justified-infinite-scroll' ),
						'type'    => 'number',
						'class'   => 'small-text',
						'default' => 300,
						'step'    => '10',
						'min'     => '0',
					)
				)
			);
			return $gallery_templates;
		}
	}
}