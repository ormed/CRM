<?php 
include_once 'connection/checkUser.php';
include_once 'parts/header.php';
include_once 'database/Order.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include_once 'parts/body_header.php';
	
	$db = new Database();
	$results = Order::getOrdersDetails($_POST['order_id'], $_POST['cust_id'], $_POST['start_date'], $_POST['end_date'], $_POST['first_name'], $_POST['last_name']);
	?>
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
                                        <div class="form-group">
                                            <i class="fa fa-info-circle"></i> <label>Order Id</label>
                                            <select id="option_order" name="order_id" onchange="showDetails(<?php echo count($results);?>);" class="form-control" style="width:200px">
				                            <?php foreach ($results as $index=>$order) { ?>
				                            <option value='<?php echo($order['ORDER_ID'].",".$index)?>'><?php echo($order['ORDER_ID'])?></option>
				                            <?php }?>
				                            </select>
				                           
				                            
				                            <?php foreach ($results as $order_index=>$order) { 
				                            			$rows = Order::getOrderRows($order['ORDER_ID'], $db);
				                            			$total = Order::getTotal($order['ORDER_ID'], $db)[0]['TOTAL'];
				                            ?>
				                            </br>
				                            <?php if($order_index == 0) { ?>
				                            <div id="summary_<?php echo $order_index ?>" class="row" style="display: block;">
				                            <?php } else { ?>
				                            <div id="summary_<?php echo $order_index ?>" class="row" style="display: none;">
				                            <?php } ?>
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
				                            
				                            
				                           <?php }?>
				                         <button type="submit" class="btn btn-success">Edit</button>
				                         <button type="reset" class="btn btn-primary">Back</button>   
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
    <script>
	function showDetails(total) 
	{
		var x = document.getElementById("option_order").value;
		var order_id = x.split(",")[0];
    	var index = x.split(",")[1];
		for(var i = 0; i < total; i++) 
		{
			if(i != index)
			{
				document.getElementById("summary_"+i).style.display='none';
			}
		}
    	document.getElementById("summary_"+index).style.display='block';
	}
	</script>
	<?php 
	
	include_once 'parts/body_footer.php';
} else {
?>

<body>

	<div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
		<div id="page-wrapper">

			<div class="container-fluid">
			
			
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Search Orders</h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				</br>
				<!-- /.row -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Search
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        	<form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
	                        	<div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Order Id</label>
	                            	<input class="form-control" name="order_id" style="width:200px" maxlength="10" onkeypress='return event.charCode >= 46 && event.charCode <= 57'>
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Customer Id</label>
	                            	<input class="form-control" name="cust_id" style="width:200px">
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Customer First Name</label>
	                            	<input class="form-control" name="first_name" style="width:200px">
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Customer Last Name</label>
	                            	<input class="form-control" name="last_name" style="width:200px">
	                            </div>
	                             <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>Start Date</label>
	                            	<input class="form-control" name="start_date" style="width:200px" type="date" value=<?php echo date("Y-m-d")?>/>
	                            </div>
	                            <div class="form-group">
	                            	<i class="fa fa-caret-right"></i> <label>End Date</label>
	                            	<input class="form-control" name="end_date" style="width:200px" type="date" value=<?php echo date("Y-m-d")?>/>
	                            </div>
	                            
	                            <button type="submit" class="btn btn-success">Search</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
	                     	</form>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->


			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

    
<?php 
}
include_once 'parts/bottom.php';
?>

</body>

</html>
