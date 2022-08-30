<?php

$page = 'Bookbuy Dashboard';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$user_type = '';
$user = new User();

if(!$user->isLoggedIn()) {
   Redirect::to('index.php');
}


?>
<style>
body {
    background-image: url("assets/img/unsplash.jpg");
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}
</style>

<div class="container-fluid">
    <div class="row">
        <?php if($user->hasPermission('admin') || $user->hasPermission('staff')) { ?>
        <div class="col-md-2">
           
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="./dashboard.php"><span
                            class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
                <!-- <li role="presentation"><a href="staff_list.php">Staffs</a></li>
                <li role="presentation"><a href="student_list.php">Students</a></li>
                <li role="presentation"><a href="dean_list.php">Deans</a></li> -->
                <li role="presentation"><a href="category_list.php"><span
                            class="glyphicon glyphicon-copyright-mark"></span> Categories</a></li>
                <div class="btn-group ">
                    <li role="presentation" class="btn btn-default dropdown-toggle " data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog "></span> Manage User<span
                            class="caret presentation"></span>
        </li>
                    <ul class="dropdown-menu" role="presentation">
                        <li role="presentation" class="active"><a href="staff_list.php">Staffs</a></li>
                        <li role="presentation"><a href="student_list.php">Students</a></li>
                        <li role="presentation"><a href="dean_list.php">Deans</a></li>
                    </ul>
                </div>
            </ul>

        </div>
        <div class="col-md-10">
            <?php 
         if($user->hasPermission('admin')) {
            include_once('./admin_dashboard.php');
         } else if($user->hasPermission('staff')) {
            include_once('./staff_dashboard.php');
         }
      ?>
        </div>
        <?php } else if($user->hasPermission('dean')) {
         include_once('./dean_dashboard.php');
       } ?>
    </div>
</div>

<?php include_once('./partials/footer.php') ?>