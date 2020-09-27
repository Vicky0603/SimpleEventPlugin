<?php
defined("ABSPATH") or die("Bad Access");


/*
 *require controller Class class
 */

require_once(PLUGIN_PATH_ADMIN."event_controller.php");

/*
 * class event_frontend
 * this class Used For Deal With Frontend
 *
 * @since      1.0.0
 */

class event_frontend extends event_controller
{

    /**
     *  custom_page_layout
     *
     *  get page content path
     *
     * @return    (starung) page content path
     * @since     1.0.0
     * @access   public
     *
     */
    public static function custom_page_layout()
    {

        // get wp post
        global $post;
        // get post name
        $page_slug = $post->post_name;
        // check post name
        if ($page_slug == 'my_events') {
            $page_template = PLUGIN_PATH_INCLUDES_PUBLIC . 'page_template.php';
        }
        return $page_template;
    }

    /**
     *  get_all_events
     *
     *  get all events
     *
     * @return    (unknow) events or null
     * @since     1.0.0
     * @access   public
     *
     */
    public function get_all_events()
    {
        // get today date for check past events or not
        $todayData = date("m/d/Y");
        // get option from database
        $past_events_option = get_option('past_events');
        // preparing sql query
        $sql = "SELECT * FROM $this->tableName ";
        if ($past_events_option != 'yes') {
            $sql .= "WHERE `date` >= " . $todayData;
        }
        global $wpdb;
        $allevents = $wpdb->get_results($sql);
        return $allevents;
    }
}