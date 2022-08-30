<?php

$page = 'Bookbuy Admin Inventory';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$user_type = '';
$user = new User();

if (!$user->isLoggedIn()) {
   Redirect::to('index.php');
} else {
   if (!$user->hasPermission('staff')) {
      Redirect::to('dashboard.php');
   }
}

$instance = DB::getInstance();

if(isset($_GET['search']) && !empty($_GET['search'])) {
   $search = strtolower($_GET['search']);
   $q = $instance->query("SELECT * FROM order_details od
      LEFT JOIN users u ON od.user_id = u.id
      WHERE status = 'pending'
      AND (LOWER(CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name)) LIKE '%{$search}%' OR LOWER(od.order_id) = ?)
   ", [strtolower($search)]);
} else {
   $q = $instance->query("SELECT *, t.subject_code as t_subject_code, u.first_name as u_first_name,
                           u.last_name as u_last_name,
                           od.created_at as od_created_at
                          FROM order_details od
                          LEFT JOIN users u ON od.user_id = u.id
                          LEFT JOIN textbooks t ON t.id = od.textbook_id
                          WHERE status = 'pending'");
}

$orders = [];
foreach($q->results() as $result) {
   if(!array_key_exists($result->order_id, $orders)) {
      $orders[$result->order_id] = [];
   }
   $orders[$result->order_id][] = $result;
}

?>
<style>
   body{
      background-color:#94d4e0 ;
   }

</style>
<div class="container">
   <div class="row">
      <div class="col-md-9">
         <h1>Pending Orders</h1>
      </div>
      <div class="col-md-3">
         <div class="form-group">
            <label for="search_keyword">Search</label>
            <input type="text" class="form-control" id="search_keyword" placeholder="Search Keywords" value="<?php echo isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : ''; ?>">
         </div>
      </div>
      <div class="col-md-12">
         <div class="table-responsive">
            <table class="table table-info table-bordered">
               <tr class="info">
                  <th>Subject Code</th>
                  <th>Student Name</td>
                  <th>Ordered Date</th>
                  <th colspan="2">Action</th>
               </tr>
               <?php foreach($orders as $key => $order) { ?>
               <tr>
                  <td><?php echo $order[0]->t_subject_code ?></td>
                  <td><?php echo $order[0]->u_first_name && $order[0]->u_last_name ? ($order[0]->u_first_name . " " . $order[0]->u_last_name) : $order[0]->full_name; ?></td>
                  <td><?php echo $order[0]->od_created_at ?></td>
                  <td><button class="btn btn-primary view-items" data-id="<?php echo $key; ?>">View Items</button></td>
                  <td><button class="btn btn-info mark-as-paid-btn" data-id="<?php echo $key; ?>">Mark as Paid</button></td>
               </tr>
               <?php } ?>
            </table>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="view-items-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Items View</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
            <thead>
                  <tr>
                     <th colspan="3">Ordered Items</th>
                  </tr>
                  <tr>
                     <th>Textbook Name</th>
                     <th>Quantity</th>
                     <th>Subtotal</th>
                  </tr>
            </thead>
            <tbody class="view-items-list">
            
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mark-paid-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Mark as Paid</h4>
      </div>
      <div class="modal-body">
         <input type="hidden" id="selected-order-id">
         <div class="form-group">
            <label for="official_recpt">OR. Number:</label>
            <input type="off_rcpt" id="official_recpt" class="form-control" placeholder="OR. Number">
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" id="payment-date" class="form-control" placeholder="Payment Date">
        </div>

        <div class="form-group">
            <label for="payment-type">Payment Type</label>
            <select name="payment-type" id="payment-type"class="form-control" >
                  <option value="cash">Cash</option>
                  <option value="cc">Credit/Debit Card</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="mark-as-paid-submit">Save</button>
      </div>
    </div>
  </div>
</div>

<?php include_once('./partials/footer.php') ?>

<script>
   $('#payment-date').val(moment(new Date()).format('YYYY-MM-DD'))

    $('#printPage').on('click', function() {
        var newWindow=window.open('./sales_report_print.php');
        newWindow.focus();
        newWindow.print();
    });

    $('.view-items').on('click', function() {
       let id = $(this).data('id')

      $.get('./api_orders.php?order_id=' + id).then(function(response) {
         response = JSON.parse(response)

         let html = ''
         let total = 0
         response.results.forEach(function(item) {
            html += `
               <tr>
                  <td>` + item.textbook_name + `</td>
                  <td>` + item.od_quantity + `</td>
                  <td>` + currencyFormat(item.total) + `</td>
               </tr>
            `

            total += item.total
         });
         
         html += `
               <tr>
                  <td colspan="2"><b>Grand Total</b></td>
                  <td>` + currencyFormat(total) + `</td>
               </tr>
            `

         $('.view-items-list').html(html)
         $('#view-items-modal').modal('show')
      });
    })

    $('.mark-as-paid-btn').on('click', function() {
       let id = $(this).data('id')
       $('#selected-order-id').val(id);

       $('#mark-paid-modal').modal('show')
    })

    $('#mark-as-paid-submit').on('click', function() {
       let data = {
          or_number: $('#official_recpt').val(),
          order_id: $('#selected-order-id').val(),
          payment_date: $('#payment-date').val(),
          payment_type: $('#payment-type').val()
       }
       $.ajax({
         url: './api_orders.php',
         type: 'POST',
         data: data,
         dateType: 'json'
      }).then(function(response) {
         $('#mark-paid-modal').modal('hide')
         // location.reload()
      });
    })

    $('#search_keyword').on('keydown', function(e) {
       if(e.keyCode == 13) {
          console.log($(this).val())
         window.location = '/pending_orders.php?search=' + $(this).val()
       }
    })
</script>