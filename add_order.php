<?php

$page = 'Bookbuy Admin Add Order';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$user_type = '';
$user = new User();

if (!$user->isLoggedIn()) {
   Redirect::to('index.php');
} else {
   if (!$user->hasPermission('admin') && !$user->hasPermission('staff') && !$user->hasPermission('student')) {
      Redirect::to('dashboard.php');
   }
}

?>
<style type="text/css">
   hr {
      border: 1px solid black;

   }

   .hr-2 {
      border: 1px solid black;
   }

   i {
      font-size: 20px;
   }

   .bgd-print {
      background: gray;
      width: 40%;
   }

   .container {
      background: #1abc9c;
   }
  body{ 
 
    background-image: url(unsplash.jpg);
    background-size: cover;
}
</style>
<div class="container">
   <div class="row">
      <div class="col-md-3">
         <h1 style="display:inline;">Order</h1>
      </div>
      <div class="col-md-3 <?php echo ($user->hasPermission('student')) ? 'col-md-offset-3' : '' ?> ">
         <div class="form-group">
            <?php if ($user->hasPermission('student')) { ?>
               <input type="text" id="id_number" style="margin-top:5px" class="form-control input-lg btn-block" value="<?php echo $user->data()->id_number;?>" placeholder="ID Number" disabled>
            <?php } else { ?>
               <input type="text" id="id_number" style="margin-top:5px" class="form-control input-lg btn-block" placeholder="ID Number">
            <?php } ?>
         </div>
      </div>
      <?php if (!$user->hasPermission('student')) { ?>
       <div class="col-md-3">
         <div class="form-group">
            <input type="text" id="full_name" style="margin-top:5px" class="form-control input-lg btn-block" placeholder="Name">
         </div>
      </div>
      <?php } else { ?>
         <input type="hidden" id="full_name" style="margin-top:5px" class="form-control input-lg btn-block" placeholder="Name" value="<?php echo $user->data()->first_name . ' ' . $user->data()->last_name; ?>">
      <?php } ?>
      <div class="col-md-3">
         <div class="form-group">
            <input type="text" id="edp_code" style="margin-top:5px" class="form-control input-lg btn-block" placeholder="EDP Code">
         </div>
      </div>
     
      <div class="col-md-8">
         <div class="table-responsive">
            <table class="table table-info table-bordered">
               <tr class="info">
                  <td colspan="5" class="text-center"><b>CART</b></td>
               </tr>
               <tr class="info t-header">
                  <th>Subject Code</th>
                  <th>Book Title</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th></th>
               </tr>
               <tr style="font-size:20px">
                  <td>----</td>
                  <td>----</td>
                  <td>----</td>
                  <td>----</td>
                  <td>----</td>
               </tr>
            </table>
            <button class="btn btn-default pull-right" id="checkout-order" style="margin:10px">Checkout ></button>
         </div>
      </div>
      <div class="col-md-4">
         <div class="panel panel-default">
            <div class="panel-heading">
               <div class="panel-title">Textbooks</div>
            </div>
            <div class="panel-body">
               <div class="row">
               <div class="col-md-12">
                     <div class="form-group">
                        <label for="search_department">Department</label>
                        <select name="search_department" id="search_department" class="form-control input-sm">
                           <option value="">All</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="filter_by">Filter</label>
                        <select name="filter_by" id="filter_by" class="form-control input-sm">
                           <option value="">All</option>
                           <option value="subject_code">Subject Code</option>
                           <option value="textbook_name">Textbook Name</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="search_keywords">Search</label>
                        <input type="text" class="form-control input-sm" id="search_keywords" placeholder="Search Keywords">
                     </div>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <table class="table table-bordered table-condensed">
                     <tr>
                        <td colspan="3" class="text-center">RESULTS</td>
                     </tr>
                     <tr>
                        <th>Subject Code</th>
                        <th>Textbook name</th>
                        <td></td>
                     </tr>
                     <tbody id="t-search-result">
                        <tr>
                           <td>---</td>
                           <td>---</td>
                           <td>---</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="add-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <div class="modal-title">
               <b>Add Order</b>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <input type="hidden" name="textbook_id" id="textbook_id">
                  <div class="form-group">
                     <h3 id="t-title">Fundamental Of Programming</h3>
                  </div>
                  <div class="form-group">
                     <b>Department: <br></b>
                     <i id="t-cat">Computer Studies</i>
                  </div>
                  <div class="form-group">
                     <b>Price <br></b>
                     <i id="t-price">100</i>
                  </div>
                  <div class="form-group">
                     <b>Publisher <br></b>
                     <i id="t-publisher">Sample</i>
                  </div>
                  <div class="form-group">
                     <b>Available Books <br></b>
                     <i id="t-quantity">Sample</i>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="Quantity">Quantity</label>
                     <input type="number" name="quantity" class="form-control" id="tb-quantity" placeholder="Quantity" value="1">
               </div>
                  <div class="form-group">
                     <label>Total:</label>
                     <i id="tb-total">0</i>
                  </div>
                  <div class="form-group">
                     <img src="./assets/img/book-sample.png" id="tb-image" style="width:100%;height:300px" alt="Sample book">
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="add-to-cart">Place to cart</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="edit-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <div class="modal-title">
               <b>Add Order</b>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <input type="hidden" name="order_id" id="order_id">
                  <div class="form-group">
                     <h3 id="edit-t-title">Fundamental Of Programming</h3>
                  </div>
                  <div class="form-group">
                     <b>Department: <br></b>
                     <i id="edit-t-cat">Computer Studies</i>
                  </div>
                  <div class="form-group">
                     <b>Price <br></b>
                     <i id="edit-t-price">100</i>
                  </div>
                  <div class="form-group">
                     <b>Publisher <br></b>
                     <i id="edit-t-publisher">Sample</i>
                  </div>
                  <div class="form-group">
                     <b>Quantity Available <br></b>
                     <i id="edit-t-quantity">Sample</i>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="Quantity">Quantity</label>
                     <input type="number" name="quantity" class="form-control btn-block" id="edit-tb-quantity" placeholder="Quantity" value="1">
                  </div>
                  <div class="form-group">
                     <label>Total:</label>
                     <i id="edit-t-total">100</i>
                  </div>
                  <div class="form-group">
                     <img src="./assets/img/book-sample.png" id="edit-tb-image" style="width:100%;height:300px" alt="Sample book">
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="update-to-cart">Update to cart</button>
         </div>
      </div>
   </div>
