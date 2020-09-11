<?php 
defined('ABSPATH') || exit;

class QuickDownloadShortCode {

   public $a, $pid, $attachment_id, $url;
   
    
    public function __construct()
    {
        add_shortcode('quick_download_button', array($this, 'quick_download_button_shortcode'));
    }

    public function quick_download_button_shortcode($attr) {

        $this->a = shortcode_atts(array(
            'title' => 'Download',
            'filesize' => '',
            'url' => '',
            'extension' => '',
            'extension_text' => '0',
            'url_external' => '',
        ), $attr);

        global $post;
        $this->pid = $post->ID;
    
    
        $this->attachment_id = attachment_url_to_postid($this->a['url']);  //get attachment id from URL
    
        $quick_download_button_url = qdb_default_url() . '?aid=' . $this->attachment_id; //pass attachment id to plugin download file
    
        $this->url = !empty($this->a['url']) ? $quick_download_button_url : $this->a['url_external']; //if url value is empty set url to external url (url_external)

        return $this->generate_form();

    }

    function generate_form() {

    ob_start();
    ?>
    
        <div class="button--download" style="margin: 6rem auto;width: 200px;">
            <form method="<?php echo !empty( esc_url($this->a['url']))  ? 'post' : 'get'; ?>" action="<?php echo esc_url($this->url); ?>">
                <?php wp_nonce_field(); ?>
                <button class="g-btn f-l bsbtn d-block position-relative shadow rounded-lg border-0" type="submit" style="z-index:2;height:50px; width:200px;" title="<?php esc_attr_e('Download', 'quick-download-button'); ?>" data-pid="<?php echo intval($this->pid); ?>" <?php echo !empty( esc_url( $this->a['url_external'] ) ) ? 'formtarget="_blank"' : ''; ?>><?php echo esc_attr($this->a['title']); ?></button>
            </form>
    
            <?php
            $extension_path = '';
            $file_icon = '';
            $extension_array = ['pdf', 'mp3', 'mov', 'zip', 'txt', 'doc', 'xml', 'mp4', 'ppt'];
    
            if ('1' == $this->a['extension']) :
                $extension_path = explode('.', $this->a['url']);
                $file_extension = end($extension_path);
                //check if file icon is in array of extension
                if (in_array(strtolower($file_extension), $extension_array)) {
                    $file_icon = 'fi fi-' . strtolower($file_extension);
                } else {
                    //check image array
                    $image_array = ['jpg', 'jpeg', 'tiff', 'png', 'bmp', 'gif'];
                    if (in_array(strtolower($file_extension), $image_array)) {
                        $file_icon = 'fi fi-image';
                    } else {
                        $file_icon = 'fi fi-file';
                    }
                }
    
            elseif ('' != $this->a['extension'] && '1' != $this->a['extension']) :
                $file_extension = $this->a['extension'];
                $file_icon = '';
            endif;
            ?>
    
            <?php if ('' != $this->a['extension']) : ?>
                <p class="up"><i class="<?php echo sanitize_html_class($file_icon); ?>"></i>
                    <?php if ('1' == $this->a['extension_text']) echo '<span>' . esc_attr( $file_extension) . '</span>'; ?></p>
            <?php endif;  ?>
    
            <?php if ('1' === $this->a['filesize']) :
                $file_url = filesize($this->qdb_convert_url_to_path($this->a['url']));
                $file_size = $this->qdb_formatSizeUnits($file_url);
                if ('0 bytes' != $file_size) {
                    $file_blob_size = $this->qdb_formatSizeUnits($file_url);
                    $blob_number = explode(' ', $file_blob_size);
                    $blob_number = $blob_number[0];
    
                    $blob_measure = explode(' ', $file_blob_size);
                    $blob_measure = $blob_measure[1];
                }
                /* translators: %1$s is a filesize %2$s is the measurement */
                printf(__('<p class="down"><i class="fi-folder-o"></i> %1$s %2$s</p>'), esc_attr($blob_number), esc_attr($blob_measure));
    
            elseif ('' !== $this->a['filesize']) :
                /* translators: %1$s is a filesize */
                printf(__('<p class="down"><i class="fi-folder-o"></i> %1$s </p>'), esc_attr($this->a['filesize']));
    
                //else :
            ?>
    
            <?php endif; ?>
    
        </div>
    
    <?php
        return ob_get_clean();
    }

    
//convert URL to path
    function qdb_convert_url_to_path($url)
    {
        return str_replace(
            wp_get_upload_dir()['baseurl'],
            wp_get_upload_dir()['basedir'],
            $url
        );
    }


//get file size
    function qdb_formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
   

}