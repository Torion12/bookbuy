<?php

include_once('./core/init.php');
$user = new User();

if(!$user->isLoggedIn()) {
   echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
   die();
}

$instance = DB::getInstance();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && !empty($_GET['id']) && !empty($_GET['action']) && $_GET['action'] == 'delete') {
   $res = $instance->delete('textbooks', ['id', '=', $_GET['id']]);
    if($res) {
        echo json_encode(['error' => false, 'message' => "Textbook Successfully Deleted"]);
    } else {
        echo json_encode(['error' => true, 'message' => "Error on Deleted"]);
    }
} else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && !empty($_GET['id']) && !empty($_GET['action']) && $_GET['action'] == 'stocks') {
   $res = $instance->get('textbooks', ['id', '=', $_GET['id']]);
   $first = $res->first();

   if($_GET['quantity'] > $first->quantity) {
      echo json_encode(['error' => true, 'message' => "({$first->quantity}) textbook left. Please place an order with maximum ({$first->quantity})"]);
   } else {
      echo json_encode(['error' => false, 'message' => ""]);
   }
} else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) {
   $q = $instance->get('textbooks', ['id', '=', $_GET['id']]);
   $result = $q->first();

   $q = $instance->get('categories', ['id', '=', $result->category_id]);
   $result->category = $q->first();

   $q = $instance->query('SELECT SUM(od.quantity) as od_quantity
                          FROM textbooks t 
                          LEFT JOIN order_details od 
                          ON t.id = od.textbook_id 
                          WHERE t.id = ? AND od.status = "pending"', [$_GET['id']]);
   $q_result = $q->first();
   $result->quantity = $result->quantity - $q_result->od_quantity;

   echo json_encode([
      'result' => $result
   ]);
} else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {

   $sql = 'SELECT t.*,c.genre as course_type FROM textbooks t LEFT JOIN categories c ON t.category_id = c.id';

   $where = '';
   if(isset($_GET['filter_by']) && !empty($_GET['filter_by']) && !empty($_GET['search'])) {
      $where .= ' LOWER(' . $_GET['filter_by'] . ') LIKE "%' . strtolower($_GET['search']) . '%"';
   } else if(!empty($_GET['search'])) {
      $where .= ' LOWER(t.textbook_name) LIKE  "%' . strtolower($_GET['search']) . '%"';
      $where .= ' OR LOWER(t.subject_code) LIKE  "%' . strtolower($_GET['search']) . '%"';
      $where .= ' OR LOWER(t.textbook_desc) LIKE  "%' . strtolower($_GET['search']) . '%"';
      $where .= ' OR LOWER(t.textbook_status) LIKE  "%' . strtolower($_GET['search']) . '%"';
      $where .= ' OR LOWER(t.department) LIKE  "%' . strtolower($_GET['search']) . '%"';
   }

   if(!empty($where) && isset($_GET['category']) && !empty($_GET['category'])) {
      $where = ' WHERE (' . $where . ') AND category_id = ' . $_GET['category'];
   } else if(isset($_GET['category']) && !empty($_GET['category'])){
      $where .= ' WHERE category_id = ' . $_GET['category'];
   } else if(!empty($where)){
      $where = ' WHERE ' . $where;
   }

   if(!empty($where) && isset($_GET['department']) && !empty($_GET['department'])) {
      $where .= ' OR LOWER(t.department) = "' . strtolower($_GET['department']) . '"';
   } else if(empty($where) && isset($_GET['department']) && !empty($_GET['department'])) {
      $where .= ' WHERE LOWER(t.department) = "' . strtolower($_GET['department']) . '"';
   }

   if(!empty($where)) {
      $sql .= $where;
   }

   // var_dump($sql);
   // die();
   $q = $instance->query($sql);
   $result = $q->results();

   echo json_encode([
      'results' => $result
   ]);
} else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['department']) && !empty($_GET['department'])) {
   $sql = 'SELECT t.*,c.genre as course_type 
           FROM textbooks t 
           LEFT JOIN categories c
           ON t.category_id = c.id 
           WHERE t.textbook_status = "active" AND LOWER(t.department) = "' . $_GET['department'] . '"';

   $q = $instance->query($sql);
   
   echo json_encode([
      'results' => $q->results(),
      'count' => $q->count()
   ]);
}  else if($_SERVER['REQUEST_METHOD'] == 'GET') {
   $sql = 'SELECT t.*,c.genre as course_type 
           FROM textbooks t 
           LEFT JOIN categories c
           ON t.category_id = c.id 
           WHERE t.textbook_status = "active"';

   $q = $instance->query($sql);
   
   echo json_encode([
      'results' => $q->results(),
      'count' => $q->count()
   ]);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id']) && !empty($_GET['id'])) {
   $update = $instance->update('textbooks', $_GET['id'], array(
       'department'     => $_POST['department'],
       'textbook_name'  => $_POST['textbook_name'],
       'subject_code'   => $_POST['subject_code'],
       'textbook_price' => $_POST['textbook_price'],
       'category_id'    =>  $_POST['category'],
       'store_available'=> $_POST['store_available'],
       'publisher'      => $_POST['publisher'],
       'author'         => $_POST['author'],
       'edition'        => $_POST['edition'],
       'quantity'        => $_POST['quantity'],
   ));

   if($update) {
       echo json_encode(['error' => false, 'message' => "Textbook Successfully Updated"]);
   } else {
       echo json_encode(['error' => true, 'message' => "Error on update"]);
   }
} 
