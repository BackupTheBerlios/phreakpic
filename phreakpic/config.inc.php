<?php
$config_vars = array
(
	//Database
	'table_prefix' => 'photo_',
	
	'content_path_prefix' => 'content',

	//Picture stuff
	'thumb_size' =>
	array
	(
	//	'percent' => '30',
	//	'height' => '130',
	//	'width' => '100'
		'maxsize' => '130'
	),
	
	
	// ID of the cat where to put pictures that are no longer linked in any cat
	'deleted_content_cat' => 1,
	
	'root_categorie' => 0,
	
	'dir_mask' => 0775,
   
	//view_cat.php the Colums of the table, where we can see the thumbnails
	'thumb_table_cols' => 4,
	'default_template' => 'standard',
	'default_lang' => 'german'
);
?>
