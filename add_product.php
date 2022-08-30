

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
      'subject' => array(
            'required' => true,
            'min' => 2,
            'max' => 150
      ),
      'department' => array(
         'required' => true,
         'min' => 2,
         'max' => 150
      ),
      'title' => array(
            'required' => true,
            'min' => 2,
            'max' => 150
      ),
      'author' => array(
            'required' => true,
            'min' => 2,
            'max' => 150
      ),
      'edition' => array(
            'required' => true,
            'min' => 1,
            'max' => 150
      ),
      'price' => array(
            'required' => true,
            'min' => 0,
            'max' => 99999
      ),
      'store_available' => array(
            'required' => true,
            'min' => 2,
            'max' => 150
      ),
      'publisher' => array(
            'required' => true,
            'min' => 2,
            'max' => 150
      ),
   ));

   if($validation->passed()) {
      $uploaddir = 'uploads/';
      $name = bin2hex(random_bytes(20));
      $imageExt = strtolower(pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION));
      $target_file = $uploaddir . $name . '.' . $imageExt;

      if($imageExt != "jpg" && $imageExt != "png" && $imageExt != "jpeg"
         && $imageExt != "gif" ) {
            $_errors = '<div class="alert alert-warning">Invalid file! Please select JPEG, JPG or PNG</div>';
      } else {

         if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $q = $instance->insert('textbooks', [
               'department' => Input::get('department'),
               'subject_code' => Input::get('subject'),
               'textbook_name' => Input::get('title'),
               'author' => Input::get('author') ?? '',
               'edition' => Input::get('edition') ?? '',
               'textbook_price' => Input::get('price') ?? '',
               'store_available' => Input::get('store_available') ?? '',
               'publisher' => Input::get('publisher') ?? '',
               'category_id' => Input::get('category') ?? '',
               'quantity' => Input::get('quantity') ?? '',
               'remaining' => Input::get('quantity') ?? '',
               'textbook_img' => $target_file
            ]);
         } else {
            $_errors = '<div class="alert alert-warning">Something went wrong when uploading file!</div>';
         }
      }
   } else {
      foreach($validation->errors() as $error) {
            $_errors .= '<div class="alert alert-warning">' . ucfirst($error) . '</div>';
      }
   }


   
}

?>
<style>
   body{
      background-color:#94d4e0 ;
   }

</style>
<div class="container">
   <div class="row">
      <div class="col-md-6 col-md-offset-3">
         <ol class="breadcrumb">
            <li><a href="./dashboard.php">Dashboard</a></li>
            <li><a href="./inventory.php">Inventory</a></li>
            <li class="active">Add New Product</li>
         </ol>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6 col-md-offset-3">
         <h1>Add New Product</h1>
         <hr>
      </div>
      <div class="col-md-6 col-md-offset-3">
         <?php echo $_errors ?>
         <form method="POST" enctype="multipart/form-data" action="./add_product.php">
            <div class="row">
               <div class="col-md-6">
               <div class="form-group">
                     <label for="title">Department</label>
                     <input type="text" class="form-control" name="department" placeholder="Department" required>
                  </div>
                  <div class="form-group">
                     <label for="title">Subject Code/Subject</label>
                     <input type="text" class="form-control" name="subject" placeholder="Subject Code/Subject" required>
                  </div>
                  <div class="form-group">
                     <label for="title">Title of Textbook</label>
                     <input type="text" class="form-control" name="title" placeholder="Title of Textbook" required>
                  </div>
                  <div class="form-group">
                     <label for="price">Price</label>
                     <input type="number" class="form-control" name="price" placeholder="Price" required>
                  </div>
                  <div class="form-group">
                     <label for="price">Quantity</label>
                     <input type="number" class="form-control" name="quantity" placeholder="Quantity" required>
                  </div>
                  <div class="form-group">
                     <label for="edition">Category</label>
                     <select name="category" id="category" class="form-control">

                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="store_available">Book Store Available</label>
                     <input type="text" class="form-control" name="store_available" placeholder="Book Store Available" required>
                  </div>
                  <div class="form-group">
                     <label for="publisher">Publisher</label>
                     <input type="text" class="form-control" name="publisher" placeholder="Publisher" required>
                  </div>
                  <div class="form-group">
                     <label for="author">Author</label>
                     <input type="text" class="form-control" name="author" placeholder="Author" required>
                  </div>
                  <div class="form-group">
                     <label for="edition">Edition</label>
                     <input type="text" class="form-control" name="edition" placeholder="Edition" required>
                  </div>
                  <!-- <div class="form-group">
                     <label for="no_copies_needed">No. of copies needed</label>
                     <input type="number" class="form-control" name="no_copies_needed" placeholder="No. of copies needed" required>
                  </div> -->
                  <!-- <div class="form-group">
                     <label for="prepared_name">Prepared Name</label>
                     <input type="number" class="form-control" name="prepared_name" placeholder="Prepared Name" required>
                  </div> -->
                  <div class="form-group">
                     <label for="image"></label>
                     <input type="file" class="form-control" name="image" placeholder="image">
                  </div>
               </div>
            </div>
               <button class="btn btn-info" name="submit">Create</button>
         </form>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>

<script>
   $.get('./api_categories.php').then(function(response) {
      response = JSON.parse(response)

      $('#category').empty()
      response.results.forEach(function(cat) {
         $('#category').append(`
            <option value="`+cat.id+`">`+cat.genre+`</option>
         `)
      });
   })
</script>