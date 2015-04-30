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
/*
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
}*/

/* Rukaya's addition to show a list of documents recently added to www.sanbi.org tagged with biodiversity-advisor from the biodiversity advisor feed */

function get_latest_ba_documents_func() {
    
//var_dump('http://www.sanbi.org/documents/biodiversity-advisor-feed http://www.sanbi.org/information/documents/feed');
   // Get the RSS feed
   $rss = simplexml_load_file('http://www.sanbi.org/information/documents/ba-feed');



   // Set the number of words we want in the description for each item
   $wordlength = 250; 
   $output = '<div class="rss">';
   foreach($rss->channel->item as $item) {
        // Get the full description (this is all the text in the node)
        $desc = $item->description;
        
        // Retrieve the first image
        preg_match('/<img.+src=[\'"]\/sites\/default(?P<src>.+?)[\'"].*>/i', $desc, $image);
        $image = $image['src'];
        
        // Select the actual field-field-description
        preg_match('#<div class="field field-type-text field-field-description">.*\n.*\n.*\n.*\n(?P<desc>.+?)</div>#', $desc, $descfield);
        $descfield = $descfield['desc'];
                
        // Add all the other elements into vars
        $link = $item->link;
        $title = $item->title;
        $date = date('d F Y', strtotime($item->pubDate));
        
        // Print out the RSS item
        $output = $output . '<article><h1><a href="' . $link  . '" title="' . $title . '">' . $title . '</a> <small>Published on ' . $date . '</small></h1>';
        if($image) 
            $output .= '<img src="http://www.sanbi.org/sites/default' . $image . '" alt="News item image" class="img-thumbnail">';
        $output .= '<p>' . $descfield . '</p></article>';
    }
    $output .= '<p class="rss-footer">View more documents on <a href="http://www.sanbi.org/information/documents">sanbi.org</a></p></div>';
    return $output;
}
add_shortcode( 'get_latest_ba_documents', 'get_latest_ba_documents_func' );

?>
