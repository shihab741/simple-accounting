/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	 config.extraPlugins = 'imageuploader';
	 
	 
	config.filebrowserBrowseUrl = '/ckeditor/plugins/imageuploader/imgbrowser.php';
	config.filebrowserImageBrowseUrl = 'http://fromreadingtable.com/quotes/ckeditor/plugins/imageuploader/imgbrowser.php?type=Images';
	config.filebrowserFlashBrowseUrl = '/ckfinder/ckfinder.html?type=Flash';
	
	
};