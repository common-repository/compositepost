<?php
/*
Plugin Name: Composite Post
Plugin URI: http://rutkoski.wordpress.com/
Description: Compose a post or page with other posts or pages. Usage: [composite option="value"]. Use on or more options from the get_posts() function (http://codex.wordpress.org/Template_Tags/get_posts). The plugin tries to avoid infinit recursion, but it could happen.
Author: Rodrigo Rutkoski Rodrigues
Version: 0.3
Author URI: http://rutkoski.wordpress.com/
*/

// http://codex.wordpress.org/Template_Tags/get_posts
// http://codex.wordpress.org/Template_Tags/get_posts#Parameters:_WordPress_2.6.2B

function composite_do($content)
{
  global $wp_query;
  
  $args = array('exclude' => $wp_query->post->ID);

  preg_match_all('/(\[ *composite +(.+) *\])/im', $content, $matches);

  if (count($matches)) {
    foreach ($matches[1] as $match) {
      $_content = composite_parse($match, $args);
      $content = str_replace($match, $_content, $content);
    }
  }

  return $content;
}

function composite_parse($match, $args = array())
{
  preg_match_all('/([^= ]+)="([^"]+)"/i', $match, $options);

  $query = array();
  foreach ($options[1] as $k=>$type) {
    $query[$type] = $options[2][$k];
  }

  $r = wp_parse_args($query);
  if ( empty( $r['post_status'] ) )
		$r['post_status'] = ( 'attachment' == $r['post_type'] ) ? 'inherit' : 'publish';
	if ( ! empty($r['numberposts']) )
		$r['posts_per_page'] = $r['numberposts'];
	if ( ! empty($r['category']) )
		$r['cat'] = $r['category'];
	if ( ! empty($r['include']) ) {
		$incposts = preg_split('/[\s,]+/',$r['include']);
		$r['posts_per_page'] = count($incposts);  // only the number of posts included
		$r['post__in'] = $incposts;
	} elseif ( ! empty($r['exclude']) ) {
		$r['post__not_in'] = preg_split('/[\s,]+/',$r['exclude']);
  }

  if (empty($r['exclude']) ) {
    $r['post__not_in'] = array($args['exclude']);
  } else {
    $excposts = preg_split('/[\s,]+/',$r['exclude']);
    $excposts[] = $args['exclude'];
    $r['post__not_in'] = $excposts;
  }

  $r['caller_get_posts'] = true;

  $get_posts = new WP_Query;
	$posts = $get_posts->query($r);

  $tpl = WP_PLUGIN_DIR.'/compositepost/';
  if (!empty($query['template'])) {
    $tpl .= $query['template'].'.php';
  } else {
    $tpl .= 'default.php';
  }

  if (!is_readable($tpl))
    return sprintf('<p>Template file for CompositePost not found: <b>%s</b></p>', $tpl);

  global $more;

  $_content = '';
  while ($get_posts->have_posts()) {
    $get_posts->the_post();
    $more = 0;
    ob_start();
    include($tpl);
    $_content .= ob_get_clean();
  }

  return $_content;
}

add_filter('the_content', 'composite_do');

function pre($o)
{
  echo '<pre>';
  print_r($o);
  echo '</pre>';
}

?>