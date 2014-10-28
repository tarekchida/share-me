<?php

class shareMe {

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

    public static function sm_deactivation() {

        global $wpdb;
        $table = SM_TABLE_PREFIX . "config";
        $structure = "drop table if exists $table";
        $wpdb->query($structure);

        $table = SM_TABLE_PREFIX . "social_list";
        $structure = "drop table if exists $table";
        $wpdb->query($structure);
    }

}
