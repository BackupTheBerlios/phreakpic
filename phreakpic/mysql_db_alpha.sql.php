<?php

$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "cat_auth`
#

CREATE TABLE `" . $phreakpic_table_prefix . "cat_auth` (
  `usergroup_id` int(11) NOT NULL default '0',
  `catgroup_id` int(11) NOT NULL default '0',
  `view` enum('0','1') NOT NULL default '0',
  `delete` enum('0','1') NOT NULL default '0',
  `edit` enum('0','1') NOT NULL default '0',
  `cat_add` enum('0','1') NOT NULL default '0',
  `content_add` enum('0','1') NOT NULL default '0',
  `content_remove` enum('0','1') NOT NULL default '0',
  `cat_remove` enum('0','1') NOT NULL default '0',
  `comment_edit` enum('0','1') NOT NULL default '0',
  `add_to_group` enum('0','1') NOT NULL default '0',
  `remove_from_group` enum('0','1') NOT NULL default '0'
) TYPE=MyISAM

"; 

$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "cat_comments`
#

CREATE TABLE `" . $phreakpic_table_prefix . "cat_comments` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) default NULL,
  `feedback` text NOT NULL,
  `user_id` int(11) default NULL,
  `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `changed_count` smallint(6) NOT NULL default '0',
  `parent_id` smallint(6) NOT NULL default '0',
  `topic` text NOT NULL,
  `last_changed_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM
"; 

$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "catgroups`
#

CREATE TABLE `" . $phreakpic_table_prefix . "catgroups` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "cats`
#

CREATE TABLE `" . $phreakpic_table_prefix . "cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `current_rating` smallint(6) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `catgroup_id` int(11) NOT NULL default '0',
  `is_serie` enum('0','1') NOT NULL default '0',
  `content_amount` tinyint(4) NOT NULL default '0',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "content`
#

CREATE TABLE `" . $phreakpic_table_prefix . "content` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `file` text NOT NULL,
  `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `locked` enum('0','1') NOT NULL default '0',
  `contentgroup_id` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `current_rating` tinyint(4) NOT NULL default '0',
  `width` smallint(4) NOT NULL default '0',
  `height` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "content_auth`
#

CREATE TABLE `" . $phreakpic_table_prefix . "content_auth` (
  `usergroup_id` int(11) NOT NULL default '0',
  `contentgroup_id` int(11) NOT NULL default '0',
  `view` enum('0','1') NOT NULL default '1',
  `delete` enum('0','1') NOT NULL default '0',
  `edit` enum('0','1') NOT NULL default '0',
  `comment_edit` enum('0','1') NOT NULL default '0',
  `add_to_group` enum('0','1') NOT NULL default '0',
  `remove_from_group` enum('0','1') NOT NULL default '0'
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "content_comments`
#

CREATE TABLE `" . $phreakpic_table_prefix . "content_comments` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) default NULL,
  `feedback` text NOT NULL,
  `user_id` int(11) default NULL,
  `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `changed_count` smallint(6) NOT NULL default '0',
  `parent_id` smallint(6) NOT NULL default '0',
  `topic` text NOT NULL,
  `last_changed_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "content_in_cat`
#

CREATE TABLE `" . $phreakpic_table_prefix . "content_in_cat` (
  `cat_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `place_in_cat` mediumint(9) NOT NULL default '65536'
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "content_ratings`
#

CREATE TABLE `" . $phreakpic_table_prefix . "content_ratings` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `feedback` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "contentgroups`
#

CREATE TABLE `" . $phreakpic_table_prefix . "contentgroups` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "user_in_group`
#

CREATE TABLE `" . $phreakpic_table_prefix . "user_in_group` (
  `user_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0'
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "usergroups`
#

CREATE TABLE `" . $phreakpic_table_prefix . "usergroups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM
"; 


$query[] = "

#
# Table structure for table `" . $phreakpic_table_prefix . "views`
#

CREATE TABLE `" . $phreakpic_table_prefix . "views` (
  `user_id` int(11) default '0',
  `content_id` int(11) default '0',
  `start` datetime NOT NULL default '0000-00-00 00:00:00',
  `end` datetime NOT NULL default '0000-00-00 00:00:00'
) TYPE=MyISAM
";

$query[] = "
	ALTER TABLE `users` ADD `" . $phreakpic_table_prefix . "user_template` VARCHAR(50) DEFAULT '$default_template' NOT NULL
";

?>