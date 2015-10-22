FooGallery Justified Infinite Scroll Extension 
==============================================
Description
-----------

This is basically a "clone" of the existing "Justified Gallery" default template. But since
the underlying javascript engine now has the ability to do an "infinite scroll" (
See http://miromannino.github.io/Justified-Gallery/endless-scroll]for more 
information), this extension has modified code to make use of that capability and to use the "norewind"
option.

As part of the "Infinite Scroll" options, you can specify 
* page mode vs. scroll mode - controls if the advance is based on scrolling or on a specific click on the button to advance to the next "page" of images
* the number of images on the "initial" page (use 0 for all)
* the number of images per page (use 0 for all remaining images
* the tolerance on scrolling - i.e. how close to the bottom of the screen is the current gallery so that you may start adding more images before they reach the bottom of the gallery.  A value of 1.8-2.5 times the current row height should be suffient.
before you actually hit the end of the gallery.

Finally, while FooGallery will normally convert to a proper thumbnail size dynamically, this can cause issues in performace depending on the cache management and the size of the image being included into the gallery.  Therfore, there is also a pull-down to select the thumbnail "source" - either an existing thumbnail or to lett FooGallery call the library to dynamically resize the image .

Features at a glance
--------------------

* All the features of the built-in "justified Gallery Template"
* Adds ability to use the underlying javascript functionality of "norewind" which allows splitting up the content into "pages".
* Load additional images either by a click( Page Mode) or an onScroll action.
* Specify either Dynamic Generation of thumbnail images or use a specific size already supported by wordpress

Installation
------------

1. Upload the contents of this folder to the `/wp-content/plugins/foogallery-justified-infinite-scroll-template` directory (create it if necessary)
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Setup your gallery as normal in FooGallery, choosing the Justified with Infinite Scroll gallery type
1. Set values for the thumbnail and infinite scroll as mentioned above.

Other Information
-----------------
Since this is an extension template for the FooGallery plugin, you will need to have [FooGallery](http://wordpress.org/plugins/foogallery/) installed (and activated).
You can also check out the other FooGallery Extensions [here](http://foo.gallery/)

* Contributors: Scott Baeder
* Tags: foogallery, foo gallery, Justified Gallery, Infinite Scroll
* Requires at least: 3.4
* Tested up to: 
* Stable tag: 
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Changelog
---------

= 1.0.1 =
* final clean-up and testing
* modify UI, and add ability to select existing thumbnail or dynaic generation of the image

= 1.0 =
* Initial version - pre-release...
