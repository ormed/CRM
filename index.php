<?php include_once 'connection/checkUser.php';?>

<?php include_once 'parts/header.php';?>

<body>

	<div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
		<div id="page-wrapper">

			<div class="container-fluid">




				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Welcome to CRM</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->

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
                                        
                                        $q = "Select * from customers";
                                        $db = new Database();
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
					</div>
					<!-- /.panel-body -->
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Users</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>

									<tr>
										<th>User Id</th>
										<th>Username</th>
										<th>Password</th>
										<th>First Name</th>
										<th>Last Name</th>
									</tr>
								</thead>
								<tbody>
                                        <?php 
                                        
                                        $q = "Select * from users";
                                        $db = new Database();
                                        $results = $db->createQuery($q);
                                                     
                                        foreach ($results as $result) {
                                        	?>
                                                                                
                                    		<tr>
												<td><?php echo($result["USER_ID"]); ?></td>
												<td><?php echo($result["USERNAME"]); ?></td>
												<td><?php echo($result["PASSWORD"]); ?></td>
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

				<div class="panel panel-default">
					<div class="panel-heading">Products</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>

									<tr>
										<th>P Id</th>
										<th>Description</th>
									</tr>
								</thead>
								<tbody>
                                        <?php 
                                        
                                        $q = "select * from products;";
                                        $db = new Database();
                                        $results = $db->createQuery($q);
                                        debug($results);
                                        
                                        foreach ($results as $result) {
                                        	?>
                                                                                
                                    		<tr>
												<td><?php echo($result["P_ID"]); ?></td>
												<td><?php echo($result["DESCRIPTION"])?></td>
												<td><?php //echo($result["QUANTITY"])?></td>
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


			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
