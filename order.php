<?php 
include_once 'connection/checkUser.php';
include_once 'database/Order.php';
include_once 'help_functions.php';


$err = '';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$err = Order::testOrderForm();
} 

if (($_SERVER["REQUEST_METHOD"] == "POST") && (empty($err))) {
	Order::insertNewOrder();
	header("Location: all_orders.php");
}
else {

include_once 'parts/header.php';?>

<body>

    <div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
        <div id="page-wrapper">
        
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Order</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                
                <div class="panel panel-default">
                        <div class="panel-heading">
                            New Order
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="order-form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                    
                                    <?php if(!empty($err)) { ?>
                                    	<div class="alert alert-danger" role="alert">
                                    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    		<?php echo $err?>
                                    	</div> 
                                    	<?php }?>
                                    	
                                    <div class="form-group">
                                            <i class="fa fa-calendar"></i> <label>Date</label> 
                                        	<div>
                                        		<input class="form-control" name="order-date" style="width:200px" type="date" value=<?php echo date("Y-m-d")?> >
                                       		</div>
			  							</div>
			  							
			  									<?php 
			  									$results = Customer::getAllCustomers();
                                        		?>
                                        		
                                        <div class="form-group">
                                            <i class="fa fa-user"></i> <label>Customer</label>
                                            <select name="customer" class="form-control" style="width:200px">
                                            			<?php foreach ($results as $result) { ?>
                                                <option>	<?php echo($result["CUST_ID"]." ".$result["FIRST_NAME"]." ".$result["LAST_NAME"]);?></option>
                                            			<?php } ?>
                                           	</select>
                                        </div>
                                        
                                        <div class="form-group">
                                        	<i class="fa fa-cubes"></i> <label>Items</label>
                                        	                     		
                                        		<?php 
                                        			$results = Products::getAllProducts();
                                        		?>
                                        		
                                        		<div>
                                        			<i class="fa fa-cube"></i> <label>Item #1</label>
                                        		</div>
                                        		<div>
                                        			Description: <select id="desc1" name="desc1" class="form-control" style="width:200px" onchange="showPrice(1)">
                                                				<?php foreach ($results as $result) { ?>
                                                				<option value='<?php echo($result['DESCRIPTION'].",".$result['PRICE']);?>'><?php echo($result["DESCRIPTION"]);?></option>
                                                				<?php } ?>
                                           					 </select>
                                        		</div>
                                        		<div>
                                            		<i class="fa fa-usd"></i> <label>Price</label>
                                            		<input class="form-control" id='price1' name="price1" style="width:200px" placeholder='Price' value='<?php echo $results[0]['PRICE'];?>' maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57' disabled>
                                        		</div>
                                        		<div>
                                            		<i class="fa fa-cubes"></i> <label>Quantity</label>
                                            		<input class="form-control" id='quantity1' name="quantity1" style="width:200px" placeholder='Quantity' maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                      			</div>
                                        		
                                        		<div id="more-items"></div>
                                        		
										   		<div id="no-more-error"></div>  <!-- Error too much items -->
                                        		
                                        		<div>
                                        		<script type="text/javascript">
													var i = 1;
												</script>
                                        			Add Items:
                                        			<button type="button" id= "plus-items" class="btn btn-success btn-xs" onClick="addItemToDiv(++i)">+</button>
                                        		</div>
                                        		
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-unlock-alt"></i> <label>Status</label>
                                            <select name="status" class="form-control" style="width:200px">
                                                <option>Open</option>
                                                <option>Close</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                        <button type="submit" class="btn btn-default">Submit Order</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
                                    	</div>
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
<script>
function addItemToDiv(i) 
{
// 	if(i > 5) { // Limit to maximum 5 items per order
//		document.getElementById("no-more-error").innerHTML = "<div class='alert alert-danger'>Can't add more than 5 items in one order!</div></div>";
//		document.getElementById("plus-items").className = "btn btn-danger btn-xs";
//	} else {
	var div = document.createElement('div');
	div.innerHTML = "<i class='fa fa-cube'></i> <label>Item #"+i+"</label></div><div>Description: <select id='desc"+i+"' name='desc"+i+"' class='form-control' style='width:200px' onchange='showPrice("+i+")'><?php foreach ($results as $result) { ?><option><?php echo($result["DESCRIPTION"]);?></option><?php } ?></select></div> <div><i class='fa fa-usd'></i> <label>Price</label><input class='form-control' id='price"+i+"' name='price"+i+"' style='width:200px' placeholder='Price' value='<?php echo $results[0]['PRICE'];?>' maxlength='10' onkeypress='return event.charCode >= 46 && event.charCode <= 57' disabled></div><div><i class='fa fa-cubes'></i> <label>Quantity</label><input class='form-control' id='quantity"+i+"' name='quantity"+i+"' style='width:200px' placeholder='Quantity' maxlength='5' onkeypress='return event.charCode >= 48 && event.charCode <= 57'>";
	document.getElementById('more-items').appendChild(div);
// 	}
}

function showPrice(i) 
{
	var price = document.getElementById("desc"+i).value.split(",")[1];
	document.getElementById("price"+i).value = price;
}
</script>
<?php 
include_once 'parts/bottom.php';
include_once 'parts/footer.php'; 
//} 
}
?>
