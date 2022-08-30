<?php

include_once('./core/init.php');
$user = new User();

if(!$user->isLoggedIn()) {
   echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
   die();
}

$instance = DB::getInstance();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && !empty($_GET['id']) && !empty($_GET['action']) && $_GET['action'] == 'delete') {
   
   $result = $instance->query('SELECT * FROM textbooks WHERE category_id = ?', [$_GET['id']]);
   if($result->count() > 0) {
      echo json_encode(['error' => true, 'message' => "Category is actively used by textbooks"]);
      die();
   }
   
   $res = $instance->delete('categories', ['id', '=', $_GET['id']]);
    if($res) {
        echo json_encode(['error' => false, 'message' => "Category Successfully Deleted"]);
    } else {
        echo json_encode(['error' => true, 'message' => "Error on Deleted"]);
    }
} else if($_SERVER['REQUEST_METHOD'] == 'GET') {
   $result = $instance->query('SELECT * FROM categories');

   echo json_encode([
      'results' => $result->results(),
      'count' => $result->count()
   ]);
} else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id']) && !empty($_GET['id'])) {
   $result = $instance->query('SELECT * FROM categories WHERE LOWER(genre) = ? AND id != ?', [strtolower($_POST['name']), $_GET['id']]);
   if($result->count() > 0) {
      echo json_encode(['error' => true, 'message' => "Category already exists"]);
      die();
   }

   $result = $instance->update('categories', $_GET['id'], [
      'genre' => $_POST['name'],
      'description' => $_POST['desc'] ?? '',
   ]);

   if($result) {
      echo json_encode(['error' => false, 'message' => "Category Successfully Updated"]);
   } else {
      echo json_encode(['error' => true, 'message' => "Error on Update"]);
   }
} else if($_SERVER['REQUEST_METHOD'] == 'POST') {
   $result = $instance->query('SELECT * FROM categories WHERE LOWER(genre) = ?', [strtolower($_POST['name'])]);
   if($result->count() > 0) {
      echo json_encode(['error' => true, 'message' => "Category already exists"]);
      die();
   }

   $result = $instance->insert('categories', [
      'genre' => $_POST['name'],
      'description' => $_POST['desc'] ?? '',
   ]);

   if($result) {
      echo json_encode(['error' => false, 'message' => "Category Successfully Created"]);
   } else {
      echo json_encode(['error' => true, 'message' => "Error on Create"]);
   }
}

