<?php include_once 'connection/checkUser.php';?>

<?php 
include_once 'database/Database.php';
include_once 'database/Products.php';
include_once 'help_functions.php';

$err = '';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Check all input fields filled fine 
	$err = Products::testNewProduct($_POST['desc'], $_POST['price'], $_POST['store_price'], $_POST['quantity']);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($err)) {
	if(isset($_POST['edit_product'])) {
		$desc = substr($_POST['desc'], 0, strpos($_POST['desc'], ','));
	} else {
		$desc = $_POST['desc'];
	}
	$result = Products::insertProduct($desc, $_POST['price'], $_POST['store_price'], $_POST['quantity']); // insert or update product
	if($result) {
		if(isset($_POST['new_product'])) {
			// Popup added product succesfully
			$message = "Added New Product: ". $desc;
		} else {
			$message = "Updated Product: ". $desc;
		}
		$url = "all_products.php";
	} else {
		// Error on insert
		$message = "Error adding new product...";
		$url = "product.php";
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
                        <h1 class="page-header">Add/Edit Product</h1>
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
                                            <i class="fa fa-usd"></i> <label>Price</label>
                                            <input class="form-control" name="price" placeholder="Price" maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57'>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-shopping-cart"></i> <label>Store Price</label>
                                            <input class="form-control" name="store_price" placeholder="Store Price" maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57'>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-cubes"></i> <label>Quantity</label>
                                            <input class="form-control" name="quantity" placeholder="Quantity" maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>
                                        
                                        <button type="submit" name="new_product" class="btn btn-success">New Product</button>
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
                            Edit Product
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
                                    	
                                    	<?php 
                                    		$results = Products::getAllProducts();
			                            ?>
                                    	
                                        <div class="form-group">
                                            <i class="fa fa-info-circle"></i> <label>Description</label>
                                            <select id="option_desc" name="desc" class="form-control" style="width:200px" onchange="showDetails()">
				                            <?php foreach ($results as $result) { ?>
				                            <option value='<?php echo($result["DESCRIPTION"].",".$result['PRICE'].",".$result['STORE_PRICE'].",".$result['QUANTITY']);?>'><?php echo($result["DESCRIPTION"]);?></option>
				                            <?php } ?>
				                            </select>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-usd"></i> <label>Price</label>
                                            <input class="form-control" id='edit_price' name="price" placeholder='Edit Price' value='<?php echo $results[0]['PRICE'];?>' maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57'>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-shopping-cart"></i> <label>Store Price</label>
                                            <input class="form-control" id='edit_store_price' name="store_price" placeholder='Edit Store Price' value='<?php echo $results[0]['STORE_PRICE'];?>' maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57'>
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-cubes"></i> <label>Quantity</label>
                                            <input class="form-control" id='edit_quantity' name="quantity" placeholder='Edit Quantity' value='<?php echo $results[0]['QUANTITY'];?>' maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>
                                        
                                        <button type="submit" name="edit_product" class="btn btn-success">Save Product</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
                    <input type=button onClick="location.href='all_products.php'" class="btn btn-primary" value='Back'>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    
    <script>
    	function showDetails() 
    	{
    		var x = document.getElementById("option_desc").value;
        	var price = x.split(",")[1];
    		var store_price = x.split(",")[2];
    		var quantity = x.split(",")[3];
    	    document.getElementById("edit_price").value = price;
    	    document.getElementById("edit_store_price").value = store_price;
    	    document.getElementById("edit_quantity").value = quantity;
    	}
    </script>
    
<?php 
include_once 'parts/bottom.php';
include_once 'parts/footer.php';
}
?>
