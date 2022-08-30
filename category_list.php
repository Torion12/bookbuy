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
                <!-- <li role="presentation"><a href="staff_list.php">Staffs</a></li>
                <li role="presentation"><a href="student_list.php">Students</a></li>
                <li role="presentation"><a href="dean_list.php">Deans</a></li> -->
            <li role="presentation"class="active"><a href="category_list.php">Categories</a></li>
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
               <h1>Categories</h1>
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
                     <button" class="btn btn-info btn-lg pull-right btn-block" id="add-cat-modal">&plus; Category</button>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table table-info table-bordered">
                     <thead>
                        <tr class="info">
                           <th>Name</th>
                           <th>Description</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody id="cat-list">

                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="edit-cat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Category</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-user-type">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="edit_cat_name">Category Name</label>
              <input type="text" name="edit_cat_name" id="edit_cat_name" class="form-control"/>
              <input type="hidden" name="edit_cat_id" id="edit_cat_id" class="form-control"/>
            </div>

            <div class="form-group">
              <label for="edit_cat_desc">Category Description</label>
              <textarea type="text" name="edit_cat_desc" id="edit_cat_desc" class="form-control"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary edit-cat-save">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add-cat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Category</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="add-user-type">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="add_first_name">Category Name</label>
              <input type="text" name="add_cat_name" id="add_cat_name" class="form-control"/>
            </div>

            <div class="form-group">
              <label for="add_cat_desc">Category Description</label>
              <textarea type="text" name="add_cat_desc" id="add_cat_desc" class="form-control"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary add-cat-save">Save</button>
      </div>
    </div>
  </div>
</div>

<?php include_once('./partials/footer.php') ?>

<script>
   getCategories();

   function getCategories() {
    $.get('./api_categories.php').then(function(response) {
      response = JSON.parse(response)

      console.log(response)
      if (response.results.length <= 0) {
        $('#cat-list').html(`
          <tr>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
          </tr>
      `)
      } else {
        $('#cat-list').empty();
      }

      response.results.forEach(function(cat) {
        $('#cat-list').append(`
            <tr>
              <td>` + cat.genre + `</td>
              <td>` + cat.description + `</td>
              <td class="text-center">
                <button class="btn btn-info edit-cat" data-id="` + cat.id + `" data-name="` + cat.genre + `" data-desc="` + cat.description + `">Edit</button>
                <button class="btn btn-danger delete-cat" data-id="` + cat.id + `">&times; Delete</button>
              </td>
            </tr>
        `);
      })

      if (response.results.length > 0) {
        $('#cat-list').append(`
          <tr>
            <td class="info">Name</td>
            <td class="info">Description</td>
            <td class="info">Action</td>
           
          </tr>
      `)
      }
    });
  }

  $('body').on('click', '.edit-cat', function() {
   let id = $(this).data('id')
   let name = $(this).data('name')
   let desc = $(this).data('desc')

   $('#edit_cat_name').val(name)
   $('#edit_cat_desc').val(desc)
   $('#edit_cat_id').val(id)
   $('#edit-cat').modal('show')
  });

  $('.edit-cat-save').on('click', function() {
   let id = $('#edit_cat_id').val();

   if(!$('#edit_cat_name').val()) {
      toastr.error('Error', 'Please input category name')
      return
   }

   let cat = {
      name: $('#edit_cat_name').val(),
      desc: $('#edit_cat_desc').val()
   }

   $.post('./api_categories.php?id=' + id, cat).then(function(response) {
      response = JSON.parse(response)
        
      if(response.error) {
         toastr.error("Error", response.message)
      } else {
         toastr.success("Updated!", response.message)
         getCategories()
      }
   });
  })

  $('#add-cat-modal').on('click', function() {
   $('#add-cat').modal('show')
  });

  $('.add-cat-save').on('click', function() {
   let id = $('#add_cat_id').val();

   if(!$('#add_cat_name').val()) {
      toastr.error('Error', 'Please input category name')
      return
   }
   let cat = {
      name: $('#add_cat_name').val(),
      desc: $('#add_cat_desc').val()
   }

   $.post('./api_categories.php', cat).then(function(response) {
      response = JSON.parse(response)
        
      if(response.error) {
         toastr.error("Error", response.message)
      } else {
         toastr.success("Created!", response.message)
         getCategories()
      }
   });
  })

  $('body').on('click', '.delete-cat', function() {
     if(confirm('Are you sure to delete category?')) {
        $.get('./api_categories.php?id=' + $(this).data('id') + '&action=delete').then(function(response) {
         response = JSON.parse(response)
        
         if(response.error) {
            toastr.error("Error", response.message)
         } else {
            toastr.success("Deleted!", response.message)
            getCategories()
         }
        });
     }
  })
</script>