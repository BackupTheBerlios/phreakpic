<?php
define ("ROOT_PATH",'');
include_once ('includes/common.inc.php');
include_once ('includes/functions.inc.php');
include_once ('classes/group.inc.php');
include_once ('classes/categorie.inc.php');

// Some Config vars

$version = "alpha";

$dbdump = "mysql_$version.sql";


$available_dbms = array(
	"mysql" => array(
		"LABEL" => "MySQL 3.x",
		"SCHEMA" => "mysql", 
		"DELIM" => ";",
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_remarks"
	)
	
	/*, 
	"mysql4" => array(
		"LABEL" => "MySQL 4.x",
		"SCHEMA" => "mysql", 
		"DELIM" => ";", 
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_remarks"
	), 
	"postgres" => array(
		"LABEL" => "PostgreSQL 7.x",
		"SCHEMA" => "postgres", 
		"DELIM" => ";", 
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_comments"
	), 
	"mssql" => array(
		"LABEL" => "MS SQL Server 7/2000",
		"SCHEMA" => "mssql", 
		"DELIM" => "GO", 
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_comments"
	),
	"msaccess" => array(
		"LABEL" => "MS Access [ ODBC ]",
		"SCHEMA" => "", 
		"DELIM" => "", 
		"DELIM_BASIC" => ";",
		"COMMENTS" => ""
	),
	"mssql-odbc" =>	array(
		"LABEL" => "MS SQL Server [ ODBC ]",
		"SCHEMA" => "mssql", 
		"DELIM" => "GO",
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_comments"
	)*/
);
?>

<html>
<head>
<title>Phreakpic Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
	h1, h2, h3, h4, h5, h6, p, font, td {	font-family: "Trebuchet MS", Verdana}
-->
</style>
</head>

<body bgcolor="#FFFFFF">

