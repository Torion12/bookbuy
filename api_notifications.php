<?php

include_once('./core/init.php');
$user = new User();

if(!$user->isLoggedIn()) {
   echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
   die();
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
   $instance = DB::getInstance();

   $result = $instance->get('notifications', ['user_id', '=', $user->data()->id]);

   echo json_encode([
      'results' => $result->results(),
      'count' => $result->count()
   ]);
}