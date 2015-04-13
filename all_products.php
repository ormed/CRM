<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
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
						<h1 class="page-header">All Products</h1>
					</div>
					<!-- /.col-lg-12 -->
					<input type="button" class="btn btn-primary" value="Add/Edit" onClick='parent.location="product.php"'/> 
					<input type="button" class="btn btn-info" value="Search" onClick='parent.location="search_product.php"'/>
				</div>
				</br>
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
