<?php
defined("ABSPATH") or die("Bad Access");

/*
 * get Plugin All Pathes
 */
require_once(PLUGIN_PATH."pathes.php");

/*
 * class event_controller
 * this classUsed For Manage All My Classes
 *
 * @since      1.0.0
 */
class event_controller{
    
        
    /**
	 * define table name
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tableName  define plugin main table name .
	 */
    protected $tableName = "ev_events";
    /**
	 * define tags table name
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tagesTable  define plugin tags table .
	 */
    protected $tagesTable = "ev_events_tags";
    
    /**
     *  loadValidation
     *
     *  Load Validation Class
     *
     *  @param     $validationArray (string)  array of data
     *  @return    (object) object from validation class
     *  @since     1.0.0
     *  @access    public
     *
     */
    public function loadValidation($validationArray)
    {
        // require validatin class File
        require_once(PLUGIN_PATH_ADMIN."validation.php");
        // define object from validation class
        $object = new validation($validationArray);
        return $object;
    }
    
    /**
     *  loadEventCrud
     *
     *  Load Crud Class
     *
     *  @param     $validationArray (array)  array of data
     *  @param     $tags (string)  tags as string
     *  @return    (object) object from eventsCrud class
     *  @since     1.0.0
     *  @access   public
     *
     */
    public function loadEventCrud()
    {
        // require validatin class File
        require_once(PLUGIN_PATH_ADMIN."events-crud.php");
        $object = new eventsCrud();
        return $object;
    }
    
    /**
     *  loadUploadFiles
     *
     *  Load Upload Files Class
     *
     *  @since     1.0.0
     *  @access   public
     *
     */
    public function loadUploadFiles()
    {
        // require validatin class file
        require_once(PLUGIN_PATH_ADMIN."uploadMyFiles.php");
        $object = new uploadMyFiles();
        return $object;
    }
    
    
}