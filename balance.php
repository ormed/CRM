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
                                            <th>Price Per Unit</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Essence</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $results = Balance::getBalance();
                                        foreach ($results as $index=>$result) {
                                        	$move_date = Balance::getBalanceDate($result['MOVE_ID']);
                                        	$description = Products::getProductDesc($result['P_ID']);
//                                         	$price = Products::getProductPrice($result['P_ID']);
											$price = $result['PRICE'];
                                        	$balance = Balance::getTotalBalance($result['MOVE_ID']);
                                        	?>                 
                                    		<tr>
												<td><?php echo($result['MOVE_ID']); ?></td>
												<td><?php echo($move_date[0]['MOVE_DATE'])?></td>
												<td><?php echo($description)?></td>
												<td><?php echo($price)?></td>
												<td><?php echo($result['QUANTITY'])?></td>
												<td><?php if(strcmp($result['ESSENCE'],"Credit") == 0){ echo("<font color='green'>".($result['QUANTITY']*$price)."$");} else{echo("<font color='red'>-".($result['QUANTITY']*$price)."$");} ?></font></td>
												<td><?php echo($result['ESSENCE'])?></td>
												<?php if($index == (count($results)-1)) { ?>
												<td><h4><u><strong><?php if($balance[0]['TOTAL'] > 0){ echo "<font color='green'>";} else{ echo "<font color='red'>";} echo($balance[0]['TOTAL']."$")?></font></strong></u></h4></td>
												<?php } else { ?>
												<td><strong><?php if($balance[0]['TOTAL'] > 0){ echo "<font color='green'>";} else{ echo "<font color='red'>";} echo($balance[0]['TOTAL']."$")?></font></strong></td>
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


			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
