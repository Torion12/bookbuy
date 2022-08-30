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
      background-color:#94d4e0 ;
   }

</style>
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <h1>Sales Report</h1>
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
               </tbody>
               <tr class="active" style="font-weight:bold">
                  <td>Grand Total</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id="grand-total">200</td>
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
    $('#printPage').on('click', function() {
      let today = moment(new Date());

      if($('#report-type').val() == 'daily') {
         var newWindow=window.open('./sales_report_print.php?date_from=' + moment(new Date()).format('YYYY-MM-DD') + '&date_to=' + moment(new Date()).format('YYYY-MM-DD') + '&subject_code=' + $('#select-subject-code').val());
      } else if($('#report-type').val() == 'weekly') {
         var newWindow=window.open('./sales_report_print.php?date_from=' + moment(new Date()).startOf('isoWeek').format('YYYY-MM-DD') + '&date_to=' + moment(new Date()).endOf('isoWeek').format('YYYY-MM-DD') + '&subject_code=' + $('#select-subject-code').val());
      } else if($('#report-type').val() == 'monthly') {
         var newWindow=window.open('./sales_report_print.php?date_from=' + moment(new Date()).startOf('month').format('YYYY-MM-DD') + '&date_to=' + moment(new Date()).endOf('month').format('YYYY-MM-DD') + '&subject_code=' + $('#select-subject-code').val());

      }
        newWindow.focus();
        newWindow.print();
    });

    $('#select-subject-code').on('change', function() {
      $('#report-type').trigger('change')
    });

   $('#report-type').on('change', function() {
      let today = moment(new Date());

      if($(this).val() == 'daily') {
         getOrders(
            moment(new Date()).format('YYYY-MM-DD'), 
            moment(new Date()).format('YYYY-MM-DD'),
            $('#select-subject-code').val()
         )
      } else if($(this).val() == 'weekly') {
         getOrders(
            today.startOf('isoWeek').format('YYYY-MM-DD'),
            today.endOf('isoWeek').format('YYYY-MM-DD'),
            $('#select-subject-code').val()
         )
      } else if($(this).val() == 'monthly') {
         getOrders(
            today.startOf('month').format('YYYY-MM-DD'),
            today.endOf('month').format('YYYY-MM-DD'),
            $('#select-subject-code').val()
         )
      }
   })

   function fetchDepartment() {
      $.get('./api_dept.php').then(function(response) {
         response = JSON.parse(response);

         $('#sales_type').empty();
         $('#sales_type').append(new Option('All', ''));
         response.results.forEach(function(row) {
            $('#sales_type').append(new Option(row.department, row.department))
         })
      });
   }

   function fetchSubjectCode() {
      $.get('./api_subj_code.php').then(function(response) {
         response = JSON.parse(response);

         $('#select-subject-code').empty();
         $('#select-subject-code').append(new Option('All', ''));
         response.results.forEach(function(row) {
            $('#select-subject-code').append(new Option(row.subject_code, row.id))
         })
      });
   }
   
   fetchSubjectCode();
   fetchDepartment();

   $('#select-date-from').val()
   $('#select-date-to').val()
   getOrders(moment(new Date()).format('YYYY-MM-DD'), moment(new Date()).format('YYYY-MM-DD'))

   function getOrders(date_from = '', date_to = '', subject_code = '') {
      $.get('./api_orders.php?status=paid&date_from=' + date_from + '&date_to=' + date_to + '&subject_code=' + subject_code).then(function(response) {
         response = JSON.parse(response)
         let keys = Object.keys(response.results)
         let total = 0;
         $('#sales-report-table').empty();
         keys.forEach(function(row) {
            $('#sales-report-table').append(`
               <tr>
                  <td>` + response.results[row][0].od_created_at + `</td>
                  <td>` + (response.results[row][0].u_full_name ? response.results[row][0].u_full_name : response.results[row][0].od_full_name) + `</td>
                  <td>` + (response.results[row][0].id_number ? response.results[row][0].id_number : response.results[row][0].od_id_number) + `</td>
                  <td>` + response.results[row][0].subject_code + `</td>
                  <td>` + response.results[row][0].edp_code + `</td>
                  <td>` + response.results[row][0].textbook_name + `</td>
                  <td>` + response.results[row][0].publisher + `</td>
                  <td>` + (response.results[row][0].price ? response.results[row][0].price : response.results[row][0].od_id_number) + `</td>
                  <td>` + response.results[row][0].od_quantity + `</td>
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

   // $('#search-keyword').on('change', function() {
   //    getOrders($('#select-date').val(), $('#search-keyword').val(), $('#sales_type').val())
   // })

   // $('#sales_type').on('change', function() {
   //    getOrders($('#select-date').val(), $('#search-keyword').val(), $('#sales_type').val())
   // })
</script>