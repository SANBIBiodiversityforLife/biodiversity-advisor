<?php 
/**
 * Update the images
*/
/*
$conn = mysqli_connect('localhost', 'root', '', 'bioadvisor');
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$results = mysqli_query( $conn, "SELECT * FROM wp_posts WHERE post_type = 'attachment' ");

$imgposts = array();
while ($row = mysqli_fetch_array($results, MYSQL_ASSOC)) {
  array_push($imgposts, $row);
}

mysqli_free_result($results);
mysqli_close($conn);

$newdbconnection = mysqli_connect('localhost', 'root', '', 'ba2014');
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

foreach($imgposts as $imgpost) {
    $newguid = str_replace('biodiversityadvisor.sanbi.org', 'ba.localhost', $imgpost['guid']);
    $query = $newdbconnection->prepare("UPDATE wp_posts SET post_mime_type=?, guid=? WHERE ID=?");
    $query->bind_param('ssi', $imgpost['post_mime_type'], $newguid, $imgpost['ID']); 
    $query->execute();
}

mysqli_close($newdbconnection); 
*/
/*
$conn = mysqli_connect('localhost', 'root', '', 'ba2014');
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$results = mysqli_query( $conn, "SELECT * FROM wp_posts WHERE post_type = 'page' and post_content LIKE '%&parent=%'");

while ($row = mysqli_fetch_array($results, MYSQL_ASSOC)) {
   //$query = $conn->prepare("UPDATE wp_posts SET post_content=? WHERE ID=?");
   $newcontent = $row['post_content'];
   print_r($newcontent);
   $newcontent = preg_replace('%&parent=[^"]%', '', $newcontent);
   print_r($newcontent);
   //$query->bind_param('si', $newcontent, $row['ID']); 
   //$query->execute();
}

mysqli_free_result($results);
mysqli_close($conn);
*/
?>