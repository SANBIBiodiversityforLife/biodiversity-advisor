<?php
/*
All the functions are in the PHP pages in the func/ folder.
*/

require_once locate_template('/func/cleanup.php');
require_once locate_template('/func/setup.php');
require_once locate_template('/func/enqueues.php');
require_once locate_template('/func/navbar.php');
require_once locate_template('/func/widgets.php');
require_once locate_template('/func/feedback.php');

/* Rukaya's addition to show all pages a user has authored when one edits a user */

add_action( 'show_user_profile', 'display_all_authored_pages' );
add_action( 'edit_user_profile', 'display_all_authored_pages' );

function display_all_authored_pages( $user ) { 
    $query1 = new WP_Query(array('author' => $user->ID, 'post_type' => 'page', 'posts_per_page'=>-1 ));

    ?> 
    <h2>Author of the following pages:</h2>
    <ul> 
    <?php
    while ( $query1->have_posts() ) {
        $query1->the_post();
        ?>
        <li><?php the_title(); ?> - <a href="<?php echo get_edit_post_link(get_the_ID()); ?>">Edit page</a> | <a href="<?php echo get_permalink() ?>">View on the website</a></li>
        <?php 
    }
    ?>
    </ul>
<?php
}

add_action( 'init', 'create_posttype' );
function create_posttype() {
  register_post_type( 'seakey',
    array(
      'labels' => array(
        'name' => __( 'Seakeys species' ),
        'singular_name' => __( 'Seakeys species' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'seakeys'),
    )
  );
}

?>
