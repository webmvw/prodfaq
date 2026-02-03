<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Optional: delete all FAQ meta
delete_post_meta_by_key( '_prodfaq_items' );
