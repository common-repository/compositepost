=== Composite Post ===
Contributors: rutkoski
Donate link: http://rutkoski.wordpress.com/
Tags: Post, page
Requires at least: 2.0.2
Tested up to: 2.1
Stable tag: trunk

Compose a post or page with other posts or pages.

== Description ==

CompositePost let’s you insert posts/pages inside other posts/pages.

== Usage ==

Write anywhere on a post:

[composite option="value"]

You can specify one or more options. The options are the same used in the get_posts() function (see http://codex.wordpress.org/Template_Tags/get_posts).

== Example ==

Show all posts from category Projects and include post with ID = 1:

[composite category_name="Projects" include="1"]

== Templates ==

CompositePost create a loop using a template located in the plugin’s folder (usually /wordpress/wp-content/plugins/composite_post). The default template is /wordpress/wp-content/plugins/composite_post/default.php. You can have as many templates as you want, just specify wich one to use, like this:

[composite category_name="Projects" template="my_template"]

CompositePost will look for the file at /wordpress/wp-content/plugins/composite_post/my_template.php

== Installation ==

1. Upload plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
