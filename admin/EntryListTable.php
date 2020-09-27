<?php
defined("ABSPATH") or die("Bad Access");


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class EntryListTable extends WP_List_Table {
    
    private $tableName;

    function __construct($tableName) {
      global $status, $page;
      parent::__construct(array(
        'singular' => 'Entry Data',
        'plural' => 'Entry Datas',
      ));
        $this->tableName = $tableName;
    }
    /*Set Data To My Table*/
    function column_default($item, $column_name) {
        switch($column_name){
          case 'action': echo '<a href="'.admin_url('admin.php?page=edit-event&entryid='.$item['id']).'">Edit</a>';
                return;
        }
        return $item[$column_name];
    }

    function column_feedback_name($item) {
      $actions = array( 'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id']) );
      return sprintf('%s %s', $item['id'], $this->row_actions($actions) );
    }
    /*Print Check Box*/
    function column_cb($item) {
      return sprintf( '<input type="checkbox" name="id[]" value="%s" />', $item['id'] );
    }
    
    /*Set Table Column*/
    function get_columns() {
      $columns = array(
        'cb' => '<input type="checkbox" />',
			  'id'=> 'Id',
			  'title'=> 'Title',
        'description'=> 'Description',
        'date'=> 'Date',
        'action' => 'Action'
      );
      return $columns;
    }
    /*
    Allow User To Sort Data With Column
    */
    function get_sortable_columns() {
      $sortable_columns = array(
        'id' => array('id', true),
        'date'=>array('date',true)  
      );
      return $sortable_columns;
    }
    /*set Actions*/
    function get_bulk_actions() {
      $actions = array( 'delete' => 'Delete' );
      return $actions;
    }
    
    /*Excute Action*/
    function process_bulk_action() {
      global $wpdb;
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $this->tableName  WHERE id IN($ids)");
                echo "<div class='alert alert-success'>Items Deleted</div>";
            }
        }
    }
    

    function prepare_items() {
      global $wpdb,$current_user;
     // set Items Per Page For Pagination    
      $per_page = 10;
      $columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();
      $this->_column_headers = array($columns, $hidden, $sortable);
      //process bulk action (delete)     
      $this->process_bulk_action();
      // get total items    
      $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $this->tableName");

      $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
      // get order by -> column     
      $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
      $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

      //get data (all data or search)
      if(isset($_REQUEST['s']) && $_REQUEST['s']!='') {
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->tableName WHERE `title` LIKE '%".$_REQUEST['s']."%' OR `description` LIKE '%".$_REQUEST['s']."%' ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged * $per_page), ARRAY_A);
      } else {
          $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->tableName ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged * $per_page), ARRAY_A);
      }
        // Set Pagination Args
      $this->set_pagination_args(array(
        'total_items' => $total_items,
        'per_page' => $per_page,
        'total_pages' => ceil($total_items / $per_page)
      ));
    }
}