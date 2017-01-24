=== MultiSite Clone Duplicator ===
Contributors: pdargham, julienog, daviddaug, globalis
Tags: duplicate, clone, copy, duplication, duplicator, factory, multisite, site, blog, network, wpmu, new blog
Requires at least: 4.0.0
Tested up to: 4.7.1
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Clones an existing site into a new one in a multisite installation : copies all posts, settings and files

== Description ==

MultiSite Clone Duplicator adds a "Duplicate Site" functionality to your network installation.  

It allows you to clone any site of your network into a new one : all data, files, users and roles can be copied.  

It is useful when you want to create multiple sites from the same template : Don't waste your time copying the same configuration again and again !  
  
Simple and user-friendly, this plugin extends WordPress core network's functionalities without polluting the dashboard.

WARNING : If you clone the primary site, you must use ```mucd_default_primary_tables_to_copy``` filter to declare plugins and custom database tables, or your cloned site won't be complete

= Features: =
* Clones any site of your wordpress multisite installation
* Copies all posts and settings
* Generates log files (if option is checked)
* Copy all files from duplicated site (if option is checked)
* Keep users and roles from duplicated site (if option is checked)
* Configure which site is clonable (so you can define an unique "pattern" site)
* Fully hookable
* Command line ready (provides a WP-CLI subcommand)

== Installation ==

You can install MultiSite Clone Duplicator using the built in WordPress plugin installer. It’s easy, 2 seconds.

If you prefer download MultiSite Clone Duplicator manually :

1. Upload multisite-clone-duplicator/ to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. (Optional) Chmod 777 the logs/ directory of the plugin, if you want to activate logs
4. Go to My Sites > Network Admin > Duplication and enjoy !
5. (Optional) Change default options into Network dashboard > Network settings > Duplication

In the future, you'll probably want to create a dedicated "template" blog to clone from.

== Frequently Asked Questions ==

= How does it work ? =
* It creates a new user if the email was not an existing email
* It creates a new blog with appropriate title and admin user
* It copies all tables from cloned site, but keep some options (like title, domain, etc) of the new blog
* It searches and replaces old site's URL and DOMAINS with the new ones
* It copies upload directory from the old site to the upload directory of the new one (if option is checked)
* It imports users and roles from the old site to the new one (if option is checked)

= Does it support subdirectory AND subdomain installations ? = 
Yes, it supports both !

= Can I clone the primary site ? = 
Yes you can, but you want to be careful : WordPress saves network tables and primary blog tables with the same prefix, and some of their data are mixed. It forces us to restrict primary blog cloning to copy only the default wp tables. If you want to change this (for example, include your plugin tables in the cloning), use mucd_default_primary_tables_to_copy filter. In the future, you want probably not to copy again and again the primary blog : use a "template" blog dedicated to clonage instead.

= Does it clone plugins settings ? = 
Yes it does !

= But some data are serialized ? =
It's not a problem ! Serialized data are understood by the plugin, recursively unserialized, replaced with appropriate values, and serialized again.

= After cloning, new site was created, but it goes on 404 page, why ? =
Check your host / server configuration : you probably cloned your site into a domain that is not available !

