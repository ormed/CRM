<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Balance.php';
include_once 'database/User.php';

$err = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(empty($_POST['start_date']) || empty($_POST['end_date'])) {
		$err = "Please fill in all the fields";
	}
}

if (($_SERVER["REQUEST_METHOD"] == "POST") && (empty($err))) {
	include_once 'parts/body_header.php';
	
	$db = new Database();
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$results = Balance::getAllBalanceByDate($start_date, $end_date, $db);
	
	?>
	<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Balance</h1>
					</div>
					<!-- /.col-lg-12 -->
					<input type="button" class="btn btn-info" value="Back" onClick='parent.location="date_balance.php"'/>
				</div>
				</br>
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
                            <i class="fa fa-sort-amount-desc"></i> Moves
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
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
                                        	$username = User::getUsername($result['USER_ID']);
                                        	$description = Products::getProductDesc($result['P_ID'], $db);
                                        	$price = $result['PRICE'];
                                        	$balance = Balance::getTotalBalanceByDate($start_date, $end_date, $result['MOVE_ID'], $db);
                                        	?>                 
                                        	<input type="hidden" id='hidden_balance_<?php echo $index?>' value='<?php echo $balance[0]['TOTAL']?>'/>
											<input type="hidden" id='hidden_date_<?php echo $index?>' value='<?php echo($move_date[0]['MOVE_DATE'])?>'/>
												
                                    		<tr>
												<td><?php echo($result['MOVE_ID']); ?></td>
												<td><?php echo ($username); ?></td>
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
               				document.getElementById('graph').style.display='block';
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
	<?php 
	
	include_once 'parts/body_footer.php';
} else {
?>

<body>

	<div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
		<div id="page-wrapper">

			<div class="container-fluid">
			
			
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Balance By Date</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				</br>
				<!-- /.row -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Dates
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        	<form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                        		<?php if(!empty($err)) { ?>
                                    	<div class="alert alert-danger" role="alert">
                                    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    		<?php echo $err?>
                                    	</div> 
                                    	<?php }?>
                                    	
	                        	<div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Start Date</label>
	                            	<input class="form-control" name="start_date" style="width:200px" type="date" value=<?php echo date("Y-m-d")?>/>
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>End Date</label>
	                            	<input class="form-control" name="end_date" style="width:200px" type="date" value=<?php echo date("Y-m-d")?>/>
	                            </div>
	                            
	                            <button type="submit" class="btn btn-success">Search</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
	                     	</form>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->


			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

    
<?php 
}
include_once 'parts/bottom.php';
?>

</body>

</html>
