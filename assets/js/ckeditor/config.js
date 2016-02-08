/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

        config.skin = 'office2003';
	
	config.toolbar = 'full_text_toolbar';
	config.toolbar_full_text_toolbar =
	[
		{ name: 'document', items : [ 'NewPage','Preview' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format', 'Font','FontSize' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] }
	];	
	
	config.toolbar = 'add_comment_toolbar';
	config.toolbar_add_comment_toolbar =
	[
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat','Link','Unlink' ] },
	];	

	config.toolbar = 'add_image_description_toolbar';
	config.toolbar_add_image_description_toolbar =
	[
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat','Link','Unlink','Anchor','Smiley' ] },
	];		
};
