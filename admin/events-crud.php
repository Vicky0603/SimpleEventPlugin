<?php
defined("ABSPATH") or die("Bad Access");

/*
 * get Plugin All Pathes
 */
require_once(PLUGIN_PATH."pathes.php");

/*
 *require controller Class class
 */

require_once(PLUGIN_PATH_ADMIN."event_controller.php");

/**
 * eventsCrud class
 *
 * this class manage Crud Operations
 *
 * @since      1.0.0
 */
class eventsCrud extends event_controller{
    
    /**
	 * when user press on add new event from admin Menu 
	 *
	 * load add new event view
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function add_event_output() {
        
     /*
     *  when user press on add event
     *  start validate data 
     *  insert data to database
     */    
      if(isset($_POST['add']))
      {
          // preparing validation array
          $validationArray = array(
                                        "title"=>array(
                                            "value"     => $_POST['title'],
                                            "errorName" => "Title",
                                            "rules"     => "required|min-length[3]|max-length[50]"
                                        ),
                                        "description"=>array(
                                            "value"     => $_POST['description'],
                                            "errorName" => "Description",
                                            "rules"     => "required|min-length[5]"
                                        ),
                                        "date"=>array(
                                            "value"     => $_POST['date'],
                                            "errorName" => "date",
                                            "rules"     => "required"
                                        ),
                                        "start_time"=>array(
                                            "value"     => $_POST['start_time'],
                                            "errorName" => "Start Time",
                                            "rules"     => "required"
                                        ),
                                        "end_time"=>array(
                                            "value"     => $_POST['end_time'],
                                            "errorName" => "End Time",
                                            "rules"     => "required|min-length[3]|max-length[50]"
                                        ),
                                        "event_category"=>array(
                                            "value"     => $_POST['event_category'],
                                            "errorName" => "Event Category",
                                            "rules"     => "required"
                                        ),
                                        "tags"=>array(
                                            "value"     => $_POST['tags'],
                                            "errorName" => "tags",
                                            "rules"     => "required"
                                        )
                                    );
          
          // run validation
          $validatedData = $this->loadValidation($validationArray)->validator();
          // check if data passed
          if(is_array($validatedData) && array_key_exists("success",$validatedData)){
              // cehck if user choose image
              if(isset($_FILES['image']) && $_FILES['image']['size'] > 0)
              {
                  // upload image
                  $imageUrl = $this->loadUploadFiles()->uploadFiles('image');
                  //check if image uploaded successfully
                if(FALSE !== $imageUrl) 
                {
                    // add image to success validated data
                    $validatedData['success']['image'] =  $imageUrl;
                    $addData = $this->add_new_entry($validatedData['success'],$this->tableName,$this->tagesTable);
                    //check if data added succssfully
                    if(FALSE !== $addData)
                    {
                        $messageSuccess = 'Your Data Added Successfully';
                    }else{
                        $messageError = "Error Adding Data Please Try Again";
                    }
                }else{
                   $messageError = "Invalid Image";
                }
              }else{
                  $messageError = "Please Choose Event Image";
              }
             
          }else{
              $messageError = $validatedData;
          }
      }
      // get view    
      require_once(PLUGIN_PATH_INCLUDES_ADMIN.'new_entry.php');

    }
    
    /**
     * add_new_entry
     *
     * Add Data To Database
     *
     * @param     $data (array)  array of data
     * @param     $events_table_name (string)  events table name
     * @param     $tags_table_name (string)  events tags table name
     * @return    (Boolean) TRUE if Data Inserted , FALSE Otherwise
     *
     */
    public function add_new_entry($data,$events_table_name,$tags_table_name)
    {
        if(is_array($data))
        {
            
            global $wpdb;
            // preparing data
            $sql = $wpdb->prepare(
                        "INSERT INTO `$events_table_name`      
                           (`title`,`description`,`image`,`date`,`start_time`,`end_time`,`event_category`) 
                            values (%s, %s, %s, %s, %s, %s, %s) ",
                            $data['title'], $data['description'], $data['image'], $data['date'],
                            $data['start_time'], $data['end_time'], $data['event_category']);
           // insert data
            $result = $wpdb->query($sql);
            //check if data inserted successfully
            if(FALSE !== $result)
            {
                // get last inserted id
                $event_id = $wpdb->insert_id;
                /*
                 * start add event tags
                 * tags returned as string so we must convert string to array
                 */
                $tagsArray = explode(",",$data['tags']);
                if(is_array($tagsArray))
                {
                  for($i=0; $i < count($tagsArray); $i++)
                  {
                      $sqlTags = $wpdb->prepare("INSERT INTO `$tags_table_name` (`event_id`,`tag`) values (%s, %s) ",
                                                 $event_id, $tagsArray[$i]);
                      $wpdb->query($sqlTags);
                  }
                }
                return TRUE;
            }
        }
        return FALSE;
    }
    /**
	 * when user press on edit event
	 *
	 * get edit event view
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function edit_event_output() {
     
    // get event id from url    
    $id = (int)$_GET['entryid'];
    // check if id == 0 die 
    if($id == 0)
    {
        die("Invalid Id");
    }
    // global wpdb    
    global $wpdb;
    // get data from database    
    $results = $wpdb->get_results( "SELECT * FROM $this->tableName WHERE `id` = $id");
    // check if there is data with this id    
    if(!is_array($results))
    {
       die("Bad Access"); 
    }
     // shift array    
     $result = array_shift($results);
        
    // get data from database    
    $tags = $wpdb->get_results( "SELECT * FROM $this->tagesTable WHERE `event_id` = $id"); 
    /*
    * tags plugin works only with strings so we will prepare data to be string
    */
    $tagsArray = array(); 
        
    if(is_array($tags))
    {
        foreach($tags as $key=>$tag)
        {
           $tagsArray[] = $tag->tag; 
        }
    }
    // implode array to return string
    $tagsString = implode(",",$tagsArray);
    /*
     *  when user press on edit event
     *  start validate data 
     *  update data 
     */    
      if(isset($_POST['edit']))
      {
          // preparing validation array
          $validationArray = array(
                                        "title"=>array(
                                            "value"     => $_POST['title'],
                                            "errorName" => "Title",
                                            "rules"     => "required|min-length[3]|max-length[50]"
                                        ),
                                        "description"=>array(
                                            "value"     => $_POST['description'],
                                            "errorName" => "Description",
                                            "rules"     => "required|min-length[5]"
                                        ),
                                        "date"=>array(
                                            "value"     => $_POST['date'],
                                            "errorName" => "date",
                                            "rules"     => "required"
                                        ),
                                        "start_time"=>array(
                                            "value"     => $_POST['start_time'],
                                            "errorName" => "Start Time",
                                            "rules"     => "required"
                                        ),
                                        "end_time"=>array(
                                            "value"     => $_POST['end_time'],
                                            "errorName" => "End Time",
                                            "rules"     => "required|min-length[3]|max-length[50]"
                                        ),
                                        "event_category"=>array(
                                            "value"     => $_POST['event_category'],
                                            "errorName" => "Event Category",
                                            "rules"     => "required"
                                        ),
                                        "tags"=>array(
                                            "value"     => $_POST['tags'],
                                            "errorName" => "tags",
                                            "rules"     => "required"
                                        )
                                    );
          
          // run validation
          $validatedData = $this->loadValidation($validationArray)->validator();
          // check if data passed
          if(is_array($validatedData) && array_key_exists("success",$validatedData)){
              // cehck if user choose image
              if(isset($_FILES['image']) && $_FILES['image']['size'] > 0)
              {
                  // upload image
                  $imageUrl = $this->loadUploadFiles()->uploadFiles('image');
                  //check if image uploaded successfully
                  if(FALSE !== $imageUrl) 
                  {
                      // add image to success validated data
                      $validatedData['success']['image'] =  $imageUrl;
                  }
              }else{
                  $validatedData['success']['image'] =  $result->image;
              }
              
              // update data
              $updateData = $this->update_entry($validatedData['success'],$id,$this->tableName,$this->tagesTable);
              //check if data added succssfully
              if(FALSE !== $updateData)
              {
                  $messageSuccess = 'Your Data Updated Successfully';
              }else{
                  $messageError = "Error Updating Data Please Try Again";
              }
          }else{
              $messageError = $validatedData;
          }
      } 
        // load my view
       require_once(PLUGIN_PATH_INCLUDES_ADMIN.'edit_entry.php');
    }
    /**
     * update_entry
     *
     * Add Data To Database
     *
     * @param     $data (array)  array of data
     * @param     $id (int)  id that will be used for update
     * @param     $events_table_name (string)  events table name
     * @param     $tags_table_name (string)  events tags table name
     * @return    (Boolean) TRUE if Data Inserted , FALSE Otherwise
     *
     */
    public function update_entry($data,$id,$events_table_name,$tags_table_name)
    {
        if(is_array($data))
        {
            
            global $wpdb;
            // preparing data
            $sql = $wpdb->prepare(
                        "UPDATE `$events_table_name`      
                           SET `title` = %s,`description` = %s,`image` = %s,`date` = %s,
                                `start_time` = %s,`end_time` = %s, `event_category` = %s
                                WHERE `id` = $id
                            ",
                            $data['title'], $data['description'], $data['image'], $data['date'],
                            $data['start_time'], $data['end_time'], $data['event_category']);
           // Update data
            $result = $wpdb->query($sql);
            //check if data Updated successfully
            if(FALSE !== $result)
            {
                /*
                * delete old tags
                */
                $deleteSql = $wpdb->query("DELETE FROM $tags_table_name WHERE `event_id` = $id");
                /*
                 * start add event tags
                 * tags returned as string so we must convert string to array
                 */
                $tagsArray = explode(",",$data['tags']);
                if(is_array($tagsArray))
                {
                  for($i=0; $i < count($tagsArray); $i++)
                  {
                      $sqlTags = $wpdb->prepare("INSERT INTO `$tags_table_name` (`event_id`,`tag`) values (%s, %s) ",
                                                 $id, $tagsArray[$i]);
                      $wpdb->query($sqlTags);
                  }
                }
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
	 * when user press on all events
	 *
	 * show all events using wordpress list table 
     *
	 * @since    1.0.0
     * @access   public
	 */
    public function all_events_output() {
      // load EntryListTable
      require_once(PLUGIN_PATH_ADMIN."EntryListTable.php");    
      $table = new EntryListTable($this->tableName);
      // prepare items
      $table->prepare_items();
      echo '<h4>View Events</h4>';
      echo '<form action="" method="GET">';
      echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'"/>';   
      $table->search_box( 'search', 'search_id' );
      // display table with data
      $table->display();
      echo '</form>';    
          
    }
    
    
}