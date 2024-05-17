<?php

require('./vendor/autoload.php');
require('./ConnectDB.php');
$id = [];
$id = explode('=', $url['query']);

/**
 * Start session.
 */
session_start();
$userId = $_SESSION['user_id'];
$message = "";

$db = new ConnectDB();
$conn = $db->connectDB();
$id = $id[1];

define('REMOVESTOCK', "DELETE FROM stock WHERE stock.created_by = $userId AND ID = $id");
$stmt = $conn->prepare(REMOVESTOCK);
if ($stmt->execute()) {
  echo "Stock Deleted";
}
