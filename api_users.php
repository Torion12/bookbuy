<?php

require_once 'vendor/autoload.php';
include_once('./core/init.php');
$user = new User();

if (!$user->isLoggedIn()) {
    echo json_encode(['error' => true, 'message' => 'Unauthorized Access']);
    die();
}

$instance = DB::getInstance();
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])
    && !empty($_GET['id']) && isset($_GET['action'])
    && !empty($_GET['action']) && $_GET['action'] == 'delete') {

    $res = $instance->delete('users', ['id', '=', $_GET['id']]);
    if($res) {
        echo json_encode(['error' => false, 'message' => "User Successfully Deleted"]);
    } else {
        echo json_encode(['error' => true, 'message' => "Error on Deleted"]);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $q_users = "SELECT * FROM users";
    if (isset($_GET['role']) && !empty($_GET['role'])) {
        $param = strtolower($_GET['role']);
        $query = "SELECT * FROM roles WHERE name = ?";

        $q = $instance->query($query, [$param]);

        if ($q->count() > 0) {
            $q_users .= " WHERE role_id = ?";
            $param = $q->first()->id;


            $q = $instance->query("SELECT users.id,id_number, email, contact_number, first_name, middle_name, last_name, address, `name` as role FROM users LEFT JOIN roles ON users.role_id = roles.id WHERE users.role_id = ?", [$q->first()->id]);
            echo json_encode(['results' => $q->results()]);
            die();
        }
    } else if (isset($_GET['id']) && !empty($_GET['id'])) {
        $q = $instance->query("SELECT users.id, id_number, email, contact_number, first_name, middle_name, last_name, address, `name` as role FROM users LEFT JOIN roles ON users.role_id = roles.id WHERE users.id = ?", [$_GET['id']]);
        echo json_encode(['user' => $q->first()]);
        die();
    }

    $q = $instance->query("SELECT * FROM users");
    echo json_encode(['results' => $q->results()]);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id']) && !empty($_GET['id'])) {

    $q = $instance->get('roles', ['name', '=', ucfirst(Input::get('role'))]);
    $role_id = $q->first()->id;

    $update = $instance->update('users', $_GET['id'], array(
        'id_number'     => $_POST['id_number'],
        'first_name'    => $_POST['first_name'],
        'last_name'     => $_POST['last_name'],
        'middle_name'   => $_POST['middle_name'],
        'contact_number'=> $_POST['contact_number'],
        'email'         => $_POST['email'],
        'address'       => $_POST['address'],
        'role_id'       => $role_id
    ));

    if($update) {
        echo json_encode(['error' => false, 'message' => "User Successfully Updated"]);
    } else {
        echo json_encode(['error' => true, 'message' => "Error on update"]);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $q = $instance->get('roles', ['name', '=', ucfirst($_POST['role'])]);
    $role_id = $q->first()->id;

    $created = $instance->insert('users', array(
        'id_number'     => $_POST['id_number'],
        'password'      => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'first_name'    => $_POST['first_name'],
        'last_name'     => $_POST['last_name'],
        'middle_name'   => $_POST['middle_name'],
        'contact_number'   => $_POST['contact_number'],
        'email'         => $_POST['email'],
        'address'       => $_POST['address'],
        'created_at'    => date('Y-m-d H:i:s'),
        'role_id'       => $role_id
    ));

    
    if($created) {
        echo json_encode(['error' => false, 'message' => "User Successfully Created"]);
    } else {
        echo json_encode(['error' => true, 'message' => "Error on Created"]);
    }
}
