<?php

/**
 * Plugin Name: Custom Download
 * Plugin URI: https://github.com/kusimo/custom-download
 * Description: Custom block plugin for download button with color and file extension options.
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
		plugins_url( 'build/css/editor.css', __FILE__ ),					// CSS file
		array( 'wp-edit-blocks' ),										// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'build/css/editor.css' )	// set version as file last modified time
	);

	// Register the front-end stylesheet.
	wp_register_style(
		'custom-download-front-end-styles',										// label
		plugins_url( 'build/css/style.css', __FILE__ ),						// CSS file
		array( ),														// dependencies
		filemtime( plugin_dir_path( __FILE__ ) . 'build/css/style.css' )	// set version as file last modified time
    );
    
    //register blocks script and styles
    register_block_type( 'custom-download/download-button', array(
        'editor_script' => 'custom-download-editor-script',					// Calls registered script above
		'editor_style' => 'custom-download-editor-styles',					// Calls registered stylesheet above
		'style' => 'custom-download-front-end-styles',	
    ) );


}

