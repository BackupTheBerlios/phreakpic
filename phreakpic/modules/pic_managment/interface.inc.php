<?php

function get_cats($parent_id)
{
// Retruns an Array of cat_ids. Of all categories which are under the categorie with ihe id $parent_id

}

function get_pics_of_cat($cat_id)
{
// Returns an Array of pic_ids of all Pictures which are in the categorie with id $cat_id
}

function get_pics_from_sql($sql)
{
// Returns an Array of pic_ids of the pictures that are returned by the sql querry $sql
}

function get_series_of_cat($cat_id)
{
// Return an Array of serie_ids of the series that are in the categorie $cat_id
}

function get_pics_of_serie($serie_id)
{
// Returns an Array of pic_ids of the pictures that are in the serie with the id $serie_id
}

function get_thumb_path_of_pic($pic_id)
{
// Returns a path to the thumb of the pic with id $pic_id
}

function get_pic_path($pic_id)
{
// Returns the path to the pic with id $pic_id
}

function add_dir_to_cat($dir,$cat_id)
{
// Adds all pictures in the Directory $dir into the Categorie with the id $cat_id
}

function add_dir_parsed($dir)
{
// Add all pictures under the Directory $dir to categories ans series depending on the relativ path to $dir
}

function add_pic_to_cat($pic_dir,$cat_id)
{
// Add the pic located in $pic_dir to the Categorie with the id $cat_id

}
?> 