</div>

<!-- PRINT  -->
<div class="modal fade" id="thankyou" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-body text-center">
            <h1>Your Order has been Placed.<br /> Proceed to Cashier.</h1>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
         </div>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>
<script>
   function getParam(param) {
      return new URLSearchParams(window.location.search).get(param);
   }

   function fetchSavedOrders() {
      $('.t-orders').remove();
      let raw = window.localStorage.getItem('cart');

      if (raw != null) {
         let cart = JSON.parse(raw)
         let keys = Object.keys(cart)

         keys.forEach(function(key) {
            displaySavedOrders(cart[key])
         })
      }
   }

   function removeCartItem(order_id) {
      let raw = localStorage.getItem('cart');
      let cart = JSON.parse(raw);

      delete cart[order_id];

      let cart_count = Object.keys(cart).length

      if (cart_count <= 0) {
         localStorage.removeItem('cart');
      }
   }

   function fetchCategories() {
      $.get('./api_categories.php').then(function(response) {
         response = JSON.parse(response);

         $('#course_type').empty();
         $('#course_type').append(new Option('All', ''));
         response.results.forEach(function(row) {
            $('#course_type').append(new Option(row.genre, row.id))
         })
      });
   }

   function fetchDepartment() {
      $.get('./api_dept.php').then(function(response) {
         response = JSON.parse(response);

         $('#search_department').empty();
         $('#search_department').append(new Option('All', ''));
         response.results.forEach(function(row) {
            $('#search_department').append(new Option(row.department, row.department))
         })
      });
   }

   function displaySavedOrders(orders_obj) {
      $.get('./api_textbooks.php?id=' + orders_obj.id).then(function(response) {
         response = JSON.parse(response);

         $('.t-header').after(`
            <tr class="t-orders ` + orders_obj.order_id + `">
               <td>` + response.result.subject_code + `</td>
               <td>` + response.result.textbook_name + `</td>
               <td>` + orders_obj.quantity + `</td>
               <td>` + currencyFormat(orders_obj.quantity * response.result.textbook_price) + `</td>
               <td class="text-center">
                  <button class="btn btn-primary edit_` + orders_obj.order_id + `" data-id="` + orders_obj.order_id + `"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                  <button class="btn btn-danger remove_` + orders_obj.order_id + `"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
               </td>
            </tr>
         `)

         addRemoveOrderEvent(orders_obj.order_id);
         addEditOrderEvent(orders_obj.order_id);
      })
   }

   function addOrder(id) {
      $.get('./api_textbooks.php?id=' + id).then(function(response) {
         selectedOrder(response);
         response = JSON.parse(response);

         $('#textbook_id').val(response.result.id)
         $('h3#t-title').text(response.result.textbook_name)
         $('#t-price').text(currencyFormat(response.result.textbook_price))
         $('#t-cat').text(response.result.category.genre)
         $('#t-quantity').text(response.result.quantity - response.result.sold)
         $('#t-publisher').text(response.result.publisher ? response.result.publisher : '---')
         $('#tb-total').text(currencyFormat(response.result.textbook_price));

         if((response.result.quantity - response.result.sold) <= 0) {
            $('#add-to-cart').attr('disabled', true)
         }

         $('#tb-image').attr('src', response.result.textbook_img + '?q=' + Math.floor(Date.now() / 1000))
      })

      $('#add-order').modal('show');
   }

   // fetch all
   fetchCategories()
   fetchSavedOrders()
   fetchDepartment()

   if (getParam('add') && $.isNumeric(getParam('add'))) {
      addOrder(
         getParam('add')
      );
   }

   $('#add-to-cart').on('click', function() {
      if(!$('#tb-quantity').val()) {
         toastr.error('Error!', 'Quantity field is empty')
         return
      }

      if($('#tb-quantity').val() <=0) {
         toastr.error('Error!', 'Quantity minimum is 1')
         return
      }

      $.get('./api_textbooks.php?id=' + $('#textbook_id').val() + '&action=stocks&quantity=' + $('#tb-quantity').val()).then(function(response) {
         response = JSON.parse(response)
         if(response.error) {
            toastr.error('Error!', response.message)
         } else {
            $.get('./api_textbooks.php?id=' + $('#textbook_id').val()).then(function(response) {
               response = JSON.parse(response);
               let id = makeid(6);
               $('.t-header').after(`
                  <tr class="t-orders order_` + id + `">
                     <td>` + response.result.subject_code + `</td>
                     <td>` + response.result.textbook_name + `</td>
                     <td>` + $('#tb-quantity').val() + `</td>
                     <td>` + currencyFormat(($('#tb-quantity').val() * response.result.textbook_price)) + `</td>
                     <td class="text-center">
                        <button class="btn btn-primary edit_order_` + id + `" data-id="order_` + id + `"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                        <button class="btn btn-danger remove_order_` + id + `"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                     </td>
                  </tr>
               `)

               let cart = window.localStorage.getItem('cart');

               let order = {
                  'id': response.result.id,
                  'order_id': 'order_' + id,
                  'quantity': $('#tb-quantity').val(),
                  'price': response.result.textbook_price,
                  'total': $('#tb-quantity').val() * response.result.textbook_price,
               }


               if (cart == null) {
                  cart = {}
               } else {
                  cart = JSON.parse(cart);
               }

               cart['order_' + id] = order
               let raw = JSON.stringify(cart);
               window.localStorage.setItem('cart', raw);

               addRemoveOrderEvent('order_' + id);
               addEditOrderEvent('order_' + id);
               unselectOrder();
               $('#tb-quantity').empty()
               $('#add-order').modal('hide');
            })
         }
      })
   });

   function addToCart() {

   }

   function makeid(length) {
      var result = [];
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var charactersLength = characters.length;
      for (var i = 0; i < length; i++) {
         result.push(characters.charAt(Math.floor(Math.random() * charactersLength)));
      }
      return result.join('');
   }

   function addRemoveOrderEvent(id) {
      $('.remove_' + id).on('click', function() {
         removeCartItem(id);
         $('.' + id).remove();
      });
   }

   function addEditOrderEvent(id) {
      $('.edit_' + id).on('click', function() {
         let cart = window.localStorage.getItem('cart');
         cart = JSON.parse(cart)

         let order = cart[id]

         $('#order_id').val(order.order_id)
         $('#edit-tb-quantity').val(order.quantity)
         $.get('./api_textbooks.php?id=' + order.id).then(function(response) {
            selectedOrder(response);
            response = JSON.parse(response);

            $('h3#t-title').text(response.result.textbook_name)
            $('#edit-t-price').text(currencyFormat(response.result.textbook_price))
            $('#edit-t-cat').text(response.result.category.genre)
            $('#edit-t-publisher').text(response.result.publisher ? response.result.publisher : '---')
            $('#edit-t-total').text(currencyFormat(order.quantity * response.result.textbook_price));

            $('#tb-image').attr('src', response.result.textbook_img + '?q=' + Math.floor(Date.now() / 1000))
         })

         $('#edit-order').modal('show');
      });
   }

   function selectedOrder(data) {
      window.localStorage.setItem('order', data);
   }

   function unselectOrder() {
      localStorage.removeItem('order');
   }

   function checkIDNumber(id_number) {
      return $.get('./api_users.php?id_number=' + id_number)
   }

   function getOrder() {
      let order = localStorage.getItem('order');

      if (order) {
         order = JSON.parse(order);

         return order
      }
      return false;
   }

   $('#update-to-cart').on('click', function() {
      let order_id = $('#order_id').val()
      let quantity = $('#edit-tb-quantity').val()

      let cart = window.localStorage.getItem('cart');
      cart = JSON.parse(cart)

      cart[order_id].quantity = quantity
      cart[order_id].total = cart[order_id].price * quantity

      let raw = JSON.stringify(cart);
      window.localStorage.setItem('cart', raw);

      fetchSavedOrders()
      $('#edit-order').modal('hide')
   });

   $('#tb-quantity').on('change', function() {
      let order = getOrder();

      let total = order.result.textbook_price * $(this).val()
      $('#tb-total').text(currencyFormat(total > 0 ? total : 0));
   })

   $('#edit-tb-quantity').on('change', function() {
      let order = getOrder();

      let total = order.result.textbook_price * $(this).val()
      $('#edit-t-total').text(currencyFormat(total > 0 ? total : 0));
   })

   function searchTextbooks(keywords, filter_by, dept) {
      return $.get('./api_textbooks.php?search=' + keywords + '&filter_by=' + filter_by + '&department=' + dept);
   }

   $('#search_keywords').on('keydown', function(e) {
      if (e.keyCode == 13) {
         let filter_by = $('#filter_by').val()
         let dept = $('#search_department').val()

         searchTextbooks($(this).val(), filter_by, dept).then(function(response) {
            response = JSON.parse(response);

            let html = '';
            response.results.forEach(function(row) {
               let id = makeid(6);
               html += `<tr>
                           <td>` + row.subject_code + `</td>
                           <td>` + row.textbook_name + `</td>
                           <td><button class="btn btn-success btn-xs btn-block add_order_item" data-id="` + row.id + `">&plus; ADD</button></td>
                        </tr>`
            })

            if (response.results.length > 0) {
               $('#t-search-result').html(html);
            } else {
               $('#t-search-result').html(`<tr><td colspan="2" class="text-center"><b>NO RESULT</b></td></tr>`);
            }
         })
      }
   });

   $('#checkout-order').on('click', function() {
      checkoutOrder()
   })

   function checkoutOrder() {
      let cart = window.localStorage.getItem('cart');
      cart = JSON.parse(cart)

      if (cart == null) {
         toastr.error("Error", "Cart is empty!")
      } else {
         if(!$('#edp_code').val()) {
            toastr.error("Error", "EDP Code required")
            return;
         }

         if(!$('#full_name').val()) {
            toastr.error("Error", "Full Name required")
            return;
         }

         if(!$('#id_number').val()) {
            toastr.error("Error", "ID Number required")
            return;
         }

         if (confirm("Are you sure to checkout?")) {
            let orders = [];
            let keys = Object.keys(cart);

            keys.forEach(function(key) {
               orders.push(cart[key])
            })

            $.post('./api_orders.php', {
               'orders': orders,
               'edp_code': $('#edp_code').val(),
               'full_name': $('#full_name').val(),
               'id_number': $('#id_number').val()
            }).then(function(response) {
               console.log(response);
            })

            $('.t-orders').remove();
            window.localStorage.removeItem('cart');
            toastr.success("Success", "Your order has been successfully placed!")
            $('#thankyou').modal('show')

         }


      }
   }

   $('#t-search-result').on('click', '.add_order_item', function() {
      addOrder($(this).data('id'))
   })
</script>

</body>

</html>