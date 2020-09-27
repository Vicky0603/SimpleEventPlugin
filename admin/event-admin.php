<?php
defined("ABSPATH") or die("Bad Access");


// require Crud Class class
require_once(PLUGIN_PATH_ADMIN."events-crud.php");
/**
 * eventsAdmin class
 *
 * this class manage admin pages
 *
 * @since      1.0.0
 */

class eventsAdmin extends eventsCrud{
    
    
    /**
	 * Define constract to control the plugin
	 *
	 * load css files
	 * load js files
	 * add pages to admin menu
     *
	 * @since    1.0.0
	 */
    public function __construct(){
               
        // Load Css Files
        add_action( 'admin_enqueue_scripts', array($this,'load_custom_css'));
        // Load Js Files
        add_action( 'admin_enqueue_scripts', array($this,'load_custom_js'));
        // Add Pages To Admin menu
        add_action('admin_menu', array($this,'add_pages_to_menu'));
        add_action('admin_menu',  array($this,'registerOptionPage')); 
        
    }
    
    /**
	 * Define activate function
	 *
     * create plugin main table
	 * create plugin events tags table
     
	 * @since    1.0.0
     * @access   public
	 */
    public static function activate(){

        $obj = new eventsAdmin();
        // create Plugin Main Table
        $obj->create_plugin_main_table();
        // create Plugin events tags table
        $obj->create_plugin_events_tags_table();
        // create new wp-post
        $obj->create_new_post();
       
    }
    
    /**
	 * Define deactivate function
	 *
	 * flush rewrite rules
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function deactivate(){
        //flush rewrite rules
        flush_rewrite_rules();
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

    /**
	 * Define create plugin main table function
	 *
	 * this function used for create main table 
     *
	 * @since    1.0.0
     * @access   private
	 */
    private function create_plugin_main_table(){
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        // create Event Table Sql Query
        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName(
                `id` int(11) unsigned NOT NULL  AUTO_INCREMENT,
                `title` varchar(255),
                `description` text,
                `image` MEDIUMTEXT,
                `date` varchar(255),
                `start_time` varchar(255),
                `end_time` varchar(255),
                `event_category` varchar(255),
                PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    
    /**
	 * Define create plugin events tags function
	 *
	 * this function used for create events tags table 
     *
	 * @since    1.0.0
     * @access   private
	 */
    private function create_plugin_events_tags_table(){
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        // create Event Tags Table Sql Query
        $sql = "CREATE TABLE IF NOT EXISTS $this->tagesTable(
                `id` int(11) NOT NULL  AUTO_INCREMENT,
                `event_id` int(11) unsigned,
                `tag` varchar(255),
                PRIMARY KEY  (id),
                FOREIGN KEY (event_id) REFERENCES $this->tableName (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
         
    }
    
    /**
	 * Define drop main table function
	 *
	 * this function used for drop main table 
     *
	 * @since    1.0.0
     * @access   private
	 */
    private function drop_plugin_main_table() {
        global $wpdb;

        $sql = "DROP TABLE IF EXISTS $this->tableName";
        $wpdb->query($sql);
    }
    
    /**
	 * Define drop main table function
	 *
	 * this function used for drop main table 
     *
	 * @since    1.0.0
     * @access   private
	 */
    private function drop_plugin_events_tags_table() {
        global $wpdb;

        $sql = "DROP TABLE IF EXISTS $this->tagesTable";
        $wpdb->query($sql);
    }
    
    /**
	 * add pages to menu
	 *
	 * this function used for add pages to admin menu 
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function add_pages_to_menu(){
        add_menu_page('Events', 'Events', 'manage_options', 'new-event', array($this,'add_event_output'),'dashicons-megaphone' );
        add_submenu_page('new-event', 'Events', 'New Event', 'manage_options', 'new-event', array($this,'add_event_output'));
        add_submenu_page('new-event', 'Events', 'All Events', 'manage_options', 'view-events', array($this,'all_events_output') );
        add_submenu_page('wp-admin', 'Events', 'Edit Events', 'manage_options', 'edit-event', array($this,'edit_event_output') );
        
        add_action( 'admin_init', array($this,'register_plugin_settings'));
    }

    /**
	 * register option page
	 *
	 * this function used for add configration page to settings
     *
	 * @since    1.0.0
     * @access   public
	 */
    function registerOptionPage() {
      add_options_page('Events Plugin Settings', 'Events Plugin', 'manage_options', 'EventPluginSettings', array($this,'render_settings_page'));
    }
    
    /**
	 * load css files
	 *
	 * load plugin admin css files
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function load_custom_css() {
      wp_register_style( 'style_css', PLUGIN_URL_VENDOR.'/tag/style.css');
      wp_enqueue_style( 'style_css' );
      wp_register_style( 'tags_css', PLUGIN_URL_VENDOR.'/tag/tag.css');
      wp_enqueue_style( 'tags_css' );
    }
    
    /**
	 * load js files
	 *
	 * load plugin admin js files
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function load_custom_js() {
      wp_enqueue_script( 'jquery_js', PLUGIN_URL_VENDOR. '/jquery.js' );
      wp_enqueue_script( 'bootstrap_js', PLUGIN_URL_VENDOR. '/bootstrap-3.3.6-dist/js/bootstrap.min.js' );
      wp_enqueue_script( 'tag_js', PLUGIN_URL_VENDOR. '/tag/tag.js' );

    }
    
    /**
	 * register settings
	 *
	 * register plugin settings
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function register_plugin_settings(){
        register_setting("EventPluginSettings",'past_events');
        register_setting("EventPluginSettings",'events_per_page');
    }
    
    /**
	 * when user press on events plugin from settings menu
	 *
	 * get settings view
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function render_settings_page()
    {
        require_once(PLUGIN_PATH_INCLUDES_ADMIN.'setting.php');
    }
    
    /**
	 * add new post to wp_posts
	 *
	 * add new post to view data at front end
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function create_new_post()
    {
       // praparing array for my post   
        $my_post = array(
          'post_title'    => "events",
          'post_content'  => "[events_page]",
          'post_status'   => 'publish',
          'post_type' => "page",
          'post_name' => "my_events"    
        );

        // Insert the post into the database
        $event_id = wp_insert_post( $my_post );
        add_option("event_page_id",$event_id);
    }    

    
}


