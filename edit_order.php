<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Order.php';
include_once 'database/Customer.php';

if (isset($_GET['order_id'])) {
	$order_id = $_GET['order_id'];
	$order = Order::getOrderHeader($order_id);
	$rows = Order::getOrderRows($order_id);
	$cust = Customer::getCustomer($order[0]['CUST_ID']);
	$order_date = Order::getOrderDate($order_id);
	$total = Order::getTotal($order_id)[0]['TOTAL'];
	
	if (!$order) {
		header("Location: all_orders.php");
	}
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
                        <h1 class="page-header">Edit Order <?php echo $order_id?></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                
                <div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div>
                <h3><strong><u>Order <?php echo $order_id?>:</strong></u></h3>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-3 col-lg-3 pull-left">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Customer Details</div>
                        <div class="panel-body">
                            <strong>Customer Id:</strong> <?php echo $cust[0]['CUST_ID']?><br>
                            <strong>Name:</strong> <?php echo $cust[0]['FIRST_NAME']." ".$cust[0]['LAST_NAME']?><br>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="panel panel-default height">
                        <div class="panel-heading">Order Details</div>
                        <div class="panel-body">
                            <strong>Status:</strong> <?php echo $order[0]['STATUS']?><br>
                            <strong>Date:</strong> <?php echo $order_date[0]['ORDER_DATE']?>
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
                                	foreach ($rows as $row) { ?>
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
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
