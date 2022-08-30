<?php

require_once 'vendor/autoload.php';
include_once('./core/init.php');
$user = new User();

if (!$user->isLoggedIn()) {
    echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
    die();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notification = new Notification();
    
    $content = '<h1>REQUEST ORDER</h1>
    <table style="border: 1px solid black;">' . $_POST['content'] . '</table>
    <h2>REQUESTED BY: ' . $_POST['name'] . '</h2>
    ';
    
    $notification->sendEmail($_POST['email'], $_POST['name'], $content);

    $q = DB::getInstance()->query('UPDATE req_textbooks SET req_code = ?',[generateRandomString()]);
} if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $q = DB::getInstance()->query('SELECT * FROM req_textbooks WHERE id = ?',[$_GET['id']]);
    $result = $q->first();

    $q = DB::getInstance()->query('SELECT * FROM users WHERE id = ?',[$result->user_id]);
    $u_result = $q->first();
    
    $q = DB::getInstance()->query('UPDATE req_textbooks SET is_received = ? WHERE id = ?',[1, $_GET['id']]);
    $notification = new Notification();
    
    $content = "<h1>REQUEST ORDER HAS BEEN Received!</h1> <br />
                <b>YOUR ORDER {$result->textbook_name} with numbers of copies {$result->num_copies} has been Received by staff.</b>";
    
    $notification->sendEmail('eduardo.julaton1997@gmail.com', $_POST['name'], $content);

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