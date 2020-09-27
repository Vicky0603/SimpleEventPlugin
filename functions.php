
<?php
/**
 * Create roles and capabilities
 */

function wps_add_role() {
    add_role( 'organiser', 'Organiser',
        array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
        )
    );
}
add_action( 'init', 'wps_add_role' );


