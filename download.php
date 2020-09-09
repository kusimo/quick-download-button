<?php
$actual_link = "http://$_SERVER[HTTP_HOST]";

if (strstr($_SERVER['HTTP_REFERER'], $actual_link) !== false) {
    $current_url = $_SERVER['SERVER_NAME'];
    $track_id = $_GET['aid'];

    /**
     * Get the link of the attachment for download
     */



    if (!empty($track_id)) {

        //require('wp-blog-header.php');
        $scriptPath = dirname(__FILE__);
        $path = realpath($scriptPath . '/./');
        $filepath = explode("wp-content",$path);
        // print_r($filepath);
        define('WP_USE_THEMES', false);
        require(''.$filepath[0].'/wp-blog-header.php');

        global $post;

        $file_url = wp_get_attachment_url($track_id);
        $attachment_title = get_the_title($track_id);
        if('' ==  $attachment_title) {
            $attachment_title = $track_id;
        } else {
            $attachment_title = sanitize_title($attachment_title);
            $string_replace = str_replace('-',' ', $attachment_title);
            $attachment_title = ucwords($string_replace);
        }
        $extension_path = explode('.', $file_url); $file_extension = end($extension_path); 

        $fullsize_path = get_attached_file( $track_id ); // Full path


      $file = $fullsize_path;

        if (file_exists($fullsize_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"', true, 200);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }

       //*/

    }

   


} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
    echo '<h1>Forbidden</h1><p>You don\'t have permission to access this file. </p><hr>';
}
