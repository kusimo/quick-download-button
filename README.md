# custom-download
Wordpress custom download button using block and shortcode option

# shortcode option example
[custom_download filesize="4.5MB" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]

# display download button only
[custom_download title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]

# To let the plugin generate file size, make sure the file is in the WordPress upload directory e.g wp-content/upload and change filesize value to 1 like below

[custom_download filesize="1" title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]

# To use in a theme file

echo do_shortcode('[custom_download title="Download" url="http://yoursite/wp-content/upload/fileto_download.pdf"]');
