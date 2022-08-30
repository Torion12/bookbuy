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

$instance = DB::getInstance();

$query = 'SELECT order_id, payment_date, payment_type, or_number FROM payment_history';

if(isset($_GET['dates']) && !empty($_GET['dates'])) {
   $dates = explode(',', $_GET['dates']);

   $query .= ' WHERE DATE(payment_date) BETWEEN "' . $dates[0] . '" AND "' . $dates[1] . '"';
}

$q = $instance->query($query);
$ph_results = $q->results();

$order_ids = "";
foreach($ph_results as $x => $result) {
   $order_ids .= "'" . $result->order_id . "'";

   if(count($ph_results) > ($x + 1)) {
      $order_ids .= ",";
   }
}

$query = 'SELECT *,
         CONCAT(u.first_name, " ", u.last_name) as u_full_name,
         od.full_name as od_full_name,
         od.quantity as od_quantity,
         od.id_number as od_id_number
         FROM order_details od
         LEFT JOIN users u ON od.user_id = u.id
         LEFT JOIN textbooks t ON od.textbook_id = t.id
         WHERE od.order_id IN(' . $order_ids . ')';

if(isset($_GET['subj_code']) && !empty($_GET['subj_code'])) {

   $query .= ' AND LOWER(subject_code) = "' . strtolower($_GET['subj_code']) . '"';
}

// var_dump($query);
// die();

$q = $instance->query($query);
$results = $q->results();

foreach($results as $result) {
   foreach($ph_results as $ph_result) {
      if($ph_result->order_id == $result->order_id) {
         $result->payment_type = $ph_result->payment_type;
      }

      $result->or_number = $ph_result->or_number;
      $result->payment_date = $ph_result->payment_date;
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
         <h1>Payment History</h1>
      </div>
      <div class="col-md-12">
      <div class="row">
            <!-- <div class="col-md-3">
               <div class="form-group">
                  <label for="sales_type">Sales Type</label>
                  <select name="sales_type" id="sales_type" class="form-control">
                     <option value="">CCS</option>
                     <option value="">Nursing</option>
                  </select>
               </div>
            </div> -->
            <!-- <div class="col-md-3">
               <div class="form-group">
                  <label for="date">Date From</label>
                  <input type="date" id="select-date-from" class="form-control">
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                  <label for="date">Date To</label>
                  <input type="date" id="select-date-to" class="form-control">
               </div>
            </div> -->
            <div class="col-md-2">
               <div class="form-group">
                  <label for="date">Report Type</label>
                  <select class="form-control" id="report-type">
                     <option value="daily">Daily</div>
                     <option value="weekly">Weekly</div>
                     <option value="monthly">Monthly</div>
                  </select>
               </div>
            </div>

            <div class="col-md-2 col-md-offset-8">
               <div class="form-group">
                  <label for="date">Subject Code</label>
                  <select class="form-control" id="select-subject-code">
                     <option value="">All</div>
                  </select>
               </div>
            </div>
            
            <!-- <div class="col-md-3">
               <div class="form-group">
                  <label for="search">Search</label>
                  <input type="text" id="search-keyword" class="form-control">
               </div>
            </div> -->

         </div>
         <div class="table-responsive">
            <table class="table table-info table-bordered">
               <tr class="info">
                  <th>Date</th>
                  <th>Name</th>
                  <th>ID Number</th>
                  <th>Subject Code</th>
                  <th>EDP Code</th>
                  <th>Title Of Textbook</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</td>
                  <th>Payment Type</th>
                  <th>OR Number</td>
                  <th>Status</td>
               </tr>
               <?php foreach($results as $result) { ?>
               <tr>
                  <td><?php echo $result->payment_date; ?></td>
                  <td><?php echo $result->u_full_name ? $result->u_full_name : ($result->od_full_name ?? '---'); ?></td>
                  <td><?php echo $result->id_number ? $result->id_number : ($result->od_id_number ?? '---'); ?></td>
                  <td><?php echo $result->subject_code; ?></td>
                  <td><?php echo $result->edp_code; ?></td>
                  <td><?php echo $result->textbook_name; ?></td>
                  <td><?php echo $result->price; ?></td>
                  <td><?php echo $result->od_quantity; ?></td>
                  <td><?php echo $result->total; ?></td>
                  <td><?php echo $result->payment_type == 'cash' ? 'Cash' : 'Credit/Debit Card'; ?></td>
                  <td><?php echo $result->or_number ?? '---'; ?></td>
                  <td>PAID</td>
               </tr>
               <?php } ?>
            </table>
         </div>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>

<script>
   function getParam(param) {
      return new URLSearchParams(window.location.search).get(param);
   }

   $('#select-subject-code').on('change', function() {
      let today = moment(new Date());
      if(getParam('report_type') == 'daily') {
         window.location = '/payment_history.php?dates=' + today.format('YYYY-MM-DD') + ',' + today.format('YYYY-MM-DD') + '&report_type=' + $('#report-type').val() + '&subj_code=' + $('#select-subject-code').val()
      } else if(getParam('report_type') == 'weekly') {
         window.location = '/payment_history.php?dates=' + today.startOf('isoWeek').format('YYYY-MM-DD') + ',' + today.endOf('isoWeek').format('YYYY-MM-DD') + '&report_type=' + $('#report-type').val() + '&subj_code=' + $('#select-subject-code').val()
      } else if(getParam('report_type') == 'monthly') {
         window.location = '/payment_history.php?dates=' + today.startOf('month').format('YYYY-MM-DD') + ',' + today.endOf('month').format('YYYY-MM-DD')  + '&report_type=' + $('#report-type').val() + '&subj_code=' + $('#select-subject-code').val()
      }
   });

   $('#report-type').on('change', function() {
      let today = moment(new Date());

      if($(this).val() == 'daily') {
         window.location = '/payment_history.php?dates=' + today.format('YYYY-MM-DD') + ',' + today.format('YYYY-MM-DD') + '&report_type=' + $(this).val() + '&subj_code=' + $('#select-subject-code').val()
      } else if($(this).val() == 'weekly') {
         window.location = '/payment_history.php?dates=' + today.startOf('isoWeek').format('YYYY-MM-DD') + ',' + today.endOf('isoWeek').format('YYYY-MM-DD') + '&report_type=' + $(this).val() + '&subj_code=' + $('#select-subject-code').val()
      } else if($(this).val() == 'monthly') {
         window.location = '/payment_history.php?dates=' + today.startOf('month').format('YYYY-MM-DD') + ',' + today.endOf('month').format('YYYY-MM-DD')  + '&report_type=' + $(this).val() + '&subj_code=' + $('#select-subject-code').val()
      }
   })

   if(getParam('report_type')) {
      $('#report-type').val(getParam('report_type'))
   }

   fetchSubjectCode()

   function fetchSubjectCode() {
      $.get('./api_subj_code.php').then(function(response) {
         response = JSON.parse(response);

         $('#select-subject-code').empty();
         $('#select-subject-code').append(new Option('All', ''));
         response.results.forEach(function(row) {
            $('#select-subject-code').append(new Option(row.subject_code, row.subject_code))
         })

         if(getParam('subj_code')) {
            $('#select-subject-code').val(getParam('subj_code'))
         }
      });
   }
</script>