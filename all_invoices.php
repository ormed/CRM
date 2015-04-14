<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Invoice.php';
include_once 'database/Customer.php';
include_once 'database/Balance.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	Invoice::deleteInvoice($_POST['invoice_id']);
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
                        <h1 class="page-header">Invoice</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
       
       <?php 
       		$db = new Database();
	      	$invoices_headers = Invoice::getInvoicesHeaders($db);

			foreach ($invoices_headers as $invoice) {
       			$invoice_rows = Invoice::getInvoiceRows($invoice['INVOICE_ID'], $db);
             	$cust = Customer::getCustomerById($invoice['CUST_ID'], $db);
             	$invoice_date = Invoice::getInvoiceDate($invoice['INVOICE_ID'], $db);
             	$total = Invoice::getTotal($invoice['INVOICE_ID'], $db);
       ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div>
                <h3><strong><u>Invoice <?php echo $invoice['INVOICE_ID']?>:</strong></u></h3>
            </div>
            <div>
            	<form role="form" id="edit-order-form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
	            	<input type="button" class="btn btn-info" value="Edit" onClick='parent.location="invoice.php?invoice_id=<?php echo $invoice['INVOICE_ID']?>"'/>
	            	<input type="hidden" name="invoice_id" value="<?php echo $invoice['INVOICE_ID'] ?>"/>
	            	<input type="submit" class="btn btn-danger" value="Delete" onClick=""/>
	            </form>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-3 col-lg-3 pull-left">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Customer Details</div>
                        <div class="panel-body">
                        	<strong>Name:</strong> <?php echo $cust[0]['FIRST_NAME']." ".$cust[0]['LAST_NAME']?><br>
                            <strong>Customer Id:</strong> <?php echo $invoice['CUST_ID']?></br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Order Details</div>
                        <div class="panel-body">
                            <strong>Date:</strong> <?php echo $invoice_date[0]['ORDER_DATE']?></br>
                            <strong>Order:</strong> #<?php echo $invoice['ORDER_ID']?>
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
                                    <td><strong>Item Description</strong></td>
                                    <td class="text-center"><strong>Item Price</strong></td>
                                    <td class="text-center"><strong>Item Quantity</strong></td>
                                    <td class="text-right"><strong>Total</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($invoice_rows)
                                	foreach ($invoice_rows as $row) { ?>
                                <tr>
                                    <td><?php echo $row['DESCRIPTION']?></td>
                                    <td class="text-center">$<?php echo $row['PRICE']?></td>
                                    <td class="text-center"><?php echo $row['QUANTITY']?></td>
                                    <td class="text-right">$<?php echo $row['TOTAL']?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="highrow"></td>
                                    <td class="highrow"></td>
                                    <td class="highrow text-center"><strong>Total</strong></td>
                                    <td class="highrow text-right">$<?php echo $total?></td>
                                </tr>
                            </tbody>
                          </table>
                   		</div>
           	   		 </div>
           		  </div>
       	 		</div>
    		</div>
		</div>
	</div>
</div>
<?php }?>
                
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
