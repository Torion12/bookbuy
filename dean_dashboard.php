<div class="col-md-12">
   <div class="panel panel-default">
      <div class="panel-body">
         <div class="row">
            <div class="col-md-4">
            <a href="./request_order.php" class="btn btn-default btn-block" style="font-size:30px;padding:30px; background: #2DFFDA;"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> <br /> Request Order</a>
            </div>
            <div class="col-md-4">
               <a href="./inventory_reprt.php" class="btn btn-default btn-block" style="font-size:30px;padding:30px; background:#4DFF65;"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <br /> View Inventory</a>
            </div>   
            <div class="col-md-4">
               <a href="./sales_report.php" class="btn btn-default btn-block" style="font-size:30px;padding:30px; background: #1FD4FB;"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> <br /> View Sales Report</a>
            </div>
         </div>
      </div>
   </div>
   <?php
$dataPoints = array( 
   array("y" => 3000, "label" => "CCS" ),
   array("y" => 2435, "label" => "Nursing" ),
   array("y" => 1842, "label" => "BSED" ),
   array("y" => 1828, "label" => "BSECE" ),
   array("y" => 1039, "label" => "BSBA" ),
   array("y" => 765, "label" => "BSHRM" ),
   array("y" => 612, "label" => "BSA" )
);
 
?>

<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
   animationEnabled: true,
   theme: "light2",
   title:{
      text: "Textbooks (2021-2022)"
   },
   axisY: {
      title: "Textbooks (in tonnes)"
   },
   data: [{
      type: "column",
      yValueFormatString: "#,##0.## Textbooks",
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
