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
    /*
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    Parameters:
    $page_title: The text displayed in the page title.
    $menu_title: The text displayed in the menu.
    $capability: The capability required to access the menu page.
    $menu_slug: The unique slug for the menu page.
    $function: The callback function that will be executed when the menu page is accessed.
    $icon_url: The URL of the icon to be displayed in the menu.
    $position: The position of the menu item relative to other menu items. Higher numbers appear later in the menu.
    */
    add_menu_page('CRUD', 'CRUD', 'manage_options', 'basic-crud', 'crudAdminPage', 'dashicons-wordpress');
}

function crudAdminPage(){
    global $wpdb;
    global $wp;
    $url = home_url( $wp->request ) . "/wp-admin/admin.php?page=basic-crud";
    $table_name = $wpdb->prefix . 'userstable';

    // Insert Data to database
    if(isset($_POST['newsubmit'])) {
        $name = $_POST['newname'];
        $email = $_POST['newemail'];

        $wpdb->query(
            "INSERT INTO $table_name(name, email)
            VALUES('$name', '$email')
            ");
        echo "<script>location.replace('admin.php?page=basic-crud')</script>";
    }

    if (isset($_POST['uptsubmit'])) {
        $id = $_POST['uptid'];
        $name = $_POST['uptname'];
        $email = $_POST['uptemail'];
        $wpdb->query("UPDATE $table_name SET name='$name',email='$email' WHERE user_id='$id'");
        echo "<script>location.replace('{$url}');</script>";
        }

    

    // Update data in Database
    if(isset($_GET['upt'])){       
        $update_id = $_GET['upt'];
        $result = $wpdb->get_results("SELECT * FROM  $table_name WHERE user_id = '$update_id'");

        // var_dump($result);
        foreach($result as $print){
            $name = $print->name;
            $email = $print->email;
        }

        echo "
         <table class='wp-list-table widefat striped'>
           <thead>
             <tr>
               <th width='25%'>User ID</th>
               <th width='25%'>Name</th>
               <th width='25%'>Email Address</th>
               <th width='25%'>Actions</th>
             </tr>
           </thead>
           <tbody>
             <form action='' method='post'>
               <tr>
                 <td width='25%'>$print->user_id <input type='hidden' id='uptid' name='uptid' value='$print->user_id'></td>
                 <td width='25%'><input type='text' id='uptname' name='uptname' value='$print->name'></td>
                 <td width='25%'><input type='text' id='uptemail' name='uptemail' value='$print->email'></td>
                 <td width='25%'><button id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=crud.php'><button type='button'>CANCEL</button></a></td>
               </tr>
             </form>
           </tbody>
         </table>";
    }

    // Delete 
    if(isset($_GET['del'])){       
        $del_id = $_GET['del'];
       echo $del_id;

    }

      
      
    ?>
<div class="wrap">
<h2>CRUD Operations</h2>
<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th width="25%">User ID</th>
            <th width="25%">Name</th>
            <th width="25%">Email Address</th>
            <th width="25%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <form action="" method="post">
            <tr>
                <td><input type="text" value="AUTO_GENERATED" disabled></td>
                <td><input type="text" id="newname" name="newname"></td>
                <td><input type="text" id="newemail" name="newemail"></td>
                <td><button id="newsubmit" name="newsubmit" type="submit">INSERT</button></td>
            </tr>
        </form>

        <?php 
        $result = $wpdb->get_results("SELECT * FROM $table_name");

       

        foreach($result as $data){
            ?>
            <tr>
                <td width="25%"><?php echo $data->user_id ; ?></td>
                <td width="25%"><?php echo $data->name ; ?></td>
                <td width="25%"><?php echo $data->email ; ?></td>
                <td width="25%">
                    <a  href="<?php echo $url; ?>&upt=<?php echo $data->user_id; ?>"><button type="button">Update</button></a> | 
                    <a  href="<?php echo $url; ?>&del=<?php echo $data->user_id; ?>"><button type="button">Delete</button></a>              
            </tr>
            <?php
        }

?>
</tbody>
</table>
</div>

<?php
}
