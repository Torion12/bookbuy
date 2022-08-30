<?php

$page = 'Bookbuy Admin Inventory';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$user_type = '';
$user = new User();

if(!$user->isLoggedIn()) {
   Redirect::to('index.php');
} else {
   if(!$user->hasPermission('admin') && !$user->hasPermission('staff')) {
      Redirect::to('dashboard.php');
   }
}

?>

<div class="container-fluid">
   <div class="row">
      <div class="col-md-2">
         <ul class="nav nav-pills nav-stacked">
             <li role="presentation" ><a href="./dashboard.php">Dashboard</a></li>
            <!-- <li role="presentation" class="active"><a href="staff_list.php">Staffs</a></li>
            <li role="presentation" ><a href="student_list.php">Students</a></li>
            <li role="presentation" ><a href="dean_list.php">Deans</a></li> -->
            <li role="presentation"><a href="category_list.php">Categories</a></li>
            <div class="btn-group presentation" >
                    <button type="button" class="btn btn-default dropdown-toggle presentation" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog presentation"></span> Manage User  <span class="caret presentation"></span>
                    </button>
                    <ul class="dropdown-menu presentation" >
                        <li role="presentation" class="active"><a href="staff_list.php">Staffs</a></li>
                        <li role="presentation"><a href="student_list.php">Students</a></li>
                        <li role="presentation"><a href="dean_list.php">Deans</a></li>
                    </ul>
                </div>
         </ul>
      </div>
      <div class="col-md-10">
         <div class="row">
            <div class="col-md-12">
               <h1>List of Staff</h1>
            </div>
            <div class="col-md-12">
               <div class="row">
                  <div class="col-md-1">
                     <div class="form-group">
                        <label for="show">Show</label>
                        <select name="show" id="show" class="form-control">
                           <option value="10">10</option>
                           <option value="50">50</option>
                           <option value="100">100</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 col-md-offset-9">
                  <button class="btn btn-info btn-lg pull-right btn-block open-add-user-modal" data-user-type="staff">&plus; Staff</button>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table table-info table-bordered ">
                     <thead>
                        <tr class="info">
                           <th>Id Number</th>
                           <th>Staff Name</th>
                           <th>Address</th>
                           <th>Contact Number</th>
                           <th>Status</th>
                           <th>Action</td>
                        </tr>
                     </thead>
                     <tbody id="staff-list">

                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>

<script>
   getUsers('staff', '#staff-list');

   $('body').on('click', '.edit-user', function() {
      openEditUserModal('staff', $(this).data('id'));
   })

   $('#edit-user').on('click', '.edit-user-save', function() {
      updateUser('staff', $('#edit-user-id').val());
      getUsers('staff', '#staff-list');
   })

   $('body').on('click', '.delete-user', function() {
      deleteUser('staff', $(this).data('id'));
      getUsers('staff', '#staff-list');
   })
</script>