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

$sql = "SELECT *,t.department as dept,
         od.created_at as od_created_at,
         od.id_number as od_id_number,
         od.quantity as od_quantity
        FROM order_details od 
        LEFT JOIN users u ON od.user_id = u.id 
        LEFT JOIN textbooks t ON od.textbook_id = t.id 
        WHERE status = 'paid'";

$where = '';
if(isset($_GET['date_from']) && !empty($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_to'])) {
   $where .= " AND DATE(od.created_at) BETWEEN '" . $_GET['date_from'] . "' AND '" . $_GET['date_to'] . "'";
}

if(isset($_GET['subject_code']) && !empty($_GET['subject_code'])) {
   $where .= " AND t.id = " . $_GET['subject_code'];
}

// var_dump($sql . $where);
// die();
$q = $instance->query($sql . $where);

$total = 0;
$orders = [];
foreach($q->results() as $result) {
   if(!array_key_exists($result->order_id, $orders)) {
      $orders[$result->order_id] = [];
   }
   $orders[$result->order_id][] = $result;
   $total += $result->total;
}

?>
<style>
   body{
      background-color:#94d4e0 ;
   }

</style>
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <h1>Sales Report</h1>
      </div>
      <div class="col-md-12">
         <div class="table-responsive">
            <table class="table table-info table-bordered">
               <thead>
                  <tr class="info">
                     <th>Date</th>
                     <th>Name</th>
                     <th>ID Number</th>
                     <th>Subject Code</th>
                     <th>EDP Code</th>
                     <th>Title Of Textbook</th>
                     <th>Publisher</th>
                     <th>Price</th>
                     <th>Quantity</th>
                     <th>Total</td>
                  </tr>
               </thead>
               <tbody id="sales-report-table">
                  <?php foreach($orders as $key => $order) { ?>
                     <tr>
                        <td><?php echo $order[0]->od_created_at ?></td>
                        <td><?php echo $order[0]->first_name . ' ' . $order[0]->last_name ?></td>
                        <td><?php echo $order[0]->id_number ? $order[0]->id_number : $order[0]->od_id_number ?></td>
                        <td><?php echo $order[0]->subject_code ?></td>
                        <td><?php echo $order[0]->edp_code ?></td>
                        <td><?php echo $order[0]->textbook_name ?></td>
                        <td><?php echo $order[0]->publisher ?></td>
                        <td><?php printf("P%01.2f", $order[0]->price); ?></td>
                        <td><?php echo $order[0]->od_quantity ?></td>
                        <td><?php printf("P%01.2f", $order[0]->total); ?></td>
                     </tr>
                  <?php } ?>
               </tbody>
               <tr class="active" style="font-weight:bold">
                  <td colspan="9">Grand Total</td>
                  <td id="grand-total"><?php printf("P%01.2f", $total); ?></td>
               </tr>
            </table>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="form-group">
            <button class="btn btn-default pull-right" id="printPage"><i class="glyphicon glyphicon-print"></i></button>
         </div>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>

<script>
   // getOrders(getParam('date'), getParam('search'), getParam('dept'))

   function getParam(param) {
      return new URLSearchParams(window.location.search).get(param);
   }

   function getOrders(date = '', search = '', dept = '') {
      $.get('./api_orders.php?status=paid&date=' + date + '&search=' + search + '&dept=' + dept).then(function(response) {
         response = JSON.parse(response)
         let keys = Object.keys(response.results)
         let total = 0;
         $('#sales-report-table').empty();
         keys.forEach(function(row) {
            $('#sales-report-table').append(`
               <tr>
                  <td>` + response.results[row][0].order_id + `</td>
                  <td>` + response.results[row][0].dept + `</td>
                  <td>` + currencyFormat(0) + `</td>
                  <td>1</td>
                  <td>` + response.results[row][0].od_created_at + `</td>
                  <td>` + (response.results[row][0].id_number ? response.results[row][0].id_number : response.results[row][0].od_id_number) + `</td>
                  <td>` + currencyFormat(response.results[row][0].total) + `</td>
               </tr>
            `);

            total += parseFloat(response.results[row][0].total)
         })

         $('#grand-total').text(currencyFormat(total))
      })
   }

   $('#select-date').on('change', function() {
      getOrders($('#select-date').val(), $('#search-keyword').val(), $('#sales_type').val())
   })

   $('#search-keyword').on('change', function() {
      getOrders($('#select-date').val(), $('#search-keyword').val(), $('#sales_type').val())
   })

   $('#sales_type').on('change', function() {
      getOrders($('#select-date').val(), $('#search-keyword').val(), $('#sales_type').val())
   })
</script>