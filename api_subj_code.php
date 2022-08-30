<?php

include_once('./core/init.php');
$user = new User();

// if(!$user->isLoggedIn()) {
//    echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
//    die();
// }

$instance = DB::getInstance();
if($_SERVER['REQUEST_METHOD'] == 'GET') {
   $q = $instance->query('SELECT id, subject_code FROM textbooks GROUP BY subject_code');

   echo json_encode(['results' => $q->results()]);
   die();
}