/**
 * FooGallery Justified Init Code.
 * Only initialize the Justified Gallery when all images are loaded
 */
/*!
 * Justified Gallery - v3.6.0
 * http://miromannino.github.io/Justified-Gallery/
 * Copyright (c) 2015 Miro Mannino
 * Licensed under the MIT license.
 
 */
jQuery(function ($) {
	$('.foogallery-justified-infinite-scroll').each(function() {
        var $gallery = $(this);
        var options = $.extend( {
            fixedHeight: false,
            randomize: false,
			target: null,
			refreshTime: 250,
			captionsAnimationDuration: 500,
			imagesAnimationDuration: 300,
			captionsVisibleOpacity: 0.7,
			class: "",
            cssAnimation: true,
            waitThumbnailsLoad: false,
            lastRow: 'nojustify',
            justifyThreshold: 0.5,
			
			sizeRangeSuffixes: {
                'lt100':'',
                'lt240':'',
                'lt320':'',
                'lt500':'',
                'lt640':'',
                'lt1024':''
            }
        }, $gallery.data('justified-options') );
        $gallery.removeClass('foogallery-justified-loading').justifiedGallery( options );
    });
});

