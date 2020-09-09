# Quick Download Button WordPress Plugin
Quick download button is a download button for WordPress. You can easily add a better download link to your post with this plugin. 

![Image of Quick Download Button](https://github.com/kusimo/quick-download-button/blob/master/screenshot/quick-download-button.png)

## Plugin's features: 

* Support for WordPress Gutenberg
* Display file size and file extension. 
* Hide download link
* Support for external download link 
* Shows download file extension for 'pdf','mp3','mov','zip','txt','doc','xml','mp4','ppt' and images ( png, gif, jpg, jpeg, bmp)
* Support for htm, html, ps, tex, xml, txt, csv, xlsx (Microsoft Excel), pptx (Microsoft PowerPoint), js, css, php
* Open external download in new tab
* Force file download


# Basic Usage 
## Gutenberg Block
Open the post you want to add download link to, click on add block icon (+). Under Media, click on Download Button icon. Click on the button to change the title, click on download icon next to the button to upload a file for download.

## Shortcode
Open the post or page you want to add download button to, paste the shortcode example below

```
[quick_download_button title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
```
The **url** value needs to be replaced with your media link URL. The **title** value is for download button title. Default value is Download. 

# Additional Shortcode Usage

## Use external URL

To use external url, add url_external attribute

```
[quick_download_button title="Download" url_external="https://google.com"]
```

## Auto calculate file size. 

To let the plugin generate file size, make sure the file is in the WordPress upload directory e.g wp-content/upload and change filesize value to 1 like below

```
[quick_download_button filesize="1" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
```

## Display file size manually

The plugin can calculate the file size for you by enter 1 in filesize value. You don't have to enter it manually but in case, enter the file size value in the filesize attribute like below.

```
[quick_download_button filesize="14.5MB" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
```


## Show file extention icon
To show file extension icon, set extension value to 1

```
[quick_download_button  title="Download" filesize="1" extension="1" url="http://yoursite/wp-content/upload/fileto_download.pdf"]
```

## Show file extention icon and text

To show both file extension icon and text,  set extension value to 1 and extension_text to 1

```
[quick_download_button  title="Download" filesize="1" extension="1" extension_text="1"  url="http://yoursite/wp-content/upload/fileto_download.pdf"]
```



# To use in a theme file (Developers)

```
echo do_shortcode('[quick_download_button title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]');
```
