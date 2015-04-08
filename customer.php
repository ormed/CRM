<?php include_once 'connection/checkUser.php';?>

<?php 
include_once 'database/Customer.php';
include_once 'help_functions.php';

$err = '';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Check all input fields filled fine
	if(isset($_POST['edit_customer'])) { 
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
	} else {
		$first_name = $_POST['new_first_name'];
		$last_name = $_POST['new_last_name'];
	}
	$err = Customer::testNewCustomer($first_name, $last_name);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($err)) {
	if(isset($_POST['edit_customer'])) { 
		// Edit customer
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$cust_id = substr($_POST['customer'], 0, strpos($_POST['customer'], ','));
		$result = Customer::updateCustomer($cust_id, $first_name, $last_name); 
		if($result) {
			// Popup added customer succesfully
			$message = "Updated Customer: ".$first_name." ".$last_name;
			$url = "all_customers.php";
		} else {
			// Error on insert
			$message = "Error adding new customer...";
			$url = "customer.php";
		}
	} else {
		// Insert new customer
		$first_name = $_POST['new_first_name'];
		$last_name = $_POST['new_last_name'];
		$result = Customer::insertCustomer($first_name, $last_name);
		if($result) {
			// Popup added customer succesfully
			$message = "Added New Customer: ". $first_name." ".$last_name;
			$url = "all_customers.php";
		} else {
			// Error on insert
			$message = "Error adding new customer...";
			$url = "customer.php";
		}
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
                
                <?php if(!empty($err)) { ?>
            	       	<div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <?php echo $err?>
                        </div> 
                <?php }?>
                                    	
       
                	<div class="panel panel-default">
                        <div class="panel-heading">
                           <i class="fa fa-user"></i> New Customer
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                    	
                                        <div class="form-group">
                                            <i class="fa fa-caret-right"></i> <label>First Name</label>
                                            <input class="form-control" name="new_first_name" value="<?php if(isset($_POST['new_first_name'])) echo $_POST['new_first_name'];?>">
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-caret-right"></i> <label>Last Name</label>
                                            <input class="form-control" name="new_last_name" value="<?php if(isset($_POST['new_last_name'])) echo $_POST['new_last_name'];?>">
                                        </div>

                                        <button type="submit" name="new_customer" class="btn btn-success">New Customer</button>
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-pencil-square-o"></i> Edit Customer
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                    	
                                    	<?php 
			                            	$results = Customer::getAllCustomers();
			                            ?>
                                    	
                                        <div class="form-group">
                                            <i class="fa fa-info-circle"></i> <label>Customer</label>
                                            <select id="option_cust" name="customer" class="form-control" style="width:200px" onchange="showDetails()">
				                            <?php foreach ($results as $result) { ?>
				                            <option value='<?php echo($result['CUST_ID'].",".$result['FIRST_NAME'].",".$result['LAST_NAME']);?>'><?php echo($result['FIRST_NAME']." ".$result['LAST_NAME']);?></option>
				                            <?php } ?>
				                            </select>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-caret-right"></i> <label>First Name</label>
                                            <input class="form-control" id='edit_first_name' name="first_name" placeholder='Edit First Name' value='<?php echo $results[0]['FIRST_NAME'];?>'>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-caret-right"></i> <label>Last Name</label>
                                            <input class="form-control" id='edit_last_name' name="last_name" placeholder='Edit Last Name' value='<?php echo $results[0]['LAST_NAME'];?>'>
                                        </div>
                                        
                                        <button type="submit" name="edit_customer" class="btn btn-success">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
                    <input type=button onClick="location.href='all_customers.php'" class="btn btn-primary" value='Back'>
                
                         
                
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <script>
    	function showDetails() 
    	{
    		var x = document.getElementById("option_cust").value;
        	var first_name = x.split(",")[1];
    		var last_name = x.split(",")[2];
    	    document.getElementById("edit_first_name").value = first_name;
    	    document.getElementById("edit_last_name").value = last_name;
    	}
    </script>
    
<?php 
include_once 'parts/bottom.php';

include_once 'parts/footer.php';
}
?>
