FooGallery Justified Infinite Scroll Extension 
==============================================

Contributors: 
Donate link:
Tags: foogallery, foo gallery, Justified Gallery, Infinite Scroll
Requires at least: 3.4
Tested up to: 
Stable tag: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extension for FooGallery, 

Description
-----------

This is basically a "clone" of the existing "Justified Gallery" default template. But since
the underlying javascript engine now has the ability to do an "infinite scroll" (
See [http://miromannino.github.io/Justified-Gallery/endless-scroll] for more 
information), this extension has modified code to make use of that capability and to use the "norewind"
option.

In addition to the original options, you can specify page mode vs. scroll mode, specify the number of images on
the "initial" page as well as the number per page after that.  You can also specify how agress to be on scrolling
before you actually hit the end of the gallery.

This is still "in development" ...

Features at a glance
--------------------

* All the features of the built-in "justified Gallery Template"
* Adds ability to use the underlying javascript functionality of "norewind" which allows splitting up the content into "pages".
* Load additional images either by page (click) or onScroll actions.

= Read more about the required plugins =

You will need [FooGallery](http://wordpress.org/plugins/foogallery/) installed.

[Check out all the FooGallery Extensions](http://foo.gallery/)

Installation
------------

1. Upload the ` ` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Setup your gallery as normal in FooGallery, choosing the Justified with Infinite Scroll gallery type
1. Set values for the number to be included on the first and each subsequent page.  If the number is "0", 
all unprocessed images will be added to the gallery (either on the first or the second page)

Changelog
---------
= 1.0 =
* Initial version - pre-release...