= How to duplicate with command line commands ? =
Install [WP-CLI](http://wp-cli.org/), go to your wordpress multisite directory, and type `wp-cli site duplicate --source=<id_of_the_site_to_clone> --slug="<slug_of_the_new_site>"`

Arguments are : `wp site duplicate --slug=<slug> --source=<site_id> [--title=<title>]
  [--email=<email>] [--network_id=<network-id>] [--private] [--porcelain] [--v]
  [--do_not_copy_files] [--keep_users] [--log=<dir_path>]`

= Which languages are currently supported? = 
As of now, following languages are supported : English (en_US), French (fr_FR), Spanish (es_ES), Lithuanian (lt_LT) and Greek (el). If you wish to, you can translate the interface in your own language in the [standard WordPress way](http://codex.wordpress.org/Translating_WordPress) or with [Transifex](https://www.transifex.com/projects/p/multisite-clone-duplicator/)

= GLOBALIS what ? =
[Globalis media systems](http://www.globalis-ms.com/) is a web IT consulting company based in Paris, and a pioneer of the PHP and LAMP platform. Since 1997, we have been designing, making and maintaining Internet, intranet or mobile software. We have been working with open source CMS since 2000 and have regularly been using WordPress since 2007.

== Screenshots ==

1. **Basic features**
2. **Advanced features**
3. **Settings**
4. **Successfull duplication**
5. **Log warning**

== Changelog ==

= 1.4.1 =
* Fix wp_cli activation

= 1.4.0 =
* Added select2 support (Ajax dropdown when selecting site)
* Restrict activate to network admin only (network admin area)
* Default logs path moved in upload dir
* Added language zh_CN
* Bugfix : SSL compatibility

= 1.3.3 =
* Bugfix : Compatibility : WordPress 4.7 (wp_get_sites was deprecated)

= 1.3.2 =
* Bugfix : Check on admin referer broke some admin page

= 1.3.1 =
* Bugfix : Compatibility with PHP 5.2

= 1.3.0 =
* Added wp-cli site duplicate subcommand
* Added default options in network settings pannel
* Languages : added translation for spanish, lithuanian and greek
* Bugfix : Using backtricks on CREATE TABLE LIKE
* Bugfix : Remove HyperDB compat. : it made some bug on schema / table selection

= 1.2.0 =
* Bugfix : duplication of tables without primary key / with several primary keys was causing SQL error
* Bugfix : escape underscore characters ( '_' ) in sql queries

= 1.1.0 =
* Bugfix : "Keep users and roles" was broken
* Bugfix : Compatibility with plugins that use reserved mysql words in table names
* Bugfix : Partial compatibility with HyperDB

= 1.0.0 =
* Tested on WP 4.0.0
* Bugfix : Compatibility with PHP 5.2
* Bugfix : SQL Error replace mysql_real_escape_string by $wpdb->prepare
* Bugfix : unable to delete, deactivate, etc. site when plugin is active
* Bugfix : Fields in duplicate form lost information after validate with error

= 0.2.0 =
* First public version released by Pierre Dargham
* Generates logs
* Primary site is clonable
* Auto-suggest for admin email
* Keep users and roles from duplicated site
* Translating
* Hookable

= 0.1.0 =
* Initial version released by Julien Oger
* Copies all the posts, settings and files from a site to a new one
* Cannot clone primary site

== Hooks ==
  
---------------------------------------
= Action : mucd_before_copy_files / mucd_after_copy_files =
Action before / after copying files  
**Args :**

  1. Int : from_site_id
  2. Int : to_site_id
  
---------------------------------------
= Action : mucd_before_copy_data / mucd_after_copy_data =
Action before / after copying data  
**Args :**

  1. Int : from_site_id
  2. Int : to_site_id
  
---------------------------------------
= Action : mucd_before_copy_users / mucd_after_copy_users =
Action before / after copying users  
**Args :**

  1. Int : from_site_id
  2. Int : to_site_id
  
---------------------------------------
= Filter : mucd_copy_blog_data_saved_options =
Filter options that should be preserved in the new blog (original values from created blog will not be erased by copy of old site's tables)  
**Args :**

  1. Array of string : option_name
  
---------------------------------------
= Filter : mucd_default_fields_to_update =
Filter fields to scan for an update after data copy  
**Args :**

  1. Array of ( 'table_name' => array('field_1', 'field_2' ...));
  
---------------------------------------
= Filter : mucd_default_primary_tables_to_copy =
Filter tables to duplicate when duplicated site is primary site  
**Args :**

  1. Array of string table_name
  
---------------------------------------
= Filter : mucd_copy_dirs =
Filter directories and files you want to copy  
**Args :**

  1. Array of string : dirs
  2. Int : from_site_id
  3. Int : to_site_id
  
---------------------------------------
= Filter : mucd_string_to_replace =
Filter which strings we want to replace during update  
**Args :**

  1. String : string_to_replace
  2. Int : from_site_id
  3. Int : to_site_id
  
---------------------------------------
  
== WP-CLI arguments ==

Arguments are :

`wp site duplicate --slug=<slug> --source=<site_id> [--title=<title>]
  [--email=<email>] [--network_id=<network-id>] [--private] [--porcelain] [--v]
  [--do_not_copy_files] [--keep_users] [--log=<dir_path>]`

== Thank’s ==

The original version of this plugin has been developed by [Julien OGER](https://github.com/julienOG) who keeps following the project carefully.  

Some code for search and replace in SQL serialised data were initialy taken from [Lionel Pointet Wordpress Migration tool](https://github.com/lpointet/wordpress_migration)
