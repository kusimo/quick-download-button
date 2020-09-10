=== Quick Download Button ===
Contributors: kusimo
Tags: media download, hide download link, download button
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7
Tested up to: 5.5
Stable tag: 4.4.6
Requires PHP: 5.6 

Quick download button with block and shortcode support.

== Description ==
Quick download button is a download button for WordPress. You can easily add a better download link to your post with this plugin.


== Installation ==
1. Upload the quick-download-button folder to the /wp-content/plugins/ directory
2. Activate the Delete Quick Download Button plugin through the \'Plugins\' menu in WordPress

== Features ==

* Support for WordPress Gutenberg
* Display file size and file extension. 
* Hide download link
* Support for external download link 
* Shows download file extension for 'pdf','mp3','mov','zip','txt','doc','xml','mp4','ppt' and images ( png, gif, jpg, jpeg, bmp)
* Support for htm, html, ps, tex, xml, txt, csv, xlsx (Microsoft Excel), pptx (Microsoft PowerPoint), js, css, php
* Open external download in new tab
* Force file download


 == Basic Usage ==

 ** Gutenberg Block **
Open the post you want to add download link to, click on add block icon (+). 
Under Media, click on Download Button icon. Click on the button to change the title, click on download icon next to the button to upload a file for download.

** Shortcode **
Open the post or page you want to add download button to, paste the shortcode example below

`
[quick_download_button title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
`
The url value needs to be replaced with your media link URL. The title value is for download button title. Default value is Download. 

== More Shortcode Usage ==

** Use external URL **

To use external url, add url_external attribute

`
[quick_download_button title="Download" url_external="https://google.com"]
`

** Auto calculate file size. **

To let the plugin generate file size, make sure the file is in the WordPress upload directory e.g wp-content/upload and change filesize value to 1 like below

`
[quick_download_button filesize="1" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
`

** Display file size manually **

The plugin can calculate the file size for you by enter 1 in filesize value. You don't have to enter it manually but in case, enter the file size value in the filesize attribute like below.

`
[quick_download_button filesize="14.5MB" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
`


** Show file extention icon **
To show file extension icon, set extension value to 1

`
[quick_download_button  title="Download" filesize="1" extension="1" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
`

** Show file extention icon and text **

To show both file extension icon and text,  set extension value to 1 and extension_text to 1

`
[quick_download_button  title="Download" filesize="1" extension="1" extension_text="1"  url="http://yoursite/wp-content/upload/fileto_download.pdf"]
`



== To use in a theme file (Developers) ==

`
echo do_shortcode('[quick_download_button title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]');
`

== Frequently Asked Questions ==

= Can this plugin be used to hide (protect) download link on my site? =

Yes.

= Can this plugin be used to display download file size on my site? =

Yes.
