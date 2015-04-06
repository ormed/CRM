<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Customer.php';
?>

<body>

	<div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
		<div id="page-wrapper">

			<div class="container-fluid">
			
			
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">All Customers</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Products
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#Customer Id</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        
                                        $results = Customer::getAllCustomers();
                                        debug($results);
                                        
                                        foreach ($results as $result) {
                                        	?>                 
                                    		<tr>
												<td><?php echo($result["CUST_ID"]); ?></td>
												<td><?php echo($result["FIRST_NAME"])?></td>
												<td><?php echo($result["LAST_NAME"])?></td>
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
