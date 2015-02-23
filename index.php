<?php //include_once 'connection/validator.php';?>

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
				<button type="button" class="btn btn-primary">Primary</button>

				<div class="panel panel-default">
					<div class="panel-heading">Customers</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>

									<tr>
										<th>Description</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>
                                    
                                    
                                    
                                    <?php /*
                                        $results = array(array("name" => 'Yossi', "age"  => 15), array("name" => 'Itzik', "age"  => 30), array("name" => 'Avi', "age"  => 20));
                                        
                                        debug($results, true);
                                        
                                        foreach ($results as $result) {
                                        ?>
                                        
                                        <tr>
                                            <td><?php echo($result["name"]); ?></td>
                                            <td><?php echo($result["age"])?></td>
                                        </tr>
                                        
                                        <?php 
                                        } 
                                        */?>
                                        
                                        
                                        <?php 
                                        
                                        $q = 'Select * from products';
                                        $db = new Database();
                                        $results = $db->createQuery($q);
                                        
                                        debug($results);
                                                     
                                        foreach ($results as $result) {
                                        	?>
                                                                                
                                    		<tr>
												<td><?php echo($result["DESCRIPTION"]); ?></td>
												<td><?php echo($result["WH_ID"])?></td>
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
