//Use this file to inject custom javascript behaviour into the foogallery edit page
//For an example usage, check out wp-content/foogallery/extensions/default-templates/js/admin-gallery-default.js

(function (JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION, $, undefined) {

	JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION.doSomething = function() {
		//do something when the gallery template is changed to justified-infinite-scroll
	};

	JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION.adminReady = function () {
		$('body').on('foogallery-gallery-template-changed-justified-infinite-scroll', function() {
			JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION.doSomething();
		});
	};

}(window.JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION = window.JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION || {}, jQuery));

jQuery(function () {
	JUSTIFIED_INFINITE_SCROLL_TEMPLATE_FOOGALLERY_EXTENSION.adminReady();
});