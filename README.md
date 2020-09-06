# Custom Download Button WordPress Plugin
Wordpress custom download button using block and shortcode option. The plugin displays file size and file extension. 


# Display download button only
To display download button only use the code as follow
[custom_download title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]

# Display file size manually
The plugin can calculate the file size for you by enter 1 in filesize value. You don't need to enter it manually but in case, enter the file size value in the filesize attribute like below.

[custom_download filesize="4.5MB" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]

# Auto calculate file size. 
To let the plugin generate file size, make sure the file is in the WordPress upload directory e.g wp-content/upload and change filesize value to 1 like below

[custom_download filesize="1" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]

# Show file extention icon
To show file extension icon, set extension value to 1

[custom_download  title="Download" filesize="1" extension="1" url="http://yoursite/wp-content/upload/fileto_download.pdf"]

# Show file extention icon and text
To show both file extension icon and text,  set extension value to 1 and extension_text to 1

[custom_download  title="Download" filesize="1" extension="1" extension_text="1"  url="http://yoursite/wp-content/upload/fileto_download.pdf"]



# To use in a theme file

<?php 
echo do_shortcode('[custom_download title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]');
?>
