<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Products.php';
include_once 'database/Balance.php';
include_once 'database/Customer.php';
include_once 'database/Invoice.php';
include_once 'database/Order.php';
?>

<body>

	<div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
		<div id="page-wrapper">

			<div class="container-fluid">
			
			<?php 
			$db = new Database();
			$num_of_customers = Customer::getCustomersCount($db);
			$num_of_orders = Order::getOrdersCount($db);
			$num_of_invoices = Invoice::getInvoiceCount($db);
			?>




				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Welcome to CRM</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
				
				<div class="row">
	                <div class="col-lg-3 col-md-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-users fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge"><?php echo $num_of_customers?></div>
	                                    <div>Customers</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="all_customers.php">
	                            <div class="panel-footer">
	                                <span class="pull-left">View All Customers</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <div class="col-lg-3 col-md-6">
	                    <div class="panel panel-green">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-6 text-right">
	                                    <div class="huge">Total</div>
	                                    <div>Balance</div>
	                                </div>
	                                <div class="col-xs-3">
	                                	<i class="fa fa-line-chart fa-5x"></i>        
	                                </div>
	                            </div>
	                        </div>
	                        <a href="balance.php">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Total Balance</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <div class="col-lg-3 col-md-6">
	                    <div class="panel panel-yellow">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-shopping-cart fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge"><?php echo $num_of_orders;?></div>
	                                    <div>Open Orders</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="edit_order_menu.php">
	                            <div class="panel-footer">
	                                <span class="pull-left">Edit Open Orders</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <div class="col-lg-3 col-md-6">
	                    <div class="panel panel-red">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-book fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge"><?php echo ($num_of_invoices)?></div>
	                                    <div>Invoices</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="all_invoices.php">
	                            <div class="panel-footer">
	                                <span class="pull-left">View All Invoices</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
            </div>

				<div class="panel panel-default">
					<div class="panel-heading">Customers</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>

									<tr>
										<th>Customer Id</th>
										<th>First Name</th>
										<th>Last Name</th>
									</tr>
								</thead>
								<tbody>
                                        <?php 
                                        
                                        $q = "select * from customers";
                                        $results = $db->createQuery($q);

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
						<input type=button onClick="location.href='customer.php'" class="btn btn-primary" value='Add/Edit Customer'>
					</div>
					<!-- /.panel-body -->
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Products</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>#Product Id</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                       	<th>Store Price</th>
                                       	<th>Profit Per Unit</th>
                                        <th>Quantity</th>
									</tr>
								</thead>
								<tbody>
                                        <?php 
                                        
                                        $results = Products::getAllProducts();
                                        
										foreach ($results as $result) {
                                        ?>                 
                                    		<tr>
												<td><?php echo($result["P_ID"]); ?></td>
												<td><?php echo($result["DESCRIPTION"])?></td>
												<td><?php echo($result["PRICE"]."$")?></td>
												<td><?php echo($result["STORE_PRICE"]."$")?></td>
												<td><?php echo(($result["PRICE"]-$result["STORE_PRICE"])."$")?></td>
												<td><?php echo($result["QUANTITY"])?></td>
											</tr>
										<?php 
                                        }

                                        ?>
                                        
                                        
                                        
                                    </tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
						<input type=button onClick="location.href='product.php'" class="btn btn-primary" value='Add/Edit Product'>
					</div>
					<!-- /.panel-body -->
				</div>

			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
