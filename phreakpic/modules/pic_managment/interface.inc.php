<?php
require_once('includes/common.inc.php');

// Get Functions

function get_cats_of_cat($parent_id, $requested_fields)
{
	// Returns an Array of the requested fields of all categories which are under the categorie with ihe id $parent_id
   global $db;
   global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "cats WHERE parent_id = '$parent_id'";

   if (!$result = $db->query($sql))
   {
      message_die(GENERAL_ERROR, "Konnte Kategorie nicht auswählen", '', __LINE__, __FILE__, $sql);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $cat_data[] = $row;
   }


   return $cat_data;

}

function get_pics_of_cat($cat_id, $requested_fields)
{
	// Returns an Array of requested fields of all Pictures which are in the categorie with id $cat_id
   global $db;
	global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "pics WHERE cat_id = '$cat_id'";

   if (!$result = $db->query($sql))
   {
      message_die(GENERAL_ERROR, "Konnte Bilder nicht auswählen", '', __LINE__, __FILE__, $sql);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $pic_data[] = $row;
   }


   return $pic_data;

}

function get_pics_from_sql($sql_where_clause, $requested_fields)
{
   // Returns an Array of the requested fields of the pictures that are returned by the sql where clause $sql_where_clause
   global $db;
	global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "pics
      WHERE $sql_where_clause";

   if (!$result = $db->query($sql))
   {
      message_die(GENERAL_ERROR, "Konnte Bilder nicht auswählen bei eigener WHERE clause", '', __LINE__, __FILE__, $sql);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $pic_data[] = $row;
   }


   return $pic_data;

}

function get_series_of_cat($cat_id, $requested_fields)
{
	// Return an Array of the requested fields of the series that are in the categorie $cat_id
   global $db;
	global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "series
      WHERE cat_id = '$cat_id'";

   if (!$result = $db->query($sql))
   {
   	message_die(GENERAL_ERROR, "Konnte Serien nicht auswählen", '', __LINE__, __FILE__, $sql);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $series_data[] = $row;
   }

   return $series_data;

}

function get_pics_of_serie($serie_id)
{
	// Returns an Array of the pictures that are in the serie with the id $serie_id ready ordered
   global $db;
	global $config_vars;

   $sql = "SELECT pic_id FROM " . $config_vars['table_prefix'] . "pic_in_serie
      WHERE serie_id = '$serie_id'
      ORDER BY place_in_serie";

   if (!$result = $db->query($sql))
   {
   	message_die(GENERAL_ERROR, "Konnte Bilder der Serie nicht auswählen", '', __LINE__, __FILE__, $sql);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $pic_data[] = $row;
   }


   return $pic_data;

}

function get_pic_data($pic_id, $requested_fields)
{
   // Returns the requested fields of the pic with id $pic_id
   global $db;
   global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "pics
      WHERE pic_id = '$pic_id'";

   if (!$result = $db->query($sql))
   {
      message_die(GENERAL_ERROR, "Konnte das Bild nicht auswählen", '', __LINE__, __FILE__, $sql);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $pic_data[] = $row;
   }


   return $pic_data;
}


// Add Functions

function add_dir_to_cat($dir,$cat_id, $name_mode = GENERATE_NAMES)
{
   // Adds all pictures in the Directory $dir into the Categorie with the id $cat_id
   global $db;
   global $config_vars;

   if ($name_mode == GENERATE_NAMES)
   {

   }

   $sql = "INSERT INTO " . $config_vars['table_prefix'] . "pics (name, file, cat_id, creation_date) VALUES ()"

}

function add_dir_parsed($dir)
{
// Add all pictures under the Directory $dir to categories ans series depending on the relativ path to $dir
}

function add_pic_to_cat($pic_dir,$cat_id)
{
// Add the pic located in $pic_dir to the Categorie with the id $cat_id
}

// Cateogries and series

function new_category($parent_id,$name)
{
// Creates an new categorie with name $name under the categorie with the id $parent_id
	global $db;

	$sql = '
		INSERT INTO '.$table_prefix."categories (name,parent_id)
		VALUES ('$name',$parent_id)";

	$result = $db->query($sql);

	if (DB::isError($result))
	{
		message_die("","During creation of a Categorie ","Something went wrong<br> ",  $result->getMessage(), '', __LINE__, __FILE__);
	}

}

function new_serie($cat_id,$name,$pictures)
{
// Creates a serie in the Categorie with the id $cat_id, namde $name with the pictures in the array $pictures in the same order as they are in the array

}


?>
