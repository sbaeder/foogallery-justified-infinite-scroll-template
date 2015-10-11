<?php
//This init class is used to add the extension to the extensions list while you are developing them.
//When the extension is added to the supported list of extensions, this file is no longer needed.

if ( !class_exists( 'Justified_Infinite_Scroll_Template_FooGallery_Extension_Init' ) ) {
	class Justified_Infinite_Scroll_Template_FooGallery_Extension_Init {

		function __construct() {
			add_filter( 'foogallery_available_extensions', array( $this, 'add_to_extensions_list' ) );
		}

		function add_to_extensions_list( $extensions ) {
			$extensions[] = array(
				'slug'=> 'justified-infinite-scroll',
				'class'=> 'Justified_Infinite_Scroll_Template_FooGallery_Extension',
				'title'=> __('Justified Infinite Scroll', 'foogallery-justified-infinite-scroll'),
				'file'=> 'foogallery-justified-infinite-scroll-extension.php',
				'description'=> __('Use the Justified formating template, but splits the gallery on specified page limits to improve user experiance on large galleries', 'foogallery-justified-infinite-scroll'),
				'author'=> 'Scott Baeder',
				'author_url'=> '',
				'thumbnail'=> JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION_URL . '/assets/extension_bg.png',
				'tags'=> array( __('template', 'foogallery') ),	//use foogallery translations
				'categories'=> array( __('Build Your Own', 'foogallery') ), //use foogallery translations
				'source'=> 'generated'
			);

			return $extensions;
		}
	}

	new Justified_Infinite_Scroll_Template_FooGallery_Extension_Init();
}