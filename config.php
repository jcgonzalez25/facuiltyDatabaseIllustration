<?php
$db		= "cs47908";
$db_user	= "cs47908";
$db_password	= "082587Qi";
$db_host	= "localhost";

$myconn = new mysqli($db_host, $db_user, $db_password, $db);
if ($myconn->connect_error) {
  die("Failed to connect to database (" . $myconn->connect_errno . "): "
    . $myconn->connect_error);
}


function requestQuery($query){
  global $myconn;
  $res = $myconn->query($query);
  if($res){
    return $res;
  }else{
    echo "No Rows Found ";
  }
}

function getColumns($tab){
  global $myconn;
  $fields = [];
  $info = requestQuery("SHOW COLUMNS FROM " . $tab);
  while($row = $info->fetch_row()[0]){array_push($fields, $row);}
  return $fields;
}
?>
