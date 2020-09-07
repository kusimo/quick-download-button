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


}

//Shortcode option


function custom_download_button_shortcode($atts, $content = null) {
    $a = shortcode_atts(array(
        'title' => 'Download',
        'bgcolor' => 'default',
        'filesize' => '',
        'duration' => '',
        'url' => '',
        'extension' => '',
        'extension_text' => '0',
        'hotlink' => false,
    ), $atts);

    global $post;
    $pid = $post->ID;
    $hotlink_url = '/dl.php?aid='.$pid;
    

    ob_start();
    ?>

    <div class="button--download" style="margin: 6rem auto;width: 200px;">
      <form method="post" action="<?php echo !empty($a['url'])? $a['url'] : $hotlink_url ; ?>">
      <button class="g-btn f-l bsbtn d-block position-relative shadow rounded-lg border-0" type="submit" style="z-index:2;height:50px; width:200px;" title="Download" data-pid="<?php echo $pid; ?>"><?php echo $a['title']; ?></button>
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
            echo '<p class="down"><i class="fi-folder-o"></i>';
                $file_url = filesize(convert_url_to_path($a['url']) );
                $file_size = formatSizeUnits($file_url);
                if('0 bytes' != $file_size) echo formatSizeUnits($file_url);
            echo '</p>';

        elseif('' !== $a['filesize']) :
            echo '<p class="down"><i class="fi-folder-o"></i> ';
                    echo $a['filesize'];
            echo '</p>';

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
