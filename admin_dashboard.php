<?php

$instance = DB::getInstance();


?>

<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-body">
         <div class="row">
            <div class="col-md-5 col-md-offset-1">
               <?php
                  $pending_count = $instance->get('order_details', ['status', '=', 'pending']);
                  $pending_count = $pending_count->results();
               ?>
               <button class="btn btn-default btn-block" style="font-size:30px;padding:30px;"><span class="label label-info " style="font-size:25px;"><?php echo count($pending_count) ?? '0' ?></span>  | Pending Orders</button>
            </div>
            <div class="col-md-4">
               <?php
                  $all = DB::getInstance()->query('SELECT * FROM order_details');
                  $all = $all->results();
               ?>
               <a href="#" class="btn btn-default btn-block" style="font-size:30px;padding:30px;"><span class="label label-info" style="font-size:25px;"><?php echo count($all) ?? '0' ?></span>  | Orders</a>
            </div>
         </div>
      </div>
   </div>
   <?php
 
$dataPoints = array( 
   array("y" => 50, "label" => "CCS" ),
   array("y" => 45, "label" => "Nursing" ),
   array("y" => 15, "label" => "BSED" ),
   array("y" => 10, "label" => "BSECE" ),
   array("y" => 29, "label" => "BSBA" ),
   array("y" => 21, "label" => "BSHRM" ),
   array("y" => 35, "label" => "BSA" )
);
 
?>

<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
   animationEnabled: true,
   theme: "light2",
   title:{
      text: "Active Users"
   },
   axisY: {
      title: "Users (in UC-banilad)"
   },
   data: [{
      type: "column",
      yValueFormatString: "#,##0.## Users",
      dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
   }]
});
chart.render();

 
}
</script>
<!-- <div id="chartContainer" style="height: 370px; width: 100%;"></div> -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</div>

</div>