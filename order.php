<?php 
include_once 'connection/checkUser.php';
include_once 'database/Order.php';
include_once 'help_functions.php';


$err = '';

//Check if post back
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//list($year, $month, $day) = preg_split('[\-]', $_POST['order-date'], -1, PREG_SPLIT_NO_EMPTY);
	//$order_date = $day."/".$month."/".$year;
	$err = Order::testOrderForm();
} 

//if (($_SERVER["REQUEST_METHOD"] == "POST") && (empty($err))) {
	
//}
//else {

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
                            Order form
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
                                        		$q = 'Select * from customers';
                                        		$db = new Database();
                                        		$results = $db->createQuery($q);
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
                                        		$q = 'Select * from products';
                                        		$db = new Database();
                                        		$results = $db->createQuery($q);
                                        		?>
                                        		
                                        		<div>
                                        			<i class="fa fa-cube"></i> <label>Item #1</label>
                                        		</div>
                                        		<div>
                                        			Description: <select name="desc1" class="form-control" style="width:200px">
                                                				<?php foreach ($results as $result) { ?>
                                                				<option><?php echo($result["DESCRIPTION"]);?></option>
                                                				<?php } ?>
                                           					 </select>
                                        		</div>
                                        		<div>
                                        			Quantity: <input name="quantity1" class="form-control" style="width:200px" placeholder="Quantity" maxlength="2" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        		</div>
                                        		
                                        		<div id="more-items"></div>
                                        		<div id="no-more-error"></div>
                                        		
                                        		<div>
                                        		<script type="text/javascript">
													var i = 2;
												</script>
                                        			Add Items:
                                        			<button type="button" id= "plus-items" class="btn btn-success btn-xs" onclick="addItemToDiv(i++)">+</button>
                                        		</div>
                                        		
                                        </div>
                                        <div class="form-group">
                                            <i class="fa fa-unlock-alt"></i> <label>Status</label>
                                            <select class="form-control" style="width:200px">
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
function addItemToDiv(i) {
	if(i > 5) {
		document.getElementById("no-more-error").innerHTML = "<div class='alert alert-danger'>Can't add more than 5 items in one order!</div></div>";
		document.getElementById("plus-items").className = "btn btn-danger btn-xs";
	} else {
		document.getElementById("more-items").innerHTML += "<div><i class='fa fa-cube'></i> <label>Item #"+i+"</label></div><div>Description:<select name='desc"+i+"' class='form-control' style='width:200px'><?php foreach ($results as $result) { ?><option><?php echo($result["DESCRIPTION"]);?></option><?php }?></select></div><div>Quantity:<input name='quantity"+i+"' class='form-control' style='width:200px' placeholder='Quantity' maxlength='2' onkeypress='return event.charCode >= 48 && event.charCode <= 57'></div>";
	}
}
</script>
<!-- <script type="text/javascript" src="order.js"></script> -->
<?php 
include_once 'parts/bottom.php';
include_once 'parts/footer.php'; 
//} 
?>