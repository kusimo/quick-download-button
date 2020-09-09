<?php

/**
 * Plugin Name: Custom Download
 * Plugin URI: https://github.com/kusimo/custom-download
 * Description: Custom block plugin for download button with color, file extension options and shortcode.
 * Version: 1.0.0
 * Author: Abidemi Kusimo
 *
 * @package custom-download
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load translations (if any) for the plugin from the /languages/ folder.
 * 
 * @link https://developer.wordpress.org/reference/functions/load_plugin_textdomain/
 */

add_action( 'init', 'custom_download_load_textdomain' );

function custom_download_load_textdomain() {
	load_plugin_textdomain( 'custom-download', false, basename( __DIR__ ) . '/languages' );
}

/**
 * Registers all block assets so that they can be enqueued through the Block Editor in
 * the corresponding context.
 *
 * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-registration/
 */
add_action( 'init', 'custom_download_register_blocks' );

function custom_download_register_blocks() {

    //If Block Editor is not active, bail.
    if(!function_exists('register_block_type')) {
        return;
    }

    /**
     * Use asset file to automatically set the dependency list for enqueuing the script
     */
    $custom_download_asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');

    //Register the block editor script

    wp_register_script(
		'custom-download-editor-script',					// label
		plugins_url( 'build/index.js', __FILE__ ),			// script file
		$custom_download_asset_file['dependencies'],		// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )		// set version as file last modified time
    );

    //Localise script
    wp_localize_script(
        'custom-download-editor-script',
        'custom_data',
        array(
            'download_file_url' => plugins_url( 'download.php', __FILE__ )
        )
    );
    
    // Register the block editor stylesheet.
	wp_register_style(
		'custom-download-editor-styles',											// label
		plugins_url( 'css/editor.css', __FILE__ ),					// CSS file
		array( 'wp-edit-blocks' ),										// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'css/editor.css' )	// set version as file last modified time
	);

	// Register the front-end stylesheet.
	wp_register_style(
		'custom-download-front-end-styles',										// label
		plugins_url( 'css/style.css', __FILE__ ),						// CSS file
		array( ),														// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' )	// set version as file last modified time
    );
    
    //register blocks script and styles
    register_block_type( 'custom-download/download-button', array(
        'editor_script' => 'custom-download-editor-script',					// Calls registered script above
		'editor_style' => 'custom-download-editor-styles',					// Calls registered stylesheet above
		'style' => 'custom-download-front-end-styles',	
    ) );

    if ( function_exists( 'wp_set_script_translations' ) ) {
        /**
         * Adds internationalization support. 
         * 
         * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/internationalization/
         * @link https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
         */
        wp_set_script_translations( 'custom-download-editor-script', 'custom-download', plugin_dir_path( __FILE__ ) . '/languages' );
        }


}

//Shortcode option


function custom_download_button_shortcode($atts, $content = null) {
    $a = shortcode_atts(array(
        'title' => _('Download', 'custom-download'),
        'filesize' => '',
        'duration' => '',
        'url' => '',
        'extension' => '',
        'extension_text' => '0',
        'url_external' => '',
    ), $atts);

    global $post;
    $pid = $post->ID;


    $attachment_id = attachment_url_to_postid($a['url']);  //get attachment id from URL

    $custom_download_url = plugin_dir_url( __DIR__ ).'custom-download/download.php?aid='.$attachment_id; //pass attachment id to plugin download file

    $url = !empty($a['url'])? $custom_download_url : $a['url_external'] ; //if url value is empty set url to external url (url_external)

    ob_start();
    ?>

    <div class="button--download" style="margin: 6rem auto;width: 200px;">
      <form method="<?php echo !empty($a['url'])? 'post' : 'get' ; ?>" action="<?php echo $url ; ?>">
        <button 
            class="g-btn f-l bsbtn d-block position-relative shadow rounded-lg border-0" 
            type="submit" style="z-index:2;height:50px; width:200px;" 
            title="<?php esc_attr_e( 'Download', 'custom-download' ); ?>" 
            data-pid="<?php echo $pid; ?>"
            <?php echo !empty($a['url_external'])? 'formtarget="_blank"' : '' ; ?>><?php esc_attr( printf(__('%s', 'custom-download'),$a['title']) );?></button>
      </form>

        <?php  
         $extension_path = '';
         $file_icon = '';
         $extension_array = ['pdf','mp3','mov','zip','txt','doc','xml','mp4','ppt'];

        if('1' == $a['extension']  ) : 
             $extension_path = explode('.', $a['url']); $file_extension = end($extension_path); 
             //check if file icon is in array of extension
             if(in_array(strtolower($file_extension),$extension_array)) {
                $file_icon = 'fi fi-'.strtolower($file_extension);
             } else {
                 //check image array
                 $image_array = ['jpg','jpeg','tiff','png','bmp','gif'];
                 if(in_array(strtolower($file_extension),$image_array)) {
                    $file_icon = 'fi fi-image';
                 } else {
                    $file_icon = 'fi fi-file';
                 }
                 
             }
            
        elseif('' != $a['extension'] && '1' != $a['extension']) :
            $file_extension = $a['extension'];
            $file_icon = '';
        endif ;  
        ?>

        <?php if('' != $a['extension']) : ?>
        <p class="up"><i class="<?php echo $file_icon; ?>"></i> 
        <?php if('1' == $a['extension_text']) echo '<span>'. $file_extension.'</span>'; ?></p>
        <?php endif ;  ?>

        <?php if( '1' === $a['filesize'] ) : 
            $file_url = filesize(convert_url_to_path($a['url']) );
            $file_size = formatSizeUnits($file_url);
            if('0 bytes' != $file_size) {
                    $file_blob_size = formatSizeUnits($file_url);
                    $blob_number = explode(' ', $file_blob_size);
                    $blob_number = $blob_number[0];

                    $blob_measure = explode(' ', $file_blob_size);
                    $blob_measure = $blob_measure[1];

                }
                /* translators: %1$s is a filesize %2$s is the measurement */
                printf( __( '<p class="down"><i class="fi-folder-o"></i> %1$s %2$s</p>'), esc_attr($blob_number), esc_attr($blob_measure));

            elseif('' !== $a['filesize']) :
                 /* translators: %1$s is a filesize */
                printf( __( '<p class="down"><i class="fi-folder-o"></i> %1$s </p>'), esc_attr($a['filesize']));

        //else :
        ?>
      
        <?php endif; ?>

    </div>

<?php
    return ob_get_clean();
}

add_shortcode('custom_download', 'custom_download_button_shortcode');

//convert URL to path
if(!function_exists('convert_url_to_path')) {
    function convert_url_to_path( $url ) {
        return str_replace( 
            wp_get_upload_dir()['baseurl'], 
            wp_get_upload_dir()['basedir'], 
            $url
        );
      }
}


//get file size
if(!function_exists('formatSizeUnits')) {
	function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
}
