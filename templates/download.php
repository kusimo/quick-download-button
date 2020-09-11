<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

$actual_link = "$_SERVER[HTTP_HOST]"? : "";

if(isset($_SERVER['HTTP_REFERER']))  {
    if (strstr($_SERVER['HTTP_REFERER'], $actual_link) !== false) {
        $current_url = $_SERVER['SERVER_NAME'];
        if(!empty($_GET)) $attachment_id = $_GET['aid'];
    
        if (!empty($attachment_id)) {
    
    
            require_once(dirname(__DIR__).'/class/download.class.php');
    
            $download = new DownloadFile($attachment_id);
        
            $download->fileFromUrl();
        }
       
    } 
} 

