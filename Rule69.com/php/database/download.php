<?php 
session_start();
require 'config.php';

if(isset($_GET['id'])){
  $_SESSION['FileToDisplay']=$_GET['id'];
}

$FileID = $_SESSION['FileToDisplay'];

$sql_search = "SELECT * FROM files WHERE id=$FileID";
$sql_search_result = mysqli_query($link,$sql_search);
$find_elements = mysqli_fetch_array($sql_search_result);

$filename = 'database/files/content/' . $find_elements["file_name"]; // of course find the exact filename....        
header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers 
header('Content-Type: application/pdf');

header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($filename));
readfile($filename);
exit();
?>