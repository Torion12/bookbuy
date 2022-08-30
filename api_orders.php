<?php

require_once 'vendor/autoload.php';
include_once('./core/init.php');
$user = new User();

if(!$user->isLoggedIn()) {
   echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
   die();
}

$instance = DB::getInstance();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['order_id']) && !empty($_GET['order_id'])) { 

   $q = $instance->query('SELECT *, od.quantity as od_quantity FROM order_details od LEFT JOIN textbooks t ON od.textbook_id = t.id WHERE od.order_id = ?', [$_GET['order_id']]);

   echo json_encode([
      'results' => $q->results()
   ]);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_number']) && !empty($_GET['id_number'])) {

   $res = $instance->get('users', ['number_id', '=', $_GET['id_number']]);
   if($res) {
      echo json_encode(['error' => false, 'count' => $res->count()]);
   } else {
      echo json_encode(['error' => true, 'message' => "Error on Deleted"]);
   }
} else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['status']) && !empty($_GET['status'])) { 
   if($_GET['status'] == 'paid') {

      $sql = "SELECT
               *,t.department as dept, od.quantity as od_quantity,
               od.created_at as od_created_at, od.id_number as od_id_number,
               CONCAT(u.first_name, ' ', u.last_name) as u_full_name,
               od.full_name as od_full_name
              FROM order_details od
              LEFT JOIN users u ON od.user_id = u.id
              LEFT JOIN textbooks t ON od.textbook_id = t.id
              WHERE status = 'paid'";

      $where = '';
      if(isset($_GET['date_from']) && !empty($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_to'])) {
         $where .= " AND DATE(od.created_at) BETWEEN '" . $_GET['date_from'] . "' AND '" . $_GET['date_to'] . "'";
      }

      if(isset($_GET['subject_code']) && !empty($_GET['subject_code'])) {
         $where .= " AND LOWER(t.id) = " . $_GET['subject_code'];
      }
      // var_dump($sql . $where);
      // die();
      $q = $instance->query($sql . $where);

      $orders = [];
      foreach($q->results() as $result) {
         if(!array_key_exists($result->order_id, $orders)) {
            $orders[$result->order_id] = [];
         }
         $orders[$result->order_id][] = $result;
      }

      echo json_encode([
         'results' => $orders
      ]);
      die();
   } else {
      $q = $instance->query('SELECT * FROM order_details WHERE status = ?', [$_GET['status']]);
   }

   echo json_encode([
      'results' => $q->results()
   ]);

} else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["payment_type"]) && !empty($_POST["payment_type"])) {
   $order_id = $_POST["order_id"];

   $history = $instance->insert('payment_history', [
      'order_id' => $order_id,
      'payment_date' => $_POST["payment_date"],
      'payment_type' => $_POST["payment_type"],
      'or_number' => $_POST["or_number"],
   ]);

   $get_orders = $instance->get('order_details', ['order_id', '=', $order_id]);

   if($get_orders->count() > 0) {
      foreach($get_orders->results() as $_order) {
         $order = $instance->query('UPDATE textbooks SET sold = sold + ? WHERE id = ?', [$_order->quantity, $_order->textbook_id]);
      }
   }

   $order = $instance->query('UPDATE order_details SET status = "paid" WHERE order_id = ?', [$order_id]);

   echo json_encode([
      'order_id' => $order_id,
      'payment_date' => $_POST["payment_date"],
      'payment_type' => $_POST["payment_type"],
   ]);
} else if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $orders = Input::get("orders");
      $order_id = generateRandomString();

      $user_q = $instance->get('users', ['id_number', '=', Input::get("id_number")]);
      $user_res = $user_q->count() > 0 ? $user_q->first()->id : false;

      $html = `
            <tr>
               <td>Item</td>
               <td>Quantity</td>
               <td>Subtotal</td>
            </tr>
         `;
      $total = 0;
      foreach($orders as $order) {

         $result = $instance->insert('order_details', [
            'textbook_id' => $order['id'],
            'order_id' => $order_id,
            'quantity' => $order['quantity'],
            'total' => $order['total'],
            'price' => $order['price'],
            'user_id' => $user_res ? $user_q->first()->id : '0',
            'id_number' => $user_res ? $user_q->first()->id : Input::get("id_number"),
            'edp_code' => $_POST['edp_code'],
            'full_name' => $_POST['full_name'],
         ]);

         $get_text = $instance->query('SELECT * FROM textbooks WHERE id = ?', [$order['id']]);

         $html .= `
            <tr>
               <td>` . $get_text->first()->textbook_name . `</td>
               <td>` . $order['quantity'] . `</td>
               <td>` . $order['total'] . `</td>
            </tr>
         `;
         $total += $order['total'];

         // decrement stocks
         $instance->query("UPDATE textbooks SET textbook_stock = ? WHERE id = ?", [($get_text->first()->textbook_stock - 1), $order['id']]);
      }

      $html .= `
            <tr>
               <td colspan="2"><b>TOTAL:</b></td>
               <td>` . $total . `</td>
            </tr>
         `;
      
      if($user->hasPermission('staff') && $user_res) {
         $message = "Staff {$user->data()->first_name} {$user->data()->last_name} added an order for {$user_q->first()->first_name} {$user_q->first()->last_name}.";
      } else if($user->hasPermission('staff') && !$user_res) {
         $message = "Staff {$user->data()->first_name} {$user->data()->last_name} added an order for ID Number #" . Input::get('id_number'). ".";
      } else {
         $message = "{$user->data()->first_name} {$user->data()->last_name} added an order.";
      }
      $notification = new Notification();
      $notification->sendNotification('staff', 'notification',
         [
            'message' => $message
         ]
      );

      if($user->hasPermission('student')) {
         $send_to = $user->data()->email;
         $name = $user->data()->first_name . ' ' . $user->data()->last_name; 
      } else if($user->hasPermission('staff') && $user_res) {
         $send_to = $user_q->first()->email;
         $name = $user_q->first()->first_name . ' ' . $user_q->first()->last_name; 
      } else {
         $send_to = '';
         $name = '';
      }
      if($send_to) {
         $html_body = `<table style="border:2px solid black;">` . $html . `</table>`;
         $notification->sendEmail($send_to, $name, "
         <b>{$name} added an order. </b>
         <br />
         " . $html_body . "
         ");
      }
   echo json_encode(['error' => false, 'errors' => '']);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}