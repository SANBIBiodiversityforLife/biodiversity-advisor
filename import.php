<?php 
/*$pattern = '#http\://biodiversityadvisor\.sanbi\.org/\?post_type\=3rd_level_items=preparation\-of\-systematic\-biodiversity\-plan[^"]+?#';
$pattern = '#http\://biodiversityadvisor\.sanbi\.org/\?3rd_level_items=preparation\-of\-systematic\-biodiversity\-plan[^"]*?#';
$replace = 'http://ba.localhost/preparation-of-systematic-biodiversity-plan/';
$string = 'http://biodiversityadvisor.sanbi.org/?3rd_level_items=preparation-of-systematic-biodiversity-plan';        
echo $string;
echo '<br />' .preg_replace($pattern, $replace, $string);
exit();*/

$conn = mysql_connect('localhost', 'root', '');
mysql_select_db('bioadvisor', $conn);
$results = mysql_query("SELECT * FROM wp_posts WHERE post_type IN ('page','home', 'home_items', '2nd_level_items', '3rd_level_items') AND post_status = 'publish'", $conn);

// Bring wordpress in 
require('./wp-load.php');

// Set up some wordpress stuff so we can import cleanly
global $wpdb;
kses_remove_filters();
remove_filter('content_save_pre', 'wp_filter_post_kses');
remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');

// Keep track of the links
$linkage = array();
$posts = array();

// Loop through the old posts and insert them into the new db
while ($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $post = array();
  
    // Clean up the HTML a little
    $content = str_replace(' align="justify"', '', $row['post_content']);
  
    // Make a note of each old guid, later on they will be mapped onto each new one. Note we're adding some regex [^"]+ onto the end here
    if(in_array($row['post_type'], array('home_items', '2nd_level_items', '3rd_level_items'))) 
        $linkage[$row['ID']] = array('old' => preg_quote('http://biodiversityadvisor.sanbi.org/?') . preg_quote($row['post_type']) . '=' . preg_quote($row['post_name']) . '[^"]*?', 'new' => '');
    else
        $linkage[$row['ID']] = array('old' => $row['guid'], 'new' => '');
    
    $post['post_content'] = $content;  //$wpdb->escape(wp_filter_kses(stripslashes($row['post_content'])));
    $post['import_id'] = $row['ID'];
    $post['post_name'] =  $row['post_name'];
    $post['post_title'] =  $wpdb->escape(stripslashes(mysql_real_escape_string($row['post_title']))); //$wpdb->escape($row['post_title']);
    $post['post_status'] =  $row['post_status'];
    $post['post_type'] =  'page';
    $post['post_author'] =  $row['post_author'];
    $post['post_excerpt'] =  $row['post_excerpt'];
    $post['post_date'] = date('Y-m-d H:i:s', strtotime($row['post_date']));
    $post['post_date_gmt'] = date('Y-m-d H:i:s', strtotime($row['post_date_gmt']));
    $post['post_modified'] = date('Y-m-d H:i:s', strtotime($row['post_modified']));
    $post['post_modified_gmt'] = date('Y-m-d H:i:s', strtotime($row['post_modified_gmt']));
    $post['filter'] = true;
  
    if($post['post_title'] == 'Planning and Assessment') 
        $post['post_title'] = 'Planning & Assessment';
    if($post['post_title'] == 'Biodiversity and Industry') 
        $post['post_title'] = 'Biodiversity & Industry';
    if($post['post_title'] == 'Checklists and Modelling') 
        $post['post_title'] = 'Checklists & Modelling';
    if($post['post_title'] == 'Resources') 
        $post['post_title'] = 'Documents & Links';
    if($post['post_title'] == 'About Us') 
        $post['post_title'] = 'About';
    if($post['post_title'] == 'Contact Us') 
        $post['post_title'] = 'Contact';

    $pid = wp_insert_post($post, true);
    
    // Map the new guid in and store the post so we can go over them all later
    $linkage[$pid]['new'] = get_the_guid ($pid);
    
    $post['post_parent'] = $row['post_parent'];
    $posts[$pid] = $post;  
}
mysql_free_result($results);

// Loop through each post and replace the old link with the new link
$newdbconnection = mysqli_connect('localhost', 'root', '', 'ba2014');
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

