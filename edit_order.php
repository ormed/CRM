<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Order.php';
include_once 'database/Customer.php';
include_once 'database/Inventory.php';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$result = true;
	if($result == true) {
		$success = "Order saved!";
		$url = "all_orders.php";

	} else {
		$success = "Error! Please try again!";
		$url = "edit_order.php?order_id=".$order_id;
	}
	//echo "<script> alert('$success'); window.location.href='$url';</script>";
}

if (!isset($_GET['order_id'])) {
	//header("Location: all_orders.php");
}

$order_id = $_GET['order_id'];
$order = Order::getOrderHeader($order_id);
$rows = Order::getOrderRows($order_id);
$cust = Customer::getCustomer($order[0]['CUST_ID']);
$order_date = Order::getOrderDate($order_id);	
$total = Order::getTotal($order_id)[0]['TOTAL'];
$status = $order[0]['STATUS'];
if (!$order || strcmp($status, "Close") == 0) {
	//header("Location: all_orders.php");
}	

?>

<body>

    <div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
        <div id="page-wrapper">
        
            <div class="container-fluid">
            
            
            
            
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edit Order</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                
  	<div class="container panel panel-default">
    	<div class="panel-heading">
           <h3><strong><u>Order <?php echo $order_id?>:</u></strong></h3>
    	</div>
    	
    	<form role="form" id="edit-order-form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
   			<div class="panel-body">
		    	<div class="row">
		    		<div class="col-lg-6">
        				<div class="col-xs-12 row">
        					<div class="row">
                			 <div class="col-xs-12 col-md-6 col-lg-6 pull-left">
                    			<div class="panel panel-default height">
                        			<div class="panel-heading">Customer Details</div>
                        			<div class="panel-body">
                            			<strong>Customer Id:</strong> <?php echo $cust[0]['CUST_ID']?><br>
                            			<strong>Name:</strong> <?php echo $cust[0]['FIRST_NAME']." ".$cust[0]['LAST_NAME']?><br>
                        			</div>
                    			</div>
                			</div>
               		 		<div class="col-xs-12 col-md-6 col-lg-6">
                   				<div class="panel panel-default height">
                       				<div class="panel-heading">Order Details</div>
                       				<div class="panel-body">
                       					<div class="row">
  											<div class="col-xs-5 col-md-3"><strong>Status:</strong></div>
  											<div class="col-xs-3 col-md-3">
  												<select name="status" class="form-control" style="width: 90px">
                               						<option selected>Open</option>
                               						<option>Close</option>
                               					</select>
                               				</div>
										</div>
                           				
                           				<strong>Date:</strong> <?php echo $order_date[0]['ORDER_DATE']?>
                               		</div>
                       				</div>
                   				</div>
           					 </div>
           				 </div>
           			</div>
           		</div>
           	</div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center"><strong>Order summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Item Name</strong></td>
                                    <td class="text-center"><strong>Item Price</strong></td>
                                    <td class="text-center"><strong>Item Quantity</strong></td>
                                    <td class="text-right"><strong>Total</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($rows)
                                	foreach ($rows as $index=>$row) { 
                                		$max_quantity = Inventory::getMaxQuantity($row['P_ID']);
                                	?>
                                <tr id="content_<?php echo $index?>">
                                    <td><button type="button" class="btn btn-primary btn-xs" onclick="deleteItem(<?php echo $index?>);"><i class="fa fa-times"></i></button> <?php echo $row['DESCRIPTION']?></td>
                                    <td id="price_<?php echo $index?>" class="text-center" >$<?php echo $row['PRICE']?></td>
                                    <td class="text-center"><button type="button" onclick="decrease(<?php echo $index?>);" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button> <span id='quantity_<?php echo $index?>'><?php echo $row['QUANTITY']?></span> <button type="button" class="btn btn-success btn-xs" onclick="increase(<?php echo $index.",".$max_quantity?>);"><i class="fa fa-plus"></i></button></td>
                                    <td class="text-right"><div id='total_<?php echo $index?>'>$<?php echo $row['TOTAL']?></div></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="highrow"></td>
                                    <td class="highrow"></td>
                                    <td class="highrow text-center"><strong>Total</strong></td>
                                    <td class="highrow text-right"><div name="total" id="total">$<?php echo $total?></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-default">Submit</button>
    <input type=button onClick="location.href='all_orders.php'" class="btn btn-default" value='Cancel'>
    
    
    </form>
                         
                
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<script>
function deleteItem(index)
{
 var tr = document.getElementById("content_"+index);
 tr.style.display = "none";
 var total_var = document.getElementById("total").innerHTML;
 total_var = total_var.replace('$',''); 
 var total_line = document.getElementById("total_"+index).innerHTML;
 total_line = total_line.replace('$',''); 
 document.getElementById("total").innerHTML = "$"+(total_var - total_line);
}

function increase(index, max_quantity)
{
 	if(document.getElementById("quantity_"+index).innerHTML >= max_quantity) return;
	document.getElementById("quantity_"+index).innerHTML++; // Increase the quantity
	var total_line = document.getElementById("total_"+index).innerHTML;
	total_line = total_line.replace('$','');
	// Increase the total line
	var price = document.getElementById("price_"+index).innerHTML;
	price = price.replace('$','');
	document.getElementById("total_"+index).innerHTML = "$" + (price*1+total_line*1);
	// Increase the total
	var total_var = document.getElementById("total").innerHTML;
	total_var = total_var.replace('$','');
	document.getElementById("total").innerHTML = "$"+(total_var*1 + price*1);
}

function decrease(index)
{
	if(document.getElementById("quantity_"+index).innerHTML == 0) return;
	document.getElementById("quantity_"+index).innerHTML--; // Decrease the quantity
	var total_line = document.getElementById("total_"+index).innerHTML;
	total_line = total_line.replace('$','');
	// Decrease the total line
	var price = document.getElementById("price_"+index).innerHTML;
	price = price.replace('$','');
	document.getElementById("total_"+index).innerHTML = "$"+(total_line - price);
	// Decrease the total
	var total_var = document.getElementById("total").innerHTML;
	total_var = total_var.replace('$','');
	document.getElementById("total").innerHTML = "$"+(total_var - price);
}
</script>
    
<?php 
include_once 'parts/bottom.php';
include_once 'parts/footer.php';

?>
