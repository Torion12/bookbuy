<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Pusher\Pusher;

require_once 'vendor/autoload.php';
require_once 'core/init.php'; 

$user = new User();
$instance = DB::getInstance();
$notifications = [];
if($user->hasPermission('student')) {
  $q = $instance->query('SELECT * FROM notifications WHERE user_id = ?', [$user->data()->id]);

  $notifications = $q->results();
} else if($user->hasPermission('staff')) {
  $q = $instance->query('SELECT * FROM notifications ORDER BY id DESC');

  $notifications = $q->results();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo isset($page) && !empty($page) ? $page : 'Home'; ?></title>

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/print.min.css">
   <link rel="stylesheet" href="assets/css/style.css">
   <link rel="stylesheet" href="/node_modules/toastr/build/toastr.min.css">
   
</head>
<body>
<style>
  #logo{
    width:34px;
    height: 34px;
    border-radius: 50%;
    margin-top: -10px;
  }

</style>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href=""><img id="logo" src="./assets/img/logo.jpg"></a>
      <a class="navbar-brand" href="#"> Bookbuy </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <?php if($user->isLoggedIn() && ($user->hasPermission('admin') || $user->hasPermission('dean') || $user->hasPermission('staff'))) { ?>
          <li <?php echo $path == '/dashboard.php' ? 'class="active"' : '' ?>><a href="./dashboard.php"> 
            <span class="sr-only">(current)</span>Dashboard</a></li>
        <?php } else { ?>
          <li <?php echo $path == '/' ? 'class="active"' : '' ?>><a href="/"> 
          <span class="sr-only">(current)</span>Home</a></li>
        <?php } ?>

        <?php if($user->isLoggedIn() && ($user->hasPermission('staff'))) { ?>
            <li><a href="./inventory.php">Inventory</a></li>
         <?php } ?>

         <?php if($user->isLoggedIn() && ($user->hasPermission('dean') || $user->hasPermission('staff'))) { ?>
            <li <?php echo $path == '/' ? 'class="active"' : '' ?>><a href="./request_orders.php">Requested Orders</a></li>
         <?php } ?>

        <?php if($user->isLoggedIn() && $user->hasPermission('staff')) { ?>
            <li><a href="./pending_orders.php"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Pending Orders <span class="badge" id="pending-orders-count">0</span></a></li>
         <?php } ?>
      
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php if($user->isLoggedIn() && ($user->hasPermission('student') || $user->hasPermission('staff') )) { ?>
      <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-bell" aria-hidden="true"></span> Notification (<b><?php echo count($notifications) ?? '0'; ?></b>)</a>
        <ul class="dropdown-menu notify-drop">
          <div class="notify-drop-title">
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-6"><span class="glyphicon glyphicon-bell" aria-hidden="true"></span> Notification (<b><?php echo count($notifications) ?? '0'; ?></b>)</div>
              <div class="col-md-6 col-sm-6 col-xs-6 text-right"><a href="" class="rIcon allRead" data-tooltip="tooltip" data-placement="bottom" title="tümü okundu."><i class="fa fa-dot-circle-o"></i></a></div>
            </div>
          </div>
          <!-- end notify title -->
          <!-- notify content -->
          <div class="drop-content">
            <?php foreach($notifications as $notification) { ?>
            <li class="notified" data-id="<?php echo $notification->id?>" style="cursor:pointer;<?php echo $notification->unread == 1 ? 'background:antiquewhite;' : ''?>">
              <div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="http://placehold.it/45x45" alt=""></div></div>
              <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">
                <p><?php echo $notification->message ?></p>
                
                <a href="" class="rIcon"><i class="fa fa-dot-circle-o"></i>
              </a>
              
              <hr>
              <p class="time"><?php echo $notification->created_at ?></p>
              </div>
            </li>
            <?php } ?>
          </div>
          <div class="notify-drop-footer text-center">
            <a href="../notifications.php"><i class="fa fa-eye"></i> Show All</a>
          </div>
        </ul>
      </li>
      <?php } ?>


         <?php if($user->isLoggedIn()) { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user->hasPermission('admin') ? 'Admin' : ($user->hasPermission('staff') ? 'Staff' : $user->data()->first_name) ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="./edit_accounts.php">Edit Account</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="./logout.php">Logout</a></li>
          </ul>
        </li>
        <?php } else { ?>
            <li><a href="./login.php">Login</a></li>
        <?php } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
