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

?>
<style>
   body{
      background-color:#ffd998;
   }

</style>
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <h1>Inventory Report</h1>
      </div>
      <?php if (!$user->hasPermission('dean')) { ?>
         <!-- <div class="col-md-12" style="margin: 10px">
            <a href="./add_product.php" class="btn btn-info btn-lg pull-right">&plus; New Product</a>
         </div> -->
      <?php } ?>
      <div class="col-md-12">
         <div class="row">
            <div class="col-md-3">
               <div class="form-group">
                  <label for="course_type">Course Type</label>
                  <select name="course_type" id="course_type" class="form-control">
                     <option value="CSS">CSS</option> 
                     <option value="Nursing">Nursing</option>
                  </select>
               </div>
            </div>
            <div class="col-md-7 col-md-offset-2">
               <button class="btn btn-info pull-right" aria-hidden="true" id="printPage" style="margin-top:25px;"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
            </div>
            <!-- <div class="col-md-3">
               <div class="form-group">
                  <label for="filter_type">Filter</label>
                  <select name="filter_type" id="filter_type" class="form-control">
                     <option value="all">All</option>
                     <option value="Department">Department</option>
                     <option value="Book Title">Book Title</option>
                  </select>
               </div>
            </div> -->
            <!-- <div class="col-md-3">
               <div class="form-group">
                  <label for="search_keyword">Filter</label>
                  <input type="text" class="form-control" id="search_keyword" placeholder="Search">
               </div>
            </div> -->
         </div>
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

               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>



<?php include_once('./partials/footer.php') ?>

<script>
   $('#printPage').on('click', function() {
      var newWindow = window.open('./inventory_print.php?department=' + $('#course_type').val());
      newWindow.focus();
      newWindow.print();
   });

   getTextbooks()
   getCategories('#course_type')

   function getTextbook(id) {
      return $.get('./api_textbooks.php?id=' + id)
   }

   function getCategories(elem) {
      $.get('./api_dept.php').then(function(response) {
      response = JSON.parse(response)

      $(elem).empty()
      if(elem == '#course_type') {
         $(elem).html('<option value="">All</option>')
      }
      response.results.forEach(function(cat) {
         $(elem).append(`
            <option value="`+cat.department+`">`+cat.department+`</option>
         `)
      });
   })
   }

   function getTextbooks(params) {

      let url = './api_textbooks.php';
      if(params) {
         url += params;
      }

      $.get(url).then(function(response) {
         response = JSON.parse(response)

         $('#textbook-list').empty()
         response.results.forEach(function(text) {
            $('#textbook-list').append(`
               <tr style="font-size:20px">
                  <td class="text-center">` + text.department + `</td>
                  <td>` + text.subject_code + `</td>
                  <td>` + text.textbook_name + `</td>
                  <td><span class="label label-info">` + (text.quantity ? text.quantity : '0') + `</span></td>
                  <td><span class="label label-success">` + (text.sold ? text.sold : '0') + `</span></td>
                  <td><span class="label label-info">` + ((text.quantity - text.sold) ? text.quantity - text.sold : '0') + `</span></td>
                 
               </tr>
             `)
         })
      })
   }

   $('body').on('click', '.edit-textbook-btn', function() {
      $('#edit-textbook').modal('show')

      $('#textbook_id').val($(this).data('id'))
      getTextbook($(this).data('id')).then(function(response) {
         response = JSON.parse(response)

         textbook = response.result

         if(textbook) {
            $('input[name="department"]').val(textbook.department)
            $('input[name="title"]').val(textbook.textbook_name)
            $('input[name="subject"]').val(textbook.subject_code)
            $('input[name="price"]').val(textbook.textbook_price)
            $('input[name="category"]').val(textbook.category_id)
            $('input[name="store_available"]').val(textbook.store_available)
            $('input[name="publisher"]').val(textbook.publisher)
            $('input[name="author"]').val(textbook.author)
            $('input[name="edition"]').val(textbook.edition)
            $('input[name="quantity"]').val(textbook.quantity)
         }
      })
   })

   $('.update-textbook-btn').on('click', function() {
      let id = $('#textbook_id').val()

      getTextbook(id).then(function(response) {
         response = JSON.parse(response)

         textbook = response.result

         if(confirm('Are you sure to update textbook: ' + textbook.textbook_name + '?')) {
            let data = {
               department: $('input[name="department"]').val(),
               textbook_name: $('input[name="title"]').val(),
               subject_code: $('input[name="subject"]').val(),
               textbook_price: $('input[name="price"]').val(),
               category: $('select[name="category"]').val(),
               store_available: $('input[name="store_available"]').val(),
               publisher: $('input[name="publisher"]').val(),
               author: $('input[name="author"]').val(),
               edition: $('input[name="edition"]').val(),
               quantity: $('input[name="quantity"]').val(),
            }   
         
            $.post('./api_textbooks.php?id=' + id, data).then(function(response) {
               response = JSON.parse(response)
            
               if(response.error) {
                  toastr.error("Error", response.message)
               } else {
                  toastr.success("Updated!", response.message)
                  getTextbooks()
                  $('#edit-textbook').modal('hide')
               }
            });
         }
      })
   })

   $('body').on('click', '.delete-textbook-btn', function() {
      let id = $(this).data('id')

      getTextbook(id).then(function(response) {
         response = JSON.parse(response)

         textbook = response.result

         if(confirm('Are you sure to delete textbook: ' + textbook.textbook_name + '?')) {
            $.get('./api_textbooks.php?id=' + id + '&action=delete').then(function(response) {
               response = JSON.parse(response)
            
               if(response.error) {
                  toastr.error("Error", response.message)
               } else {
                  toastr.success("Deleted!", response.message)
                  getTextbooks()
               }
            });
         }
      });
   })

   $('#course_type').on('change', function() {
      let params = '?department=' + $(this).val()
      getTextbooks(params)
   })
</script>