<?php
include ('../includes/common.inc.php');

class album_content
{
   var $id;
   var $file;
   var $cat_id;
   var $name;
   var $views;
   var $current_rating;
   var $creation_date;
   var $group_id;

   function album_content() //Constructor
   {

   }


   function generate_thumb($thumb_size = $config_vars['thumb_size'])
   {
      // is for extended classes
      //Generates a thumbnail picture from the actual content in the size $thumb_size. check for making the size of the thumb right (higher pictures other than widther pictures).
      return NOT_SUPPORTED;
   }

   function change_compression($compression)
   {
      // is for extended classes
      //Change the compression of the actual content object.
      return NOT_SUPPORTED;
   }

   function change_size($size, $save_mode)
   {
      // is for extended classes
      //change the size of the actual object.
      return NOT_SUPPORTED;
   }

   function get_html()
   {
      // is for extended classes
      //returns the needed HTML Code to show the actual object.
      return NOT_SUPPORTED;
   }

   function delete()
   {
      //delete the actual object from Database and filesystem. Checks if the actual object ist yet in database. Also checks authorisation.
      global $db;
      global $config_vars;

      if (check_content_action_allowed($this->content_group_id, $userdata['user_id'], "delete")) //Authorisation is okay
      {
         $sql = "DELETE FROM '" . $config_vars['table_prefix'] . "content' WHERE 'id' = " . $this->id;

         if (!$result = $db->query($sql))
         {
            message_die(GENERAL_ERROR, "Konnte Objekt nicht löschen", '', __LINE__, __FILE__, $sql);
         }

         $this->id = 0;

         $result = unlink($this->file);
         if (!$result)
         {
            message_die(GENERAL_ERROR, "Konnte Datei nicht löschen", '', __LINE__, __FILE__, '');
         }

      }
      else
      {
         return NOT_ALLOWED;
      }
   }

   function commit()
   {
      //commits all changes of the actual object to the database and/or filesystem
      global $db;
      global $config_vars;

      $sql = "UPDATE '" . $config_vars['table_prefix'] . "content'
         SET id = '" . $this->id . "', file = '" . $this->file . "', cat_id = '" . $this->cat_id . "', name = '" . $this->name . "', views = '" . $this->views . "', current_rating = '" . $this->current_rating . "', creation_date = '" . $this->creation_date . "', group_id = '" . $this->group_id . "'";

      if (!$result = $db->query($sql))
      {
         message_die(GENERAL_ERROR, "Konnte Objekt nicht commiten", '', __LINE__, __FILE__, $sql);
      }
   }

   //set and get functions for every variable
   function set_id($id)
   {
      //set the id of the actual object. Checks if actual user is allowed to.
      $this->id = $id;
   }

   function get_id()
   {
      //get the id of the actual object. Checks if actual user is allowed to.
      return $this->id;
   }

   function set_file($file)
   {
      //set the file of the actual object. Checks if actual user is allowed to.
      $this->file = $file;
   }

   function get_file()
   {
      //get the id of the actual object. Checks if actual user is allowed to.
      return $this->file;
   }

   function set_cat_id($cat_id)
   {
      //set the cat_id of the actual object. Checks if actual user is allowed to.
      $this->cat_id = $cat_id;
   }

   function get_cat_id()
   {
      //get the cat_id of the actual object. Checks if actual user is allowed to.
      return $this->cat_id;
   }

   function set_name($name)
   {
      //set the name of the actual object. Checks if actual user is allowed to.
      $this->name = $name;
   }

   function get_name()
   {
      //get the name of the actual object. Checks if actual user is allowed to.
      return $this->name;
   }

   function set_views($views)
   {
      //set the views of the actual object. Checks if actual user is allowed to.
      $this->views = $views;
   }

   function get_views()
   {
      //get the views of the actual object. Checks if actual user is allowed to.
      return $this->views;
   }

   function set_current_rating($current_rating)
   {
      //set the current_rating of the actual object. Checks if actual user is allowed to.
      $this->current_rating = $current_rating;
   }

   function get_current_rating()
   {
      //get the current_rating of the actual object. Checks if actual user is allowed to.
      return $this->current_rating;
   }

   function set_creation_date($creation_date)
   {
      //set the creation_date of the actual object. Checks if actual user is allowed to.
      $this->creation_date = $creation_date;
   }

   function get_creation_date()
   {
      //get the creation_date of the actual object. Checks if actual user is allowed to.
      return $this->creation_date;
   }

   function set_contentgroup_id($contentgroup_id)
   {
      //set the contentgroup_id of the actual object. checks if actual user is allwoed to.
      $this->contentgroup_id = $contentgroup_id;
   }

   function get_contentgroup_id()
   {
      //get the creation_date of the actual object. Checks if actual user is allowed to.
      return $this->contentgroup_id;
   }
}


class photo extends album_content
{
   function generate_thumb($thumb_size = $config_vars['thumb_size'])
   {

   }
}


?>
