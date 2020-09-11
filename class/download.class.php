<?php 

defined( 'ABSPATH' ) || exit; // Exit if accessed directly


class DownloadFile {
    protected $attachment_id, $file_url, $attachment_title;

    public function __construct($attachment_id)
    {
        $this->attachment_id = $attachment_id;
    }

    public function getFullPath() {
        $this->file_url = wp_get_attachment_url($this->attachment_id);
        $this->attachment_title = get_the_title($this->attachment_id);
        $this->file_url = get_attached_file( $this->attachment_id );

        if (file_exists($this->file_url)) {

            return $this->file_url;
        }
        return;

        
    }

    public function fileFromUrl() {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($this->getFullPath()).'"', true, 200);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($this->getFullPath()));
            readfile($this->getFullPath());
            exit;
    }

   
}