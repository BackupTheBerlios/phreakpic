/* -------------------------------------------------------- 
  phpPgAdmin 2.4.2 DB Dump
  http://sourceforge.net/projects/phppgadmin/
  Host: :5432
  Database  : "phpbb2"
  2002-10-25 20:10:43
-------------------------------------------------------- */ 

/* -------------------------------------------------------- 
  Sequences 
-------------------------------------------------------- */ 
CREATE SEQUENCE "photo_catgroups_id_seq" START 1 INCREMENT 1 MAXVALUE 2147483647 MINVALUE 1 CACHE 1; 
CREATE SEQUENCE "photo_contentgroups_id_seq" START 1 INCREMENT 1 MAXVALUE 2147483647 MINVALUE 1 CACHE 1; 
CREATE SEQUENCE "photo_usergroups_id_seq" START 1 INCREMENT 1 MAXVALUE 2147483647 MINVALUE 1 CACHE 1; 
CREATE SEQUENCE "photo_cat_comments_id_seq" START 1 INCREMENT 1 MAXVALUE 2147483647 MINVALUE 1 CACHE 1; 
CREATE SEQUENCE "photo_content_comments_id_seq" START 1 INCREMENT 1 MAXVALUE 2147483647 MINVALUE 1 CACHE 1; 
CREATE SEQUENCE "photo_cats_id_seq" START 1 INCREMENT 1 MAXVALUE 2147483647 MINVALUE 1 CACHE 1; 
CREATE SEQUENCE "photo_content_id_seq" START 1 INCREMENT 1 MAXVALUE 2147483647 MINVALUE 1 CACHE 1; 



/* -------------------------------------------------------- 
  Table structure for table "photo_cat_auth" 
-------------------------------------------------------- */
CREATE TABLE "photo_cat_auth" (
   "usergroup_id" int4 NOT NULL,
   "catgroup_id" int4 NOT NULL,
   "view" bit DEFAULT '0' NOT NULL,
   "delete" bit DEFAULT '0' NOT NULL,
   "edit" bit DEFAULT '0' NOT NULL,
   "cat_add" bit DEFAULT '0' NOT NULL,
   "content_add" bit DEFAULT '0' NOT NULL,
   "content_remove" bit DEFAULT '0' NOT NULL,
   "cat_remove" bit DEFAULT '0' NOT NULL,
   "comment_edit" bit DEFAULT '0' NOT NULL,
   "add_to_group" bit DEFAULT '0' NOT NULL,
   "remove_from_group" bit DEFAULT '0' NOT NULL
);


/* -------------------------------------------------------- 
  Table structure for table "photo_cat_comment" 
-------------------------------------------------------- */
CREATE TABLE "photo_cat_comments" (
   "id" int8 DEFAULT nextval('photo_cat_comments_id_seq'::text) NOT NULL,
   "owner_id" int8,
   "feedback" text,
   "user_id" int8,
   "creation_date" timestamp DEFAULT now(),
   "changed_count" int4,
   "parent_id" int8,
   "topic" text,
   "last_changed_date" timestamp DEFAULT now(),
   CONSTRAINT "photo_cat_comment_pkey" PRIMARY KEY ("id")
);
CREATE  INDEX "photo_cat_comment_id_key" ON "photo_cat_comments" ("id");


/* -------------------------------------------------------- 
  Table structure for table "photo_catgroups" 
-------------------------------------------------------- */
CREATE TABLE "photo_catgroups" (
   "id" int8 DEFAULT nextval('photo_catgroups_id_seq'::text) NOT NULL,
   "name" text NOT NULL,
   "description" text NOT NULL,
   CONSTRAINT "photo_catgroups_pkey" PRIMARY KEY ("id")
);
CREATE  INDEX "photo_catgroups_id_key" ON "photo_catgroups" ("id");


/* -------------------------------------------------------- 
  Table structure for table "photo_cats" 
-------------------------------------------------------- */
CREATE TABLE "photo_cats" (
   "id" int8 DEFAULT nextval('photo_cats_id_seq'::text) NOT NULL,
   "name" varchar(200) NOT NULL,
   "current_rating" int2 NOT NULL,
   "parent_id" int8 NOT NULL,
   "catgroup_id" int8 NOT NULL,
   "is_serie" bit DEFAULT '0' NOT NULL,
   "content_amount" int2 NOT NULL,
   "description" text NOT NULL,
   CONSTRAINT "photo_cats_pkey" PRIMARY KEY ("id")
);
CREATE  INDEX "photo_cats_id_key" ON "photo_cats" ("id");


