<?php
/**
 * This file could be used to catch submitted form data. When using a non-configuration
 * view to save form data, remember to use some kind of identifying field in your form.
 */
 
?>
<?php 
$user = wp_get_current_user(); 

// The Query
$query1 = new WP_Query(array('author' => $user->ID, 'post_type' => 'page', 'posts_per_page'=>-1));


// The Loop
?> 
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
/* Restore original Post Data 
 * NB: Because we are using new WP_Query we aren't stomping on the 
 * original $wp_query and it does not need to be reset with 
 * wp_reset_query(). We just need to set the post data back up with
 * wp_reset_postdata().
 */
wp_reset_postdata();

?>
<?php //echo self::get_dashboard_widget_option(self::wid, 'example_number'); ?>