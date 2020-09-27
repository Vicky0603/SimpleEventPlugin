<?php
defined("ABSPATH") or die("Bad Access");
/**
 * Upload class
 *
 * this class contain the methods that used for upload files
 *
 *  @since      1.0.0
 */

class uploadMyFiles{
    
    /**
     * uploadFiles
     *
     * Upload Image
     *
     * @param     $inputFileName (string)  the name of input file
     
     * @return    (non) Uploaded Image Path , FALSE Otherwise
     *
     */
    public function uploadFiles($inputFileName)
    {
        //require wordpress file
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $uploadedfile = $_FILES[$inputFileName];
        $upload_overrides = array( 'test_form' => false );
        //upload image
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
        if ( $movefile ) {
            //return uploaded image url
            return $movefile['url'];
        } 
        return FALSE;
    }
    
}



?>
