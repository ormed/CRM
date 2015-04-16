<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Balance.php';
include_once 'database/Products.php';
include_once 'database/User.php';


	$db = new Database();
	$results = Balance::getBalance($db);
?>

<body>

	<div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
		<div id="page-wrapper">

			<div class="container-fluid">
			
			
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Balance</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
				
				<div class="panel panel-default" style="display: none;" id="graph">
                        <div class="panel-heading">
                           	<i class="fa fa-line-chart"></i> Balance Graph
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<div align="center" id="chart_div" style="width:100%; height:50%;"></div>
						</div>
				</div>
				
				<input type="button" id='graph_button' class="btn btn-info" value="Show Graph" onClick='drawBasic(<?php echo count($results);?>)'/>
				
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Moves
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#Move Id</th>
                                            <th>Seller</th>
                                            <th>Date</th>
                                            <th>Product</th>
                                            <th>Price Per Unit</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Essence</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($results as $index=>$result) {
                                        	$move_date = Balance::getBalanceDate($result['MOVE_ID'], $db);
                                        	$seller = User::getUsername($result['USER_ID']);
                                        	$description = Products::getProductDesc($result['P_ID'], $db);
											$price = $result['PRICE'];
                                        	$balance = Balance::getTotalBalance($result['MOVE_ID'], $db);
                                        	?>                 
                                        		
                                        		<input type="hidden" id='hidden_balance_<?php echo $index?>' value='<?php echo $balance[0]['TOTAL']?>'/>
												<input type="hidden" id='hidden_date_<?php echo $index?>' value='<?php echo($move_date[0]['MOVE_DATE'])?>'/>
												
                                    		<tr>
												<td><?php echo($result['MOVE_ID']); ?></td>
												<td><?php echo($seller); ?></td>
												<td><?php echo($move_date[0]['MOVE_DATE'])?></td>
												<td><?php echo($description)?></td>
												<td><?php echo($price)?></td>
												<td><?php echo($result['QUANTITY'])?></td>
												<td><?php if(strcmp($result['ESSENCE'],"Credit") == 0){ echo("<font color='green'>".(number_format(($result['QUANTITY']*$price), 1, '.', ','))."$");} else{echo("<font color='red'>-".(number_format(($result['QUANTITY']*$price), 1, '.', ','))."$");} ?></font></td>
												<td><?php echo($result['ESSENCE'])?></td>
												<?php if($index == (count($results)-1)) { ?>
												<td><h4><u><strong><?php if($balance[0]['TOTAL'] > 0){ echo "<font color='green'>";} else{ echo "<font color='red'>";} echo(number_format($balance[0]['TOTAL'], 1, '.', ',')."$")?></font></strong></u></h4></td>
												<?php } else { ?>
												<td><strong><?php if($balance[0]['TOTAL'] > 0){ echo "<font color='green'>";} else{ echo "<font color='red'>";} echo(number_format($balance[0]['TOTAL'], 1, '.', ',')."$")?></font></strong></td>
												<?php } ?>
											</tr>
										<?php 
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
       				
       				<script type="text/javascript">
       				google.load('visualization', '1', {packages: ['corechart', 'line']});
       				//google.setOnLoadCallback(drawBasic);

       				function drawBasic(total) {	
           				var button = document.getElementById("graph_button");
           				if(button.value == 'Show Graph') {
               				button.value = 'Hide Graph';
               				document.getElementById('chart_div').style.display='block';
           					document.getElementById("graph").style.display='block';
           				} else {
           					button.value = 'Show Graph';
           					document.getElementById('chart_div').style.display='none';
           					document.getElementById('graph').style.display='none';
           					return;
           				}
       				      var data = new google.visualization.DataTable();
       				      data.addColumn('string', 'X');
       				      data.addColumn('number', 'Balance');

       				      var balance_value;
       				      var balance_date;
       				      var i;
       				      for(i = 0; i < total; i++) {
           				      balance_value = document.getElementById("hidden_balance_"+i).value;
           				  	  balance_date = document.getElementById("hidden_date_"+i).value;
           				      data.addRow([balance_date, parseInt(balance_value)]);
       				      }

       				      var options = {
       				        hAxis: {
       				          title: 'Date'
       				        },
       				        vAxis: {
       				          title: 'Balance'
       				        }
       				      };

       				      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

       				      chart.draw(data, options);
       				    }
				    </script>


			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
