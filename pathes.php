<?php

/*
    define Plugin Pathes 
*/

// Plugin Url =>
define('PLUGIN_URL', plugin_dir_url( __FILE__ ));
// Plugin Path
define('PLUGIN_PATH', plugin_dir_path( __FILE__ ));
// Css Folder Url Admin
define('PLUGIN_URL_ADMIN_CSS', PLUGIN_URL."admin/css".DIRECTORY_SEPARATOR);
// Js Folder Url Admin
define('PLUGIN_URL_ADMIN_JS', PLUGIN_URL."admin/js".DIRECTORY_SEPARATOR);
// Css Folder Url public
define('PLUGIN_URL_PUBLIC_CSS', PLUGIN_URL."public/css".DIRECTORY_SEPARATOR);
// Js Folder Url public
define('PLUGIN_URL_PUBLIC_JS', PLUGIN_URL."public/js".DIRECTORY_SEPARATOR);
// Views Folder Path 
define('PLUGIN_PATH_INCLUDES', PLUGIN_PATH."includes".DIRECTORY_SEPARATOR);
// Views Path ADMIN
define('PLUGIN_PATH_INCLUDES_ADMIN', PLUGIN_PATH."includes".DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR);
// Frontend Views Path 
define('PLUGIN_PATH_INCLUDES_PUBLIC', PLUGIN_PATH."includes".DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR);
// Vendor Folder Url
define('PLUGIN_URL_VENDOR', PLUGIN_URL."vendor".DIRECTORY_SEPARATOR);
// Admin Folder Path 
define('PLUGIN_PATH_ADMIN', PLUGIN_PATH."admin".DIRECTORY_SEPARATOR);
// Public Folder Path 
define('PLUGIN_PATH_PUBLIC', PLUGIN_PATH."public".DIRECTORY_SEPARATOR);