foreach($posts as $id => $post) {
    $post['ID'] = $id;
    $content = $post['post_content'];
    //if($post['post_title']  == 'Systematic biodiversity planning' || $post['post_title']  == 'Planning & Assessment')
     //   print_r($content);
    foreach($linkage as $oldid => $link) {
        $pattern = '|' . $link['old'] . '|';
        $content = preg_replace($pattern, $link['new'], $content);
        //echo ' |||    pattern ' . $pattern;
        //echo ' // link ' . $link['new'];
        
        //pattern |http://biodiversityadvisor.sanbi.org/?post_type=3rd_level_items=systematic-biodiversity-planning[^"]+?| // link http://ba.localhost/systematic-biodiversity-planning/
        // pattern |http\://biodiversityadvisor\.sanbi\.org/\?post_type\=3rd_level_items=preparation\-of\-systematic\-biodiversity\-plan[^"]+?| // link http://ba.localhost/preparation-of-systematic-biodiversity-plan/ 
        
        // http://biodiversityadvisor.sanbi.org/?3rd_level_items=preparation-of-systematic-biodiversity-plan
    }
    /*if($post['post_title']  == 'Systematic biodiversity planning' || $post['post_title']  == 'Planning & Assessment') {
        echo "------------------";
        print_r($content); 
        exit();
    }*/
    /*$post['content'] = $content;print_r($post['content']);
    print_r(wp_update_post($post, true));
    print_r(get_post($id)->post_content);
    exit();*/
    
    // Ok that approach doesn't seem to work. Let's try something else.
    
    $query = $newdbconnection->prepare("UPDATE wp_posts SET post_content=? WHERE ID=?");
    $query->bind_param('si', $content, $id); 
    $query->execute();
    //print "<br>Replaced " . $post['post_parent'];
}

//print_r($linkage); 


/**
 * Add the attachments 
 */
 $attachments = array();
 
$results = mysql_query("SELECT * FROM wp_posts WHERE post_type  = 'attachment'", $conn);
while ($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
  $attachment = array();
  $attachment['import_id'] = $row['ID'];
  $attachment['post_content'] = utf8_encode($row['post_content']);  //$wpdb->escape(wp_filter_kses(stripslashes($row['post_content'])));
  $attachment['post_name'] =  $row['post_name'];
  $attachment['post_guid'] =  $row['post_guid'];
  $attachment['post_title'] =  $wpdb->escape(stripslashes(mysql_real_escape_string($row['post_title']))); //$wpdb->escape($row['post_title']);
  $attachment['post_status'] =  $row['post_status'];
  $attachment['post_type'] =  'attachment';
  $attachment['post_author'] =  $row['post_author'];
  $attachment['post_date'] = date('Y-m-d H:i:s', strtotime($row['post_date']));
  $attachment['post_date_gmt'] = date('Y-m-d H:i:s', strtotime($row['post_date_gmt']));
  $attachment['post_modified'] = date('Y-m-d H:i:s', strtotime($row['post_modified']));
  $attachment['post_modified_gmt'] = date('Y-m-d H:i:s', strtotime($row['post_modified_gmt']));
  $attachment['filter'] = true;
  $attachments[$i] = $attachment;  
  $i++;
}
mysql_free_result($results);
mysql_close($conn);

foreach ($attachments as $attachment) {
    $pid = (wp_insert_post($attachment, true));
}

kses_init_filters();
add_filter('content_save_pre', 'wp_filter_post_kses');
add_filter('content_filtered_save_pre', 'wp_filter_post_kses');

/**
 * Insert the metadata
 */

$conn = mysqli_connect('localhost', 'root', '', 'bioadvisor');
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$results = mysqli_query( $conn, "SELECT * FROM wp_postmeta WHERE meta_key IN ('_display_thumbnail','display_thumbnail', '_thumbnail_id', '_wp_attached_file', '_wp_attachment_image_alt', '_wp_attachment_metadata') ");

$postmetas = array();
while ($row = mysqli_fetch_array($results, MYSQL_ASSOC)) {
  array_push($postmetas, $row);
}

mysqli_free_result($results);
mysqli_close($conn);

$newdbconnection = mysqli_connect('localhost', 'root', '', 'ba2014');
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

foreach($postmetas as $postmeta) {
    $query = $newdbconnection->prepare("INSERT INTO wp_postmeta (meta_id, post_id, meta_key, meta_value) VALUES (?, ?, ?, ?)");
    $query->bind_param('iiss', $postmeta['meta_id'], $postmeta['post_id'], $postmeta['meta_key'], $postmeta['meta_value']); 
    $query->execute();
}

mysqli_close($newdbconnection);

?>