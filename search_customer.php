<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Customer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include_once 'parts/body_header.php';

	$results = Customer::getCustomersDetails($_POST['cust_id'], $_POST['first_name'], $_POST['last_name']);
	?>
	<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Search Customers</h1>
					</div>
					<!-- /.col-lg-12 -->
					<input type="button" class="btn btn-info" value="Search" onClick='parent.location="search_customer.php"'/>
				</div>
				</br>
				<!-- /.row -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Customers
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
						<h1 class="page-header">Search Customers</h1>
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
	                            	<i class="fa fa-caret-right"></i> <label>Customer Id</label>
	                            	<input class="form-control" name="cust_id" maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57'>
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>First Name</label>
	                            	<input class="form-control" name="first_name">
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Last Name</label>
	                            	<input class="form-control" name="last_name">
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
