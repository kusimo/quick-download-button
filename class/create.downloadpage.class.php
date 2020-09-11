<?php 

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

    class CreateDownloadPage {
        private $current_user, $page, $pid, $title;

        public function quickDownloadPage() {

            if ( ! current_user_can( 'activate_plugins' ) ) return;

            global $wpdb;
            $this->title = 'Quick Download Button';
  
            if( null == get_page_by_title( $this->title, 'OBJECT', 'post' ) & $this->post_check_duplicates($this->title)!==1) { 
                
                $this->current_user = wp_get_current_user();

                // create post object
                $this->page = array(
                    'post_title'  => __( 'Quick Download Button' ),
                    'post_status' => 'publish',
                    'post_author' => $this->current_user->ID,
                    'post_type'   => 'page',
                );
                
                // insert the post into the database
                $this->pid = wp_insert_post( $this->page );

                // Store the page ID (to be used later for download page template)
                if (FALSE === get_option('quick_download_button_page_id') && FALSE === update_option('quick_download_button_page_id',FALSE)) add_option('quick_download_button_page_id', $this->pid);

                //Check post ID and Update post id after re-activation
                if ( $this->pid !== get_option('quick_download_button_page_id') ) update_option( 'quick_download_button_page_id', $this->pid);
            }

        }

        /* Prevent duplicate page title on plugin reactivation */

        public function post_exists( $title, $content = '', $date = '', $type = '' ) {
            global $wpdb;

            $post_title   = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );
            
            $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
            $args  = array();

            
            if ( ! empty( $title ) ) {
                $query .= ' AND post_title = %s';
                $args[] = $post_title;
            }

            if ( ! empty( $args ) ) {
                return (int) $wpdb->get_var( $wpdb->prepare( $query, $args ) );
            }

            return 0;
        }
        public function post_check_duplicates($title) { 
                
            $post_id = post_exists( $title );
            if (!$post_id) {
                return 0;
            } else {
                return 1;
            }
            
        }

        public function getid_duplicates($title) { 
                
            $post_id = post_exists( $title );
            if ($post_id) {
                return $post_id;
            } 	
        }

}


