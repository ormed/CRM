<?php 
include_once 'database/Order.php';
include_once 'database/Customer.php';
include_once 'database/Inventory.php';
include_once 'database/Invoice.php';

include_once 'connection/checkUser.php';
include_once 'parts/header.php';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 	$result = Invoice::editInvoice();
}

if (!isset($_GET['invoice_id'])) {
	header("Location: all_invoices.php");
}

$db = new Database();
$invoice_id = $_GET['invoice_id'];
$invoice = Invoice::getInvoiceHeader($invoice_id);
$rows = Invoice::getInvoiceRows($invoice_id, $db);
$cust = Customer::getCustomerById($invoice[0]['CUST_ID'], $db);
$invoice_date = Invoice::getInvoiceDate($invoice_id, $db);	
$total = Invoice::getTotal($invoice_id, $db);
if (!$invoice) {
	header("Location: all_invoices.php");
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
                        <h1 class="page-header">Edit Invoice</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                
  	<div class="panel panel-default">
    	<div class="panel-heading">
           <h3><strong>Invoice <?php echo $invoice_id?>:</strong></h3>
    	</div>
    	
    	<form role="form" id="edit-order-form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
    		<input type="hidden" name='invoice_id' value='<?php echo $invoice_id?>'>
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
                           				<strong>Date:</strong> <?php echo $invoice_date[0]['ORDER_DATE']?>
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
                    <h3 class="text-center"><strong>Invoice Summary</strong></h3>
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
                                    <td>
                                    	<button type="button" class="btn btn-primary btn-xs" onclick="deleteItem(<?php echo $index?>);"><i class="fa fa-times"></i></button> 
                                    	<?php echo $row['DESCRIPTION']?>
                                    	<input type="hidden" id='hidden_p_id_<?php echo $index?>' name='p_id_<?php echo $index?>' value='<?php echo $row['P_ID']?>'>
                                    </td>
                                    <td id="price_<?php echo $index?>" class="text-center" >
                                    	$<?php echo $row['PRICE']?>
                                    </td>
                                    <td class="text-center">
                                    	<button type="button" onclick="decrease(<?php echo $index?>);" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                    	 <span id='quantity_<?php echo $index?>'><?php echo $row['QUANTITY']?></span> 
                                    	 <button type="button" class="btn btn-success btn-xs" onclick="increase(<?php echo $index.",".$max_quantity?>);"><i class="fa fa-plus"></i></button>
                                    	 <input type="hidden" id='hidden_quantity_<?php echo $index?>' name='quantity_<?php echo $index?>' value='<?php echo $row['QUANTITY']?>'>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <input type=button onClick="location.href='all_invoices.php'" class="btn btn-primary" value='Cancel'>
    <input type=button onClick="location.href='search_invoice.php'" class="btn btn-primary" value='Search'>
    
    
    </form>
                         
                
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
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
	// Make quantity = 0
	document.getElementById("hidden_quantity_"+index).value = 0;
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
	var new_order_total = total_var - price;
	document.getElementById("total").innerHTML = "$" + new_order_total;
	document.getElementById("hidden_order_total").value = new_order_total; // set hidden order_total input so it can be accessed from POST afterwards
}
</script>
    
<?php 

$int_date = 31012015;
$string_date = ''.$int_date;
$day = substr($string_date, 0, 2);
$month = substr($string_date, 2, 4);
$year = substr($string_date, 4, 8);

include_once 'parts/bottom.php';
include_once 'parts/footer.php';

?>
