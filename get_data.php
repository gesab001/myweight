<?php
include "../database.php";




try {
  $db = new PDO("mysql:host=localhost;dbname=$database_weight", $user, $password);

  $rows = array();
  foreach($db->query("SELECT * FROM $table_weight ORDER BY ID ASC") as $row) {
    array_push($rows, $row);

  } 
  header("Content-Type: application/json");
  echo json_encode($rows);
  exit();
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
