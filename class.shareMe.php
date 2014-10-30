<?php

class shareMe {

    static $popinWidth = 545;
    static $popinHight = 433;

    /*
     *  PLUGIN INSTALL 
     */

    public static function sm_activation() {

        global $wpdb;
        $table = SM_TABLE_PREFIX . "social_list";
        $structure = "CREATE TABLE  IF NOT EXISTS  $table (
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
        $structure = "CREATE TABLE  IF NOT EXISTS  $table (
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

    /*
     *  PLUGIN UNINSTALL 
     */

    public static function sm_deactivation() {

        global $wpdb;
        $table = SM_TABLE_PREFIX . "config";
        $structure = "drop table if exists $table";
        $wpdb->query($structure);

        $table = SM_TABLE_PREFIX . "social_list";
        $structure = "drop table if exists $table";
        $wpdb->query($structure);
    }

    /*
     * Add plugin's Css and Js to head
     */

    public static function sm_add_style_script() {
        wp_enqueue_style('sm_style', plugins_url('/assets/css/style.css', __FILE__));
        wp_enqueue_script('sm_script', plugins_url('/assets/js/scripts.js', __FILE__));
    }

    public static function sm_getSocialShare($content) {

        global $wpdb;
        $sm_theme = "circle";
        $sm_h_pos = "right";
        $sm_v_pos = "up";
        $sm_size = "32";
        $socialList = array();

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
                $socialList[] = $social;
            }
        }

        $path = SM_URL . '/assets/images/' . $sm_theme . '/';

        $shares = self::sm_createShareButtons($socialList, $path, $sm_h_pos, $sm_size);

        if ($sm_v_pos == 'up') {
            return $shares . $content;
        } else {
            return $content . $shares;
        }
    }

    /*
     * GENERATE SHARE BUTTONS
     */

    public static function sm_createShareButtons($socialList, $path, $sm_h_pos, $sm_size) {

        $shares = "<div id='share-me'><ul class='share-" . $sm_h_pos . "'>";
        foreach ($socialList as $item) {

            if ($item->status == 0) {
                continue;
            }

            $shares.='<li>';
            $shares.=self::sm_getLink($item->name);
            $shares.='<img alt = "" src = "' . $path . $item->name . '.png"  height="' . $sm_size . 'px"  width="' . $sm_size . 'px">';
            $shares.='</a>';
            $shares.='</li>';
        }
        return $shares .= "</ul></div><br/>";
    }

    public static function sm_getLink($type) {
        global $post;
        switch ($type) {
            case 'facebook': return '<a href="http://www.facebook.com/sharer.php?u=' . apply_filters("the_permalink", get_permalink()) . '&t=' . urlencode(get_the_title()) . '" alt="Share on Facebook" title="Share on Facebook"   onclick="return smWindowpop(this.href,' . self::$popinWidth . ',' . self:: $popinHight . ')">';
                break;
            case 'twitter': return '<a href="http://twitter.com/share?text=' . urlencode(get_the_title()) . '-&url=' . apply_filters("the_permalink", get_permalink()) . '&via=StadeFrance" alt="Tweet This Post" title="Tweet This Post"  onclick="return smWindowpop(this.href,' . self::$popinWidth . ',' . self::$popinHight . ')">';
                break;
            case 'googleplus': return '<a href="https://plusone.google.com/_/+1/confirm?hl=fr-FR&url=' . apply_filters("the_permalink", get_permalink()) . '" alt="Share on Google+" title="Share on Google+"  target="_blank"onclick="return smWindowpop(this.href,' . self::$popinWidth . ',' . self::$popinHight . ')">';
                break;
            case 'tumblr': $thumbID = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
                return '<a href="http://www.tumblr.com/share/photo?source=' . urlencode($thumbID[0]) . '&caption=' . urlencode(get_the_title()) . '&clickthru=' . urlencode(get_permalink()) . '" title="Share on Tumblr"  onclick="return smWindowpop(this.href,' . self::$popinWidth . ',' . self::$popinHight . ')"> ';
                break;
            case 'linkedin': return '<a href="http://www.linkedin.com/shareArticle?mini=true&url=' . apply_filters("the_permalink", get_permalink()) . '&title=' . urlencode(get_the_title()) . '&source=Stadefrance" onclick="return smWindowpop(this.href, ' . self::$popinWidth . ',' . self::$popinHight . ')">';
                break;
            default: return '';
                break;
        }
    }

    /*
     * Set image in hrader for pot share
     */

    public static function sm_get_post_image() {
        global $post;

        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_parent' => $post->ID
        );
        $images = get_posts($args);
        $src = array();
        foreach ($images as $image) {
            $src[] = wp_get_attachment_url($image->ID, array(120, 120));
        }
        if ($src) {
            $postImage = $src [0];
        }
        if (empty($postImage)) {
            $postImage = SM_URL . '/assets/css/images/logo_big.png';
        }
        echo '<meta property="og:image" content="' . $postImage . '"/>';
    }

    public static function sm_admin_menu() {
        add_menu_page('Share Me', 'Share Me', 'manage_options', 'share-me/admin-share-me.php', '', plugins_url('css/images/logo_small.png', __FILE__), 100);
    }

}
