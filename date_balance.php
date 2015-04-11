<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Balance.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include_once 'parts/body_header.php';
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
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Moves
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#Move Id</th>
                                            <th>Date</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Essence</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $start_date = $_POST['start_date'];
                                        $end_date = $_POST['end_date'];
                                        $results = Balance::getAllBalanceByDate($start_date, $end_date);
                                        foreach ($results as $result) {
                                        	$move_date = Balance::getBalanceDate($result['MOVE_ID']);
                                        	$description = Products::getProductDesc($result['P_ID']);
                                        	//debug($description);
                                        	$price = Products::getProductPrice($result['P_ID']);
                                        	$balance = Balance::getTotalBalanceByDate($start_date, $end_date, $result['MOVE_ID']);
                                        	?>                 
                                    		<tr>
												<td><?php echo($result['MOVE_ID']); ?></td>
												<td><?php echo($move_date[0]['MOVE_DATE'])?></td>
												<td><?php echo($description)?></td>
												<td><?php echo($price)?></td>
												<td><?php echo($result['QUANTITY'])?></td>
												<td><?php echo($result['ESSENCE'])?></td>
												<td><strong><?php if($balance[0]['TOTAL'] > 0){ echo "<font color='green'>";} else{ echo "<font color='red'>";} echo($balance[0]['TOTAL'])?></strong></td>
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
						<h1 class="page-header">Search Products</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				</br>
				<!-- /.row -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Search
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        	<form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
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
