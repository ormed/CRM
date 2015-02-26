<?php include_once 'connection/checkUser.php';?>

<?php 
include_once 'database/Database.php';
include_once 'database/Products.php';
include_once 'help_functions.php';

$err = '';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$err = Products::testNewProduct();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($err)) {
	$result = Products::insertProduct(); //insert new product
	if($result) {
		// Popup added product succesfully
		$message = "Added New Product: ". $_POST['desc'];
		$url = "index.php";
	} else {
		// Error on insert
		$message = "Error adding new customer...";
		$url = "product.php";
	}
	//echo "<script> alert('$message'); window.location.href='$url';</script>";
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
                        <h1 class="page-header">Add New Product</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
       
                <div class="panel panel-default">
                        <div class="panel-heading">
                            New Product
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
                                            <i class="fa fa-info-circle"></i> <label>Description</label>
                                            <input class="form-control" name="desc" placeholder="Product Description">
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-cubes"></i> <label>Quantity</label>
                                            <input class="form-control" name="quantity" placeholder="Quantity" maxlength="2" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>

                                        <button type="submit" class="btn btn-default">New Product</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
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
