<?php

/**
 * @package Share-me
 */
/*
  Plugin Name: ShareMe
  Plugin URI:https://github.com/tarekchida/share-me
  Description: Share WP posts on multiple Social Networks with different icons sets
  Author: Tarek Chida
  Author URI: http://tchida.net
  License: GPLv2 or later
  Version: 1.4.0
 */

define('SM_FOLDER', dirname(plugin_basename(__FILE__)));
define('SM_URL', plugin_dir_url(__FILE__));
define('SM_FILE_PATH', plugin_dir_path(__FILE__));
define('SM_THEMES_PATH', SM_FILE_PATH . '/assets/images/');

global $wpdb;
$pro_table_prefix = $wpdb->prefix . 'sm_';
define('SM_TABLE_PREFIX', $pro_table_prefix);

//plugin install/uninstall
register_activation_hook(__FILE__, array('shareMe', 'sm_activation'));
register_deactivation_hook(__FILE__, array('shareMe', 'sm_deactivation'));

require_once( SM_FILE_PATH . 'class.shareMe.php' );

add_action('wp_enqueue_scripts', array('shareMe', 'sm_add_style_script'));
add_action('admin_menu', array('shareMe', 'sm_admin_menu'));
add_filter('the_content', array('shareMe', 'sm_getSocialShare'));
add_action('wp_head', array('shareMe', 'sm_get_post_image'), 5);
add_action('admin_enqueue_scripts', array('shareMe', 'sm_admin_style_script'));

