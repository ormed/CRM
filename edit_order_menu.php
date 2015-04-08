<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Order.php';
include_once 'database/Products.php';
include_once 'database/Inventory.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$order_id = substr($_POST['order_id'], 0, strpos($_POST['order_id'], ','));
	header("Location: edit_order.php?order_id=".$order_id);
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
						<h1 class="page-header">Edit Order Menu</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
                    
				
				<div class="panel panel-default">
                        <div class="panel-heading">
                            Choose Order
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                    	
                                    	<?php 
			                            	$orders_headers = Order::getOrdersHeader();
			                            ?>
                                    	
                                        <div class="form-group">
                                            <i class="fa fa-info-circle"></i> <label>Order Id</label>
                                            <select id="option_order" name="order_id" onchange="showDetails();" class="form-control" style="width:200px">
				                            <?php foreach ($orders_headers as $index=>$order) { 
				                            		if(strcmp($order['STATUS'], "Open") == 0) {
				                            ?>
				                            <option value='<?php echo($order['ORDER_ID'].",".$index)?>'><?php echo($order['ORDER_ID'])?></option>
				                            <?php 	} } ?>
				                            </select>
				                            
				                             <?php foreach ($orders_headers as $order_index=>$order) { 
				                            		if(strcmp($order['STATUS'], "Open") == 0) {
				                            			$rows = Order::getOrderRows($order['ORDER_ID']);
				                            			$total = Order::getTotal($order['ORDER_ID'])[0]['TOTAL'];
				                            ?>
				                            <div id="summary_<?php echo $order_index ?>" class="row" style="display: none;">
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
										                            <tbody>
										                                <?php if($rows)
										                                	foreach ($rows as $index=>$row) { 
										                                		$max_quantity = Inventory::getMaxQuantity($row['P_ID']);
										                                	?>
										                                <tr>
										                                    <td> 
										                                    	<?php echo $row['DESCRIPTION']?>
										                                    </td>
										                                    <td id="price_<?php echo $index?>" class="text-center" >$<?php echo $row['PRICE']?></td>
										                                    <td class="text-center">
										                                    	 <span><?php echo $row['QUANTITY']?></span> 
										                                   	</td>
										                                    <td class="text-right">
										                                    	<div>$<?php echo $row['TOTAL']?></div> 
										                                    </td>
										                                </tr>
										                                <?php } ?>
										                                <tr>
										                                    <td class="highrow"></td>
										                                    <td class="highrow"></td>
										                                    <td class="highrow text-center"><strong>Total</strong></td>
										                                    <td class="highrow text-right">
										                                    	<div>$<?php echo $total?></div>
										                                    </td>
										                                </tr>
										                            </tbody>
										                        </table>
										                    </div>
										                </div>
										            </div>
										        </div>
										    </div>
				                            
				                            
				                           <?php } }?>
				                            
                                        </div>
                                        
                                        <button type="submit" class="btn btn-success">Submit</button>
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
	function showDetails() 
	{
		for(var i=0; i<3; i++) {
			document.getElementById("summary_"+i).style.display='none';
		}
		var x = document.getElementById("option_order").value;
		var order_id = x.split(",")[0];
    	var index = x.split(",")[1];
	    document.getElementById("summary_"+index).style.visibility='visible';
	}
	</script>

    
<?php include_once 'parts/bottom.php';?>

</body>

</html>
