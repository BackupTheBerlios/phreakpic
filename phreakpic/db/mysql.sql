# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Mar 24, 2003 at 03:33 PM
# Server version: 3.23.44
# PHP Version: 4.3.0
# Database : `sauna`
# --------------------------------------------------------

#
# Table structure for table `phreakpic_cat_auth`
#

CREATE TABLE `phreakpic_cat_auth` (
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
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_cat_comments`
#

CREATE TABLE `phreakpic_cat_comments` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) default NULL,
  `feedback` text NOT NULL,
  `user_id` int(11) default NULL,
  `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `changed_count` smallint(6) NOT NULL default '0',
  `parent_id` smallint(6) NOT NULL default '0',
  `topic` text NOT NULL,
  `last_changed_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `poster_name` varchar(25) default NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_cats`
#

CREATE TABLE `phreakpic_cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `current_rating` smallint(6) NOT NULL default '0',
  `parent_id` int(11) default '0',
  `catgroup_id` int(11) NOT NULL default '0',
  `is_serie` enum('0','1') NOT NULL default '0',
  `content_amount` smallint(4) NOT NULL default '0',
  `description` text NOT NULL,
  `child_content_amount` mediumint(9) NOT NULL default '0',
  `child_comments_amount` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_content`
#

CREATE TABLE `phreakpic_content` (
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
  `comments_amount` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_content_auth`
#

CREATE TABLE `phreakpic_content_auth` (
  `usergroup_id` int(11) NOT NULL default '0',
  `contentgroup_id` int(11) NOT NULL default '0',
  `view` enum('0','1') NOT NULL default '1',
  `delete` enum('0','1') NOT NULL default '0',
  `edit` enum('0','1') NOT NULL default '0',
  `comment_edit` enum('0','1') NOT NULL default '0',
  `add_to_group` enum('0','1') NOT NULL default '0',
  `remove_from_group` enum('0','1') NOT NULL default '0',
  `edit_meta_data` enum('0','1') NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_content_comments`
#

CREATE TABLE `phreakpic_content_comments` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) default NULL,
  `feedback` text NOT NULL,
  `user_id` int(11) default NULL,
  `creation_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `changed_count` smallint(6) NOT NULL default '0',
  `parent_id` smallint(6) NOT NULL default '0',
  `topic` text NOT NULL,
  `last_changed_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `poster_name` varchar(25) default NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_content_in_cat`
#

CREATE TABLE `phreakpic_content_in_cat` (
  `cat_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `place_in_cat` mediumint(9) NOT NULL default '65536'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_content_meta_data`
#

CREATE TABLE `phreakpic_content_meta_data` (
  `id` int(11) NOT NULL auto_increment,
  `meta_field_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_content_meta_fields`
#

CREATE TABLE `phreakpic_content_meta_fields` (
  `id` int(11) NOT NULL auto_increment,
  `fieldname` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_content_ratings`
#

CREATE TABLE `phreakpic_content_ratings` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `feedback` int(11) NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_custom_searches`
#

CREATE TABLE `phreakpic_custom_searches` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `xml` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_error_reports`
#

CREATE TABLE `phreakpic_error_reports` (
  `id` int(11) NOT NULL auto_increment,
  `FILE` varchar(100) NOT NULL default '',
  `line` int(11) NOT NULL default '0',
  `sql` text NOT NULL,
  `ident` varchar(50) NOT NULL default '',
  `COMMENT` text NOT NULL,
  `TYPE` tinyint(30) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `query_string` text NOT NULL,
  `error_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_groups`
#

CREATE TABLE `phreakpic_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_user_in_group`
#

CREATE TABLE `phreakpic_user_in_group` (
  `user_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_usergroup_auth`
#

CREATE TABLE `phreakpic_usergroup_auth` (
  `usergroup_id` int(11) NOT NULL default '0',
  `usergroupgroup_id` int(11) NOT NULL default '0',
  `add_user` enum('0','1') NOT NULL default '0',
  `remove_user` enum('0','1') NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_usergroups`
#

CREATE TABLE `phreakpic_usergroups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phreakpic_views`
#

CREATE TABLE `phreakpic_views` (
  `user_id` int(11) default '0',
  `content_id` int(11) default '0',
  `start` datetime NOT NULL default '0000-00-00 00:00:00',
  `end` datetime NOT NULL default '0000-00-00 00:00:00',
  KEY `content_id` (`content_id`)
) TYPE=MyISAM;

