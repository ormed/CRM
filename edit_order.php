<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Order.php';
include_once 'database/Customer.php';
include_once 'database/Inventory.php';
include_once 'database/Products.php';
include_once 'database/Invoice.php';
include_once 'database/Balance.php';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$result = Order::editOrder();
	if(strcmp($_POST['status'], 'Close') == 0) {
		debug($_POST);
		Invoice::insertInvoice($_POST['order_id']);
		Inventory::reduceQuantity();
		Balance::insertBalance();
		$url = "invoice.php?order_id=".$_POST['order_id']; // Create Invoice
	} else {
		$url = "all_orders.php"; // Return to all orders
	}
	header("Location: ".$url);
}

if (!isset($_GET['order_id'])) {
	header("Location: all_orders.php");
} else {
	$order_id = $_GET['order_id'];
	$order = Order::getOrderHeader($order_id);
	$rows = Order::getOrderRows($order_id);
	$cust = Customer::getCustomerById($order[0]['CUST_ID']);
	$order_date = Order::getOrderDate($order_id);	
	$total = Order::getTotal($order_id)[0]['TOTAL'];
	$status = $order[0]['STATUS'];
	if (!$order) {
		header("Location: all_orders.php");
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
                
  	<div class="panel panel-default">
    	<div class="panel-heading">
           <h3><strong>Order <?php echo $order_id?>:</strong></h3>
    	</div>
    	
    	<form role="form" id="edit-order-form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
    		<input type="hidden" name='order_id' value='<?php echo $order_id?>'>
    		<input type="hidden" name='order_date' value='<?php echo $order_date[0]['ORDER_DATE']?>'>
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
  											
  											<?php if(strcmp($status, "Open") == 0) { ?>
  												<select name="status" class="form-control" style="width: 90px">
                               						<option selected>Open</option>
                               						<option>Close</option>
                               					</select>
                               				<?php } else { echo ($order_date[0]['ORDER_DATE']); }?>
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
                    <h3 class="text-center"><strong>Order Summary</strong></h3>
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
                            <tbody id="data_table">
                                <?php if($rows)
                                	foreach ($rows as $index=>$row) { 
                                		$max_quantity = Inventory::getMaxQuantity($row['P_ID']);
                                	?>
                                <tr id="content_<?php echo $index?>">
                                    <td>
                                    	<?php if(strcmp($status, "Open") == 0) { ?>
                                    	<button type="button" class="btn btn-primary btn-xs" onclick="deleteItem(<?php echo $index?>);"><i class="fa fa-times"></i></button> 
                                    	<?php } ?>
                                    	<?php echo $row['DESCRIPTION']?>
                                    	<input type="hidden" id='hidden_p_id_<?php echo $index?>' name='p_id_<?php echo $index?>' value='<?php echo $row['P_ID']?>'>
                                    </td>
                                    <td id="price_<?php echo $index?>" class="text-center" >$<?php echo $row['PRICE']?></td>
                                    <td class="text-center">
                                    	 <?php if(strcmp($status, "Open") == 0) { ?>
                                    	 <button type="button" onclick="decrease(<?php echo $index?>);" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                    	 <?php } ?>
                                    	 <span id='quantity_<?php echo $index?>'><?php echo $row['QUANTITY']?></span> 
                                    	 <?php if(strcmp($status, "Open") == 0) { ?>
                                    	 <button type="button" class="btn btn-success btn-xs" onclick="increase(<?php echo $index.",".$max_quantity?>);"><i class="fa fa-plus"></i></button>
                                    	 <?php } ?>
                                    	 <input type="hidden" id='hidden_quantity_<?php echo $index?>' name='quantity_<?php echo $index?>' value='<?php echo $row['QUANTITY'];?>'>
                                   	</td>
                                    <td class="text-right">
                                    	<div id='total_<?php echo $index?>'>$<?php echo $row['TOTAL']?></div> 
                                    	<input type="hidden" id='hidden_total_<?php echo $index?>' name='total_<?php echo $index?>' value='<?php echo $row['TOTAL']?>'>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="highrow"></td>
                                    <td class="highrow"></td>
                                    <td class="highrow text-center"><strong>Total</strong></td>
                                    <td class="highrow text-right">
                                    	<div name="total" id="total">$<?php echo $total?></div>
                                    	<input type="hidden" id='hidden_order_total' name='order_total' value='<?php echo $total?>'>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if(strcmp($status, 'Open') == 0) { ?>
                        <div> <!-- Add new product to order -->
                        	<?php 
                        		$results = Products::getAllProducts();
                            ?>
                            <button type="button" class="btn btn-success btn-xs" onclick="addProduct();"><i class="fa fa-plus"></i> Add Product </button>
                            <select id="new_product_desc" name="new_product" class="form-control" style="width:200px">
                            <?php foreach ($results as $result) {
                            ?>
                            <option><?php echo($result["P_ID"].". ".$result["DESCRIPTION"]." $".$result["PRICE"]." (".Products::getProductMaxQuantity($result["DESCRIPTION"]).")");?></option>
                            <?php } ?>
                            </select>
                            <input id="new_product_quantity" class="form-control" style="width:200px" placeholder="Quantity" maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                            <div class="alert alert-danger" id="danger_div" style="visibility: hidden">
                            	<span id="err"></span>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if(strcmp($status, 'Open') == 0) { ?>
    <button type="submit" class="btn btn-primary">Submit</button>
    <input type=button onClick="location.href='all_orders.php'" class="btn btn-primary" value='Cancel'>
    <?php } ?>
    <input type=button onClick="location.href='search_order.php'" class="btn btn-primary" value='Search'>
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
	var new_total = total_var - total_line;
	// Decrease the total
	document.getElementById("total").innerHTML = "$" + new_total;
	// Decrease the hidden total
	document.getElementById("hidden_order_total").value = new_total;
	// Decrease the hidden quantity
	document.getElementById("hidden_quantity_"+index).value = 0;
	document.getElementById("hidden_total_"+index).value = 0;
}

function addProduct() 
{
	var quantity = document.getElementById("new_product_quantity");
	if(quantity.value.localeCompare("") == 0) {
		document.getElementById("err").innerHTML = "<i class='fa fa-info-circle'></i> Must add quantity...";
		document.getElementById("danger_div").style.visibility = "visible";
		return;
	}
	document.getElementById("danger_div").style.visibility = "hidden"; // Clear errors

	var new_product = document.getElementById("new_product_desc");
	var max_quantity = ((new_product.value.split("$")[1]).split("(")[1]).split(")")[0];
	
	if(parseInt(max_quantity) < quantity.value) {
		document.getElementById("err").innerHTML = "<i class='fa fa-info-circle'></i> Maximum is " + max_quantity + "!";
		document.getElementById("danger_div").style.visibility = "visible";
		return;
	}
	
	var table = document.getElementById("data_table");
	var table_rows = table.getElementsByTagName("tr").length;
	var index = table_rows-1;
    var row = table.insertRow(index);
    row.id = "content_" + index;
    // set new description
    var new_desc = row.insertCell(0);
    // set new price
    var new_price = row.insertCell(1);
    new_price.id = "price_"+index;
    new_price.className="text-center";
    // set new quantity
    var new_quantity = row.insertCell(2);
    new_quantity.className="text-center";
    // set new total 
    var new_total = row.insertCell(3);
    new_total.className="text-right";

    // get new product id
    var new_product_id = parseInt((new_product.value.split("$")[0]).split(" ")[0]);
    
    // Put the new product details in the html
    var new_product = document.getElementById("new_product_desc");
    new_desc.innerHTML = "<button type='button' class='btn btn-primary btn-xs' onclick='deleteItem(" + index +");'><i class='fa fa-times'></i></button> " + (new_product.value.split("$")[0]).split(" ")[1];
    new_desc.innerHTML += "<input type='hidden' id='hidden_p_id_" + index + "' name='p_id_" + index + "' value='" + new_product_id + "'>";
    new_price.innerHTML = "$"+(new_product.value.split("$")[1]).split("(",1);
    new_quantity.innerHTML = "<button type='button' onclick='decrease("+ index +");' class='btn btn-danger btn-xs'><i class='fa fa-minus'></i></button> "+"<span id='quantity_" + index + "'>" + quantity.value + "</span>"+" <button type='button' class='btn btn-success btn-xs' onclick='increase(" + index + "," + max_quantity + ");'><i class='fa fa-plus'></i></button>"+"<input type='hidden' id='hidden_quantity_" + index + "' name='quantity_" + index + "' value='" + quantity.value + "'>";
    new_total.innerHTML = "<div id='total_"+index+"'>$"+(quantity.value * (new_product.value.split("$")[1]).split("(",1))+"</div>";
    new_total.innerHTML += "<input type='hidden' id='hidden_total_"+index+"' name='total_"+index+"' value='"+(quantity.value * (new_product.value.split("$")[1]).split("(",1))+"'>";

    // Set the total of the order
    var total_var = document.getElementById("total").innerHTML;
	total_var = total_var.replace('$','');
	var new_order_total = total_var*1 + (quantity.value * (new_product.value.split("$")[1]).split("(",1))*1;
	document.getElementById("total").innerHTML = "$" + new_order_total;
	document.getElementById("hidden_order_total").value = new_order_total; // set hidden input so it can be accessed from POST
}

function increase(index, max_quantity)
{
 	if(document.getElementById("quantity_"+index).innerHTML >= max_quantity) return;

 	// Increase the quantity
	document.getElementById("quantity_"+index).innerHTML++; 
	var total_line = document.getElementById("total_"+index).innerHTML;
	total_line = total_line.replace('$','');
	document.getElementById("hidden_quantity_"+index).value++; 

	// Increase the total line
	var price = document.getElementById("price_"+index).innerHTML;
	price = price.replace('$','');
	var new_row_total = price*1+total_line*1;
	document.getElementById("total_"+index).innerHTML = "$" + new_row_total;
	document.getElementById("hidden_total_"+index).value = new_row_total; // set hidden input so it can be accessed from POST

	// Increase the total
	var total_var = document.getElementById("total").innerHTML;
	total_var = total_var.replace('$','');
	var new_order_total = total_var*1 + price*1;
	document.getElementById("total").innerHTML = "$" + new_order_total;
	document.getElementById("hidden_order_total").value = new_order_total; // set hidden input so it can be accessed from POST
}

function decrease(index)
{
	if(document.getElementById("quantity_"+index).innerHTML == 0) return;

	// Decrease the quantity
	document.getElementById("quantity_"+index).innerHTML--; 
	var total_line = document.getElementById("total_"+index).innerHTML;
	total_line = total_line.replace('$','');
	document.getElementById("hidden_quantity_"+index).value--; 

	// Decrease the total line
	var price = document.getElementById("price_"+index).innerHTML;
	price = price.replace('$','');
	var new_row_total = total_line - price;
	document.getElementById("total_"+index).innerHTML = "$" + new_row_total;
	document.getElementById("hidden_total_"+index).value = new_row_total; // set hidden total_row input so it can be accessed from POST afterwards
	
	// Decrease the total
	var total_var = document.getElementById("total").innerHTML;
	total_var = total_var.replace('$','');
	var new_order_total = (total_var - price);
	document.getElementById("total").innerHTML = "$" + new_order_total;
	document.getElementById("hidden_order_total").value = new_order_total; // set hidden order_total input so it can be accessed from POST afterwards
}
</script>
    
<?php 
include_once 'parts/bottom.php';
include_once 'parts/footer.php';
}
?>