/* -------------------------------------------------------- 
  Table structure for table "photo_content" 
-------------------------------------------------------- */
CREATE TABLE "photo_content" (
   "id" int8 DEFAULT nextval('photo_content_id_seq'::text) NOT NULL,
   "name" varchar(50) NOT NULL,
   "file" text NOT NULL,
   "creation_date" timestamp DEFAULT now() NOT NULL,
   "locked" bit DEFAULT '0' NOT NULL,
   "contentgroup_id" int8 NOT NULL,
   "views" int4 NOT NULL,
   "current_rating" int2 NOT NULL,
   "width" int4 NOT NULL,
   "height" int4 NOT NULL,
   CONSTRAINT "photo_content_pkey" PRIMARY KEY ("id")
);
CREATE  INDEX "photo_content_id_key" ON "photo_content" ("id");


/* -------------------------------------------------------- 
  Table structure for table "photo_content_auth" 
-------------------------------------------------------- */
CREATE TABLE "photo_content_auth" (
   "usergroup_id" int8 NOT NULL,
   "contentgroup_id" int8 NOT NULL,
   "view" bit DEFAULT '0' NOT NULL,
   "delete" bit DEFAULT '0' NOT NULL,
   "edit" bit DEFAULT '0' NOT NULL,
   "comment_edit" bit DEFAULT '0' NOT NULL,
   "add_to_group" bit DEFAULT '0' NOT NULL,
   "remove_from_group" bit DEFAULT '0' NOT NULL
);


/* -------------------------------------------------------- 
  Table structure for table "photo_content_comments" 
-------------------------------------------------------- */
CREATE TABLE "photo_content_comments" (
   "id" int8 DEFAULT nextval('photo_cat_comments_id_seq'::text) NOT NULL,
   "owner_id" int8 NOT NULL,
   "feedback" text NOT NULL,
   "user_id" int8 NOT NULL,
   "creation_date" timestamp DEFAULT now() NOT NULL,
   "changed_count" int4 NOT NULL,
   "parent_id" int8 NOT NULL,
   "topic" text NOT NULL,
   "last_changed_date" timestamp DEFAULT now() NOT NULL,
   CONSTRAINT "photo_content_comments_pkey" PRIMARY KEY ("id")
);
CREATE  INDEX "photo_content_comments_id_key" ON "photo_content_comments" ("id");


/* -------------------------------------------------------- 
  Table structure for table "photo_content_in_cat" 
-------------------------------------------------------- */
CREATE TABLE "photo_content_in_cat" (
   "cat_id" int8 NOT NULL,
   "content_id" int8 NOT NULL,
   "place_in_cat" int4 NOT NULL
);


/* -------------------------------------------------------- 
  Table structure for table "photo_contentgroups" 
-------------------------------------------------------- */
CREATE TABLE "photo_contentgroups" (
   "id" int8 DEFAULT nextval('photo_contentgroups_id_seq'::text) NOT NULL,
   "name" text NOT NULL,
   "description" text NOT NULL,
   CONSTRAINT "photo_contentgroups_pkey" PRIMARY KEY ("id")
);
CREATE  INDEX "photo_contentgroups_id_key" ON "photo_contentgroups" ("id");


/* -------------------------------------------------------- 
  Table structure for table "photo_user_in_group" 
-------------------------------------------------------- */
CREATE TABLE "photo_user_in_group" (
   "user_id" int8 NOT NULL,
   "group_id" int8 NOT NULL
);


/* -------------------------------------------------------- 
  Table structure for table "photo_usergroups" 
-------------------------------------------------------- */
CREATE TABLE "photo_usergroups" (
   "id" int8 DEFAULT nextval('photo_usergroups_id_seq'::text) NOT NULL,
   "name" text NOT NULL,
   "description" text NOT NULL,
   CONSTRAINT "photo_usergroups_pkey" PRIMARY KEY ("id")
);
CREATE  INDEX "photo_usergroups_id_key" ON "photo_usergroups" ("id");


/* -------------------------------------------------------- 
  Table structure for table "photo_views" 
-------------------------------------------------------- */
CREATE TABLE "photo_views" (
   "user_id" int8 NOT NULL,
   "content_id" int8 NOT NULL,
   "start" timestamp DEFAULT now() NOT NULL,
   "end" timestamp DEFAULT now() NOT NULL
);



/* No Views found */

/* No Functions found */

/* No Triggers found */
