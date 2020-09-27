<?php
/* 
 * Removing Plugin data using uninstall.php
 * the below function clears the database table on uninstall
 * only loads this file when uninstalling a plugin.
 */

/* 
 * exit uninstall if not called by WP
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

/* 
 * Making WPDB as global
 * to access database information.
 */
global $wpdb;

/* 
 * @var $tables_array 
 * name of tables to be dropped
 */

$tables_array = array('ev_events_tags','ev_events');

if(isset($tables_array) && is_array($tables_array))
{
    for($i=0; $i<count($tables_array); $i++)
    {
        // drop the table from the database.
        $wpdb->query( "DROP TABLE IF EXISTS $tables_array[$i]" );
    }
    // delete data from post and option tables 
    if(!empty(get_option("event_page_id")))
    {
        // get page id (option)
        $page_id = get_option("event_page_id");
        // delete post
        wp_delete_post($page_id,true);
        // delete option
        delete_option("event_page_id");
    }
}

