<?php
$page = 'Bookbuy Admin Inventory';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$user_type = '';
$user = new User();

if (!$user->isLoggedIn()) {
   Redirect::to('index.php');
} else {
   if (!$user->hasPermission('admin') && !$user->hasPermission('staff') && !$user->hasPermission('dean')) {
      Redirect::to('dashboard.php');
   }
}
$q = $instance->query('SELECT * FROM notifications');
$results = $q->results();

?>
<style>
   body{
      background-color:#ffd998;
   }

</style>
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <h1>Notifications</h1>
      </div>
      <div class="col-md-12">
         <div class="table-responsive">
            <table class="table table-info table-bordered">
               <thead>
                  <tr class="info">
                     <th>From</th>
                     <th>Message</th>
                     <th>Status</th>
                  </tr>
               </thead>
               <tbody id="textbook-list">
                  <?php foreach($results as $result) { ?>
                     <tr>
                        <td><?php echo $result->id; ?>
                        <td><?php echo $result->message; ?>
                        <td><?php echo $result->unread == 1 ? 'UNREAD' : 'READ'; ?>
                     </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>