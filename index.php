<?php

$url = parse_url($_SERVER['REQUEST_URI']);
$path = $url['path'];

$query = '';
if (isset($url['query'])) {
  $query = $url['query'];
}
switch ($path) {
  case '/':
  case '/index':
  case '/login':
    require './Login.php';
    break;

  case '/registration':
    require './Registration.php';
    break;

  case '/home':
    require './Home.php';
    break;

  case '/stock-entry':
    require './StockEntry.php';
    break;

  case '/remove-stock':
    require './RemoveStock.php';
    break;

  case '/edit-stock':
    require './EditStock.php';
    break;

  case '/logout':
    require './Logout.php';
    break;

  default:
    require ('./404.php');
}
