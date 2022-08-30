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

$sql = 'SELECT t.*,c.genre as course_type 
           FROM textbooks t 
           LEFT JOIN categories c
           ON t.category_id = c.id 
           WHERE t.textbook_status = "active"';

if(isset($_GET['department']) && !empty($_GET['department'])) {
   $sql .= ' AND LOWER(t.department) = "' . $_GET['department'] . '"';
}

$q = $instance->query($sql);
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
         <h1>Inventory</h1>
      </div>
      <div class="col-md-12">
         <div class="table-responsive">
            <table class="table table-info table-bordered">
            <thead>
                  <tr class="info">
                     <th>Department</th>
                     <th>Subject Code</th>
                     <th>Textbook Title</th>
                     <th>Qty In-Stock</th>
                     <th>Sold</th>
                     <th>Remaining Books</th>
                  </tr>
               </thead>
               <tbody id="textbook-list">
                  <?php foreach($results as $result) { ?>
                     <tr>
                        <td><?php echo $result->department; ?>
                        <td><?php echo $result->subject_code; ?>
                        <td><?php echo $result->textbook_name; ?>
                        <td><?php echo $result->quantity; ?>
                        <td><?php echo $result->sold ?? '0'; ?>
                        <td><?php echo $result->quantity - $result->sold; ?>
                     </tr>
                  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>