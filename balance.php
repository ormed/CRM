<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Balance.php';
include_once 'database/Products.php';
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

                                        $results = Balance::getBalance();
                                        
                                        foreach ($results as $result) {
                                        	$move_date = Balance::getBalanceDate($result['MOVE_ID']);
                                        	$description = Products::getProductDesc($result['P_ID']);
                                        	$price = Products::getProductPrice($result['P_ID']);
                                        	$balance = Balance::getTotalBalance($result['MOVE_ID']);
                                        	?>                 
                                    		<tr>
												<td><?php echo($result['MOVE_ID']); ?></td>
												<td><?php echo($move_date[0]['MOVE_DATE'])?></td>
												<td><?php echo($description)?></td>
												<td><?php echo($price)?></td>
												<td><?php echo($result['QUANTITY'])?></td>
												<td><?php echo($result['ESSENCE'])?></td>
												<td><?php if($balance[0]['TOTAL'] > 0){ echo "<font color='green'>";} else{ echo "<font color='red'>";} echo($balance[0]['TOTAL'])?></td>
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


			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
