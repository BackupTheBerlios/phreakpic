<?php
require_once('includes/common.inc.php');

// Get Functions

function get_cats($parent_id, $requested_fields)
{
	// Returns an Array of cat_ids and their names. Of all categories which are under the categorie with ihe id $parent_id
   global $db;
   global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "cats WHERE parent_id = '$parent_id'";

   if (!$result = $db->query($sql))
   {
   	message_die("", "Beim auswählen der Kategorien ", "Datenbankabfrage schlug fehl<br>", $result->getMessage(), "", __LINE__, __FILE__);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $cat_data[] = $row;
   }


   return $cat_data;

}

function get_pics_of_cat($cat_id, $requested_fields)
{
	// Returns an Array of pic_ids of all Pictures which are in the categorie with id $cat_id
   global $db;
	global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "pics WHERE cat_id = '$cat_id'";

   if (!$result = $db->query($sql))
   {
   	message_die("", "Beim auswählen der Bilder ", "Datenbankabfrage schlug fehl<br>", $result->getMessage(), "", __LINE__, __FILE__);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $pic_data[] = $row;
   }


   return $pic_data;

}

function get_pics_from_sql($sql_where_clause, $requested_fields)
{
// Returns an Array of pic_ids of the pictures that are returned by the sql querry $sql
   global $db;
	global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "pics WHERE $sql_where_clause";

   if (!$result = $db->query($sql))
   {
   	message_die("", "Beim auswählen der Bilder mit eigener WHERE Clause ", "Datenbankabfrage schlug fehl<br>", $result->getMessage(), "", __LINE__, __FILE__);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $pic_data[] = $row;
   }


   return $pic_data;

}

function get_series_of_cat($cat_id, $requested_fields)
{
// Return an Array of serie_ids of the series that are in the categorie $cat_id
   global $db;
	global $config_vars;

   $sql = "SELECT $requested_fields FROM " . $config_vars['table_prefix'] . "series WHERE cat_id = '$cat_id'";

   if (!$result = $db->query($sql))
   {
   	message_die("", "Beim auswählen der Serien ", "Datenbankabfrage schlug fehl<br>", $result->getMessage(), "", __LINE__, __FILE__);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $series_data[] = $row;
   }

   return $series_data;

}

function get_pics_of_serie($serie_id)
{
// Returns an Array of pic_ids of the pictures that are in the serie with the id $serie_id
   global $db;
	global $config_vars;

   $sql = "SELECT pic_id FROM " . $config_vars['table_prefix'] . "pic_in_serie WHERE serie_id = '$serie_id' ORDER BY place_in_serie";

   if (!$result = $db->query($sql))
   {
   	message_die("", "Beim auswählen der Bilder ", "Datenbankabfrage schlug fehl<br>", $result->getMessage(), "", __LINE__, __FILE__);
   }

   while ($row = $db->sql_fetchrow($result))
   {
      $pic_data[] = $row;
   }


   return $pic_data;

}

function get_pic_data($pic_id, $requested_fields)
{
// Returns the path to the pic with id $pic_id
}


// Add Functions

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
