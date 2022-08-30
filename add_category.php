<?php

$page = 'Bookbuy Add Product';
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

$_errors = "";
if(isset($_POST['submit'])) {
   $validate = new Validate();

   $validation = $validate->check($_POST, array(
      'genre' => array(
            'required' => true,
            'min' => 2,
            'max' => 150
      ),
      'description' => array(
            'required' => true,
            'min' => 2,
            'max' => 150
      ),
   ));

   if($validation->passed()) {
      $instance = DB::getInstance();

      $result = $instance->insert('categories', [
         'genre' => Input::get('genre'),
         'description' => Input::get('description'),
      ]);

      if(!$result) {
         $_errors = '<div class="alert alert-warning">Error inserting data</div>';
      } else {
         $_errors = '<div class="alert alert-success">Successfully Created</div>';
      }
   } else {
      foreach($validation->errors() as $error) {
            $_errors .= '<div class="alert alert-warning">' . ucfirst($error) . '</div>';
      }
   }


   $uploaddir = 'uploads/';
}

?>

<div class="container">
   <div class="row">
      <div class="col-md-6 col-md-offset-3">
         <ol class="breadcrumb">
            <li><a href="./dashboard.php">Dashboard</a></li>
            <li class="active">Add New Category</li>
         </ol>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6 col-md-offset-3">
         <h1>Add New Category</h1>
         <hr>
      </div>
      <div class="col-md-6 col-md-offset-3">
         <?php echo $_errors ?>
         <form method="POST" enctype="multipart/form-data" action="./add_category.php">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="genre">Genre</label>
                     <input type="text" class="form-control" name="genre" placeholder="Genre" required>
                  </div>
                  <div class="form-group">
                     <label for="description">Description</label>
                     <textarea class="form-control" name="description" placeholder="Description" required id="" cols="30" rows="10"></textarea>
                  </div>
               </div>
            </div>
               <button class="btn btn-info" name="submit">Submit</button>
         </form>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>