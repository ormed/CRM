<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Products.php';
include_once 'database/Inventory.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include_once 'parts/body_header.php';
	
	$results = Products::getProductDetails($_POST['desc'], $_POST['p_id']);
	?>
	<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Search Products</h1>
					</div>
					<!-- /.col-lg-12 -->
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
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#Product Id</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php                             
                                        foreach ($results as $result) {
                                        ?>                 
                                    		<tr>
												<td><?php echo($result["P_ID"]); ?></td>
												<td><?php echo($result["DESCRIPTION"])?></td>
												<td>$<?php echo($result["PRICE"])?></td>
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
	                            	<i class="fa fa-caret-right"></i> <label>Product Id</label>
	                            	<input class="form-control" name="p_id" style="width:200px" maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57'>
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Product Description</label>
	                            	<input class="form-control" name="desc" style="width:200px">
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
