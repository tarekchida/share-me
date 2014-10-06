<?php

/**
 * @package Share-me
 */
/*
  Plugin Name: Share-me
  Plugin URI:https://github.com/tarekchida/share-me
  Description: Share posts on multiple Social Networks, different icons sets
  Author: Tarek Chida
  Author URI: http://tarek-chida.url.ph/
  Version: 1.0.1
 */

$siteurl = get_option('siteurl');
define('SM_FOLDER', dirname(plugin_basename(__FILE__)));
define('SM_URL', $siteurl . '/wp-content/plugins/' . SM_FOLDER);
define('SM_FILE_PATH', dirname(__FILE__));
define('SM_THEMES_PATH', SM_FILE_PATH . '/images/');
wp_enqueue_style('sm_style', SM_URL . '/css/style.css');
wp_enqueue_script('sm_script', SM_URL . '/js/scripts.js');


global $wpdb;
$pro_table_prefix = $wpdb->prefix . 'sm_';
define('SM_TABLE_PREFIX', $pro_table_prefix);

register_activation_hook(__FILE__, 'sm_install');
register_deactivation_hook(__FILE__, 'sm_uninstall');

function sm_install() {
    global $wpdb;
    $table = SM_TABLE_PREFIX . "social_list";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        name VARCHAR(80) NOT NULL,
        status INT NOT NULL, 
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
    // Populate table 
    $wpdb->query("INSERT INTO $table  (`id`, `name`, `status`) VALUES (NULL, 'facebook', '1');");
    $wpdb->query("INSERT INTO $table  (`id`, `name`, `status`) VALUES (NULL, 'twitter', '1');");
    $wpdb->query("INSERT INTO $table  (`id`, `name`, `status`) VALUES (NULL, 'googleplus', '1');");
    $wpdb->query("INSERT INTO $table  (`id`, `name`, `status`) VALUES (NULL, 'tumblr', '1');");
    $wpdb->query("INSERT INTO $table  (`id`, `name`, `status`) VALUES (NULL, 'linkedin', '1');");
    $wpdb->query("INSERT INTO $table  (`id`, `name`, `status`) VALUES (NULL, 'flickr', '1');");
    $wpdb->query("INSERT INTO $table  (`id`, `name`, `status`) VALUES (NULL, 'blogger', '1');");



    $table = SM_TABLE_PREFIX . "config";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        theme VARCHAR(32) NOT NULL,
        h_pos VARCHAR(32) NOT NULL,
        v_pos VARCHAR(32) NOT NULL,
        size INT NOT NULL,        
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
    $wpdb->query("INSERT INTO $table  (`id`, `theme`, `h_pos`, `v_pos`, `size`) VALUES (NULL, 'cercle', 'left' , 'up', 32);");
}

function sm_uninstall() {
    global $wpdb;
    $table = SM_TABLE_PREFIX . "config";
    $structure = "drop table if exists $table";
    $wpdb->query($structure);

    $table = SM_TABLE_PREFIX . "social_list";
    $structure = "drop table if exists $table";
    $wpdb->query($structure);
}

add_action('admin_menu', 'sm_admin_menu');

function sm_admin_menu() {
    add_menu_page(
            "Share Me", "Share Me", 8, __FILE__, "sm_admin_menu_list", SM_URL . "/css/images/logo_small.png"
    );
}

function sm_admin_menu_list() {
    include 'admin-share-me.php';
}

function sm_getSocialShare($content) {

    global $wpdb;
    $sm_theme = "circle";
    $sm_h_pos = "right";
    $sm_v_pos = "up";
    $sm_size = "32";
    $social_list = array();

    $sql = "SELECT *FROM " . SM_TABLE_PREFIX . "config where  1";
    $data = $wpdb->get_results($sql);
    foreach ($data as $item) {

        $sm_theme = $item->theme;
        $sm_h_pos = $item->h_pos;
        $sm_v_pos = $item->v_pos;
        $sm_size = $item->size;
    }



    $sql = "SELECT *FROM " . SM_TABLE_PREFIX . "social_list where  1";
    $socials = $wpdb->get_results($sql);
    if (count($socials) > 0) {
        foreach ($socials as $social) {
            $social_list[] = $social;
        }
    }



    $path = SM_URL . '/images/' . $sm_theme . '/';
    $shares = "<div id='share-me'><ul class='share-" . $sm_h_pos . "'>";


    foreach ($social_list as $item) {

        if ($item->status == 0)
            continue;

        $shares.='<li>';

        $shares.=sm_getLink($item->name);
        $shares.='<img alt = "" src = "' . $path . $item->name . '.png"  height="' . $sm_size . 'px"  width="' . $sm_size . 'px">';
        $shares.='</a>';
        $shares.='</li>';
    }
    $shares .= "</ul></div><br/>";
    if ($sm_v_pos == 'up') {
        return $shares . $content;
    } else {
        return $content . $shares;
    }
}

add_filter('the_content', 'sm_getSocialShare');

function sm_getLink($type) {
    global $post;
    switch ($type) {
        case 'facebook': return '<a href="http://www.facebook.com/sharer.php?u=' . apply_filters("the_permalink", get_permalink()) . '&t=' . urlencode(get_the_title()) . '" alt="Share on Facebook" title="Share on Facebook"   onclick="return smWindowpop(this.href, 545, 433)">';
            break;
        case 'twitter': return '<a href="http://twitter.com/share?text=' . urlencode(get_the_title()) . '-&url=' . apply_filters("the_permalink", get_permalink()) . '&via=StadeFrance" alt="Tweet This Post" title="Tweet This Post"  onclick="return smWindowpop(this.href, 545, 433)">';
            break;
        case 'googleplus': return '<a href="https://plusone.google.com/_/+1/confirm?hl=fr-FR&url=' . apply_filters("the_permalink", get_permalink()) . '" alt="Share on Google+" title="Share on Google+"  target="_blank"onclick="return smWindowpop(this.href, 545, 433)">';
            break;
        case 'tumblr': $thumbID = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
            return '<a href="http://www.tumblr.com/share/photo?source=' . urlencode($thumbID[0]) . '&caption=' . urlencode(get_the_title()) . '&clickthru=' . urlencode(get_permalink()) . '" title="Share on Tumblr"  onclick="return smWindowpop(this.href, 545, 433)"> ';
            break;
        case 'linkedin': return '<a href="http://www.linkedin.com/shareArticle?mini=true&url=' . apply_filters("the_permalink", get_permalink()) . '&title=' . urlencode(get_the_title()) . '&source=Stadefrance" onclick="return smWindowpop(this.href, 545, 433)">';
            break;
        default: return '';
            break;
    }
}

function sm_get_fbimage() {
    global $post;

    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'post_parent' => $post->ID
    );
    $images = get_posts($args);
    foreach ($images as $image) {
        $src[] = wp_get_attachment_url($image->ID, array(120, 120));
    }

    $fbimage = $src [0];

    if (empty($fbimage)) {
        $fbimage = SM_URL . '/css/images/logo_big.png';
    }
    echo '<meta property="og:image" content="' . $fbimage . '"/>';
}

add_action('wp_head', 'sm_get_fbimage', 5);
