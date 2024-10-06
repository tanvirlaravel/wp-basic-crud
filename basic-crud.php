<?php 
/*
* Plugin Name: Basic CRUD
* Plugin URI:  https://www.basic-crud.com
* Description: Basic CRUD Operations
* Version:     1.0
* Author:      Tanvir
* Author URI:  https://github.com/tanvirlaravel
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: basic-crud
*/

register_activation_hook(__FILE__, 'crudOperationTable');

function crudOperationTable(){
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'userstable';

    $sql = "CREATE TABLE `$table_name` (
        `user_id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(220) DEFAULT NULL,
        `email` varchar(220) DEFAULT NULL,
        PRIMARY KEY(user_id)
        ) $charset_collate;
        ";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}

add_action("admin_menu", "addAdminPageContent");

function addAdminPageContent(){
    echo 'hello form adeimn';
}