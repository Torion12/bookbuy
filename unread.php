<?php

include_once('./core/init.php');
$user = new User();

if(!$user->isLoggedIn()) {
   echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
   die();
}

$instance = DB::getInstance();
if($_SERVER['REQUEST_METHOD'] == 'GET') {
   $q = $instance->query('UPDATE notifications SET unread = 0 WHERE id = ?', [$_GET['id']]);

   echo json_encode(['done' => true]);
   die();
}