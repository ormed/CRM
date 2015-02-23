<?php include_once 'connection/checkUser.php';?>

<?php 
include_once 'database/Customer.php';
include_once 'help_functions.php';

$err = '';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$err = Customer::testNewCustomer();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($err)) {
	$result = Customer::insertCustomer($_POST['first_name'], $_POST['last_name']); //insert new customer
	if($result) {
		// Popup added customer succesfully
		$message = "Added New Customer: ". $_POST['first_name']." ".$_POST['last_name'];
		$url = "index.php";
	} else {
		// Error on insert
		$message = "Error adding new customer...";
		$url = "customer.php";
	}
	echo "<script> alert('$message'); window.location.href='$url';</script>";
} else {
?>

<?php include_once 'parts/header.php';?>

<body>

    <div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
        <div id="page-wrapper">
        
            <div class="container-fluid">
            
            
            
            
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add New Customer</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
       
                <div class="panel panel-default">
                        <div class="panel-heading">
                            New Customer
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                    	
                                    	<?php if(!empty($err)) { ?>
                                    	<div class="alert alert-danger" role="alert">
                                    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    		<?php echo $err?>
                                    	</div> 
                                    	<?php }?>
                                    	
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" name="first_name">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name">
                                        </div>

                                        <button type="submit" class="btn btn-default">Submit Button</button>
                                        <button type="reset" class="btn btn-default">Reset Button</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                
                         
                
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    
<?php 
include_once 'parts/bottom.php';

include_once 'parts/footer.php';
}
?>