<?php
if ($mode == "check_user_info")
{
	/* 
	* 
	* 
	* 
	* Server_name
	* table_prefix
	* root_categorie
	* deleted_content_cat
	* 
	* 
	*/
	
	echo ('<p>Checking the Data of the Form.<br>');
	
	//phpBB Path
	$check = @include($phpBB_Path . "config.php");
	if (empty($check))
	{
		die('phpBB2 Path is wrong. Check if you have a trailing slash (like foo/bar/phpBB2/ ).');
	}
	echo ('phpBB Path: <font color=#00ff00>OK</font><br>');
	
	//phreakpic Path
	$check = file_exists ($phpBB_Path . $phreakpic_path . "view_cat.php");
	if (empty($check))
	{
		die('Phreakpic Path is wrong. Check if you have a trailing slash (like foo/bar/Phreakpic/ ).');
	}
	echo ('Phreakpic Path: <font color=#00ff00>OK</font><br>');
	
	//Smarty Dir
	$check = file_exists($Smarty_dir . "Smarty.class.php");
	if (empty($check))
	{
		die('Smarty Dir is wrong. Check if you have a trailing slash (like /usr/local/httpd/htdocs/foo/bar/Smarty/ ).');
	}
	echo ('Smarty Dir: <font color=#00ff00>OK</font><br>');
	
	//content path prefix
	$check = @chdir("./" . $content_path_prefix);
	if ($check == false)
	{
		$check = @mkdir("./" . $content_path_prefix , 0775);
		if ($check == false)
		{
			die('Content dir folder does not exist and could not be created. Create the folder ./'.$content_path_prefix . ' or make ./ writeable ');	
		}
		
	}
	chdir("../");
	
	echo ('Content Dir: <font color=#00ff00>OK</font><br>');
	
	$check = @mkdir("$content_path_prefix/test_install_dir", 0755);
	if ($check == false)
	{
		die ("Content Dir (./$content_path_prefix) not writable (your webserver must be able to write there");
	}
	
	rmdir("$content_path_prefix/test_install_dir");
	
	
	echo ('Upload Dir permissions: <font color=#00ff00>OK</font><br>');
	
	//upload path 
	$check = @chdir("./" . $upload_path);
	if ($check == false)
	{
		$check = @mkdir("./" . $upload_path , 0775);
		if ($check == false)
		{
			die('Upload dir folder does not exist and could not be created. Create the folder ./'.$upload_path . ' or make ./ writeable ');	
		}
		
	}
	chdir("../");
	
	echo ('Upload Dir: <font color=#00ff00>OK</font><br>');
	
	$check = @mkdir("$upload_path/test_install_dir", 0755);
	if ($check == false)
	{
		die ("Content Dir (./$upload_path) not writable (your webserver must be able to write there");
	}
	
	rmdir("$upload_path/test_install_dir");
	
	
	echo ('Upload Dir permissions: <font color=#00ff00>OK</font><br>');
	
	
	//default_lang
	$check = @chdir("./languages/" . $default_lang);
	if ($check == false)
	{
		die('Couldn\'t go into the Language dir. Is the Language ' . $default_lang . ' installed in phreakpic/languages??');
	}
	chdir("../../");
	echo ('Default Language: <font color=#00ff00>OK</font><br>');
	
	//default Template
	$check = @chdir("./templates/" . $default_template);
	if ($check == false)
	{
		die('Couldn\'t go into the Template dir. Is the Template ' . $default_template . ' installed in phreakpic/templates??');
	}
	chdir("../../");
	echo ('Default Template: <font color=#00ff00>OK</font><br>');
	
	/*
	// phreakpic_table_prefix
	
	if ($phreakpic_table_prefix)
	{
		
	}
	else
	{
		die ()
	}
	
	echo ('Database table prefix: <font color=#00ff00>OK</font><br>');
	*/
	
	
	echo ('</p>');
	
	
	//Now do the db stuff
	
	echo ('<p>Now we can start with the DB Stuff.<br>');
	if ($dbms != 'mysql')
	{
		die ('Sorry, ' . $dbms . ' is not supported. Please install your phpBB2 with MySQL Database.');
	}
	echo ('DB Type: <font color=#00ff00>OK</font><br>');
	
	
	echo ("Connecting to DB $dbname on host $dbhost with user $dbuser<br>");
	$connect = mysql_connect($dbhost,$dbuser,$dbpasswd);
	if (!$connect)
	{
		die ('Database connect: <font color=#ff0000>FAILED</font><br>');
	}
	
	
	$fd = fopen ("install/" . $dbdump, "rb");
	if (!$fd)
	{
		die ("Can't open the dbdump!");
	}
	$filecontent = fread ($fd, filesize ("install/" . $dbdump));
	fclose ($fd);
	
	$usable_dump = str_replace ("photo_", $phreakpic_table_prefix, $filecontent);
	
	$usable_dump = explode($available_dbms[$dbms]["DELIM"], $usable_dump);
	
	echo ("Adding tables toDatabase <br>");
	for ($i = 0; $i < (sizeof($usable_dump) -1); $i++)
	{
		$j++;
		echo ("Table $j: ");
		$check = mysql_db_query ($dbname, $usable_dump[$i], $connect);
		$check = true;
		if ($check == false)
		{
			die (mysql_errno().": ".mysql_error()."<BR>");
		}
		echo ('<font color=#0000ff>done</font><br>');	
	}
	echo ('<font color=#00ff00>OK</font><br>');
	
	

	//Filling basic Data in the DB
	
	echo("Filling Tables<br>");
	
	// set talbe prefix
	$config_vars['table_prefix'] = $phreakpic_table_prefix;
	// give admin rights.
	$userdata['user_level'] = ADMIN;
	
	
	$admin_group = new catgroup;
	$admin_group->name = ('Admin Group');
	$admin_group->description = ('This is the Administrator Category Group, groups like the Root Car or Deleted Content belong to it. You can change the name and description, but never delete it!');
	echo("Admin Group: " . $admin_group->commit() . "<br>");
	
	//root cat
	$root_cat = new categorie;
	$root_cat->parent_id = 1;
	$root_cat->catgroup_id = $admin_group->get_id();
	$root_cat->name = ("root_cat");
	$root_cat->description = ("This is your Root Category. You can change the name and description, but never delete it!");
	$root_cat->commit();
	// set parent id to given id
	$root_cat->parent_id = $root_cat->id;
	echo("Root Cat: " . $root_cat->commit() . "<br>");
	
	
	//deleted content cat
	$deleted_content_cat = new categorie;
	$deleted_content_cat->parent_id = $root_cat->id;
	$deleted_content_cat->catgroup_id = $admin_group->get_id();
	$deleted_content_cat->name = ("Deleted Content");
	$deleted_content_cat->description = ("This is your Deleted Content Category. Here will be your deleted content stored. You can change the name and description, but never delete it!");
	echo("Delete Content Cat: " . $deleted_content_cat->commit() . "<br>");
	
	
	echo ('<font color=#00ff00>OK</font><br>');
	
	echo ('</p>'); //end of DB stuff
	
	
	//write the config file
	echo ('<p> Writing the Config ');
	
	
	//fill config_vars;
	$config_vars['table_prefix'] = $phreakpic_table_prefix;
	$config_vars['content_path_prefix'] = $content_path_prefix;
	$config_vars['deleted_content_cat'] = $deleted_content_cat->id;
	$config_vars['root_categorie'] = $root_cat->id;
	$config_vars['default_template'] = $default_template;
	$config_vars['default_lang'] = $default_lang;
	$config_vars['default_upload_dir'] = $upload_path;
	
	// set default values
	$config_vars['thumb_table_cols'] = 4;
 	$config_vars['default_content_per_page'] = 12;
 	write_config($Smarty_dir,$phpBB_Path,$phreakpic_path,$Server_name);

	echo ('<font color=#00ff00>OK</font></p>');
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
				<td width="54%">In this Folder the Content (mostly Pictures) will be stored. It must be writeable</td>
			</tr>
			<tr bgcolor="#FFCC99"> 
				<td width="21%">Upload Path</td>
				<td width="25%"> 
					<input type="text" name="upload_path" size="30" value="upload">
				</td>
				<td width="54%">Content to add must be uploaded in this folder. It must be writeable</td>
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
					<input type="text" name="phreakpic_table_prefix" size="30" value="phreakpic_">
				</td>
				<td width="54%">The prefix for the tables in the database.</td>
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
					<input type="text" name="default_template" size="30" value="standard_black">
				</td>
				<td width="54%" height="38">&nbsp;</td>
			</tr>
			<tr bgcolor="#99CCCC">
				<td width="21%" height="38">&nbsp;</td>
				<td width="25%" height="38">
					<div align="center">
						<input type="hidden" name="sid" value="<?php echo($sid); ?>">
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
