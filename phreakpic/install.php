<html>
<head>
<title>Phreakpic Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
	h1, h2, h3, h4, h5, h6, p, font, td {  font-family: "Trebuchet MS", Verdana}
-->
</style>
</head>

<body bgcolor="#FFFFFF">

<?php
if ($mode == "check_user_info")
{
	/* 
	* phreakpic_path
	* Smarty_dir
	* content_path_prefix
	* Server_name
	* table_prefix
	* root_categorie
	* deleted_content_cat
	* default_lang
	* default_template
	*/
	$check = @include($phpBB_Path . "config.php");
	if (empty($check))
	{
		die('phpBB2 Path is wrong. Check if you have a trailing slash (like foo/bar/phpBB2/ ).');
	}
	
	$check = file_exists ($phpBB_Path . $phreakpic_path . "view_cat.php");
	if (empty($check))
	{
		die('Phreakpic Path is wrong. Check if you have a trailing slash (like foo/bar/Phreakpic/ ).');
	}
	
	$check = file_exists($Smarty_dir . "Smarty.class.php");
	if (empty($check))
	{
		die('Smarty Dir is wrong. Check if you have a trailing slash (like /usr/local/httpd/htdocs/foo/bar/Smarty/ ).');
	}
	
	$check = @chdir("./" . $content_path_prefix);
	if ($check == false)
	{
		die('Couldn\'t go into the content dir. Is the folder ' . $content_path_prefix . ' existant and have proper rights??');
	}
	$check = @mkdir("test_install_dir", 0755);
	if ($check == false)
	{
		die ('Content dir have not the right permissions (must be writable for PhreakPic) or the Folder is existing.');
	}
	rmdir("test_install_dir");
}

else
{
?>
<div align="center">
	<h1>PhreakPic Installation</h1>
	<p>
		Welcome and thank you for using PhreakPic!<br>
		Please fill out the empty fields. Only change the filled fields if you know 
		what you're doing.<br>
		You must have installed a working phpBB2 and smarty.
	</p>
	<form method="post" action="<?php $PHP_SELF ?>">
		<input type="hidden" name="mode" value="check_user_info">
		
    <table width="95%" border="0" cellspacing="0" cellpadding="5">
      <tr bgcolor="#3399CC"> 
        <td colspan="4" height="10"></td>
      </tr>
      <tr> 
        <td rowspan="19" bgcolor="#3399CC" width="10"></td>
        <td colspan="3"> 
          <h3>Files and Pathes</h3>
        </td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">phpBB2 Path</td>
        <td width="25%"> 
          <input type="text" name="phpBB_Path" size="30">
        </td>
        <td width="54%">Relative path from phreakpic to phpBB2 (if the URL is 
          "http://www.blabla.com/com/phpBB2/" and phreakpic is at "http://www.blabla.com/com/phreakpic/" 
          then it will be "../phpBB2/").</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">PhreakPic Path</td>
        <td width="25%"> 
          <input type="text" name="phreakpic_path" size="30">
        </td>
        <td width="54%">Relative Path from phpBB2 to PhreakPic. See above.</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">Smarty Dir</td>
        <td width="25%"> 
          <input type="text" name="Smarty_dir" size="30">
        </td>
        <td width="54%">Absolute Path to Smarty. E.g. &quot;/usr/local/httpd/htdocs/smarty/&quot;</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">Content Path</td>
        <td width="25%"> 
          <input type="text" name="content_path_prefix" size="30" value="content">
        </td>
        <td width="54%">In this Folder the Content (mostly Pictures) will be stored.</td>
      </tr>
      <tr bgcolor="#FFCC99"> 
        <td width="21%">Servername</td>
        <td width="25%"> 
          <input type="text" name="Server_name" size="30">
        </td>
        <td width="54%">Thats the Domain as you type it in your Webbrowser. E.g. 
          &quot;http://www.localhorst.com&quot; </td>
      </tr>
      <tr> 
        <td colspan="3" height="26"> 
          <h3>Database</h3>
        </td>
      </tr>
      <tr bgcolor="#CCCC99"> 
        <td width="21%">Table Prefix</td>
        <td width="25%"> 
          <input type="text" name="table_prefix" size="30" value="photo_">
        </td>
        <td width="54%">The prefix for the tables in the database.</td>
      </tr>
      <tr bgcolor="#CCCC99"> 
        <td width="21%">Root Category</td>
        <td width="25%"> 
          <input type="text" name="root_categorie" size="30" value="0">
        </td>
        <td width="54%">The ID of the Root Category</td>
      </tr>
      <tr bgcolor="#CCCC99"> 
        <td width="21%">Deleted Content Cat</td>
        <td width="25%"> 
          <input type="text" name="deleted_content_cat" size="30" value="1">
        </td>
        <td width="54%">The ID of the table where the deleted content will be 
          stored </td>
      </tr>
      <tr> 
        <td colspan="3"> 
          <h3>User Interface</h3>
        </td>
      </tr>
      <tr bgcolor="#99CCCC"> 
        <td width="21%">Default Language</td>
        <td width="25%"> 
          <input type="text" name="default_lang" size="30" value="english">
        </td>
        <td width="54%">&nbsp;</td>
      </tr>
      <tr bgcolor="#99CCCC"> 
        <td width="21%" height="38">Default Template</td>
        <td width="25%" height="38"> 
          <input type="text" name="default_template" size="30" value="standard">
        </td>
        <td width="54%" height="38">&nbsp;</td>
      </tr>
      <tr bgcolor="#99CCCC">
        <td width="21%" height="38">&nbsp;</td>
        <td width="25%" height="38">
          <div align="center">
            <input type="submit" name="install" value="Install">
          </div>
        </td>
        <td width="54%" height="38"> 
          <div align="right">
            <input type="reset" name="cancel" value="Delete Form">
          </div>
        </td>
      </tr>
    </table>
	</form>
</div>
<?php

}

?>
</body>
</html>
