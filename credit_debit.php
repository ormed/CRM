<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Balance.php';

$db = new Database();
$credit = Balance::getTotalCredit($db)[0]['TOTAL_CREDIT'];
$debit = Balance::getTotalDebit($db)[0]['TOTAL_DEBIT'];
?>

<body>

	<div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
		<div id="page-wrapper">

			<div class="container-fluid">
			
			
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Credit vs Debit</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				</br>
				<!-- /.row -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
	                    	<i class="fa fa-bar-chart"></i> Balance Graph
	                   	</div>
	                    <!-- /.panel-heading -->
	                    <div class="panel-body">
							<div align="center" id="dual_y_div" style="width:900px; height: 500px;"></div>
						</div>
						
						<input type="hidden" id='hidden_credit' value='<?php echo $credit?>'/>
						<input type="hidden" id='hidden_debit' value='<?php echo $debit?>'/>
                        
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->


			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->
	<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['bar']}]}"></script>
	 
	<script type="text/javascript">
	google.setOnLoadCallback(drawStuff);
	function drawStuff() {
		var credit = document.getElementById("hidden_credit").value;
		var debit = document.getElementById("hidden_debit").value;
		
        var data = new google.visualization.arrayToDataTable([
          ['Essence', 'Ammount'],
          ['Credit', credit],
          ['Debit', debit]
        ]);

        var options = {
          width: 1000,
          chart: {
          },
          series: {
            0: { axis: 'distance' }, // Bind series 0 to an axis named 'distance'.
            1: { axis: 'brightness' } // Bind series 1 to an axis named 'brightness'.
          },
          axes: {
            y: {
              distance: {label: 'parsecs'}, // Left y-axis.
              brightness: {side: 'right', label: 'apparent magnitude'} // Right y-axis.
            }
          }
        };

      var chart = new google.charts.Bar(document.getElementById('dual_y_div'));
      chart.draw(data, options);
    };
  </script>

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
