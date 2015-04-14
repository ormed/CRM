<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Products.php';
include_once 'database/Invoice.php';
include_once 'database/Balance.php';

class Order {

    /**
     * function to check if form was submitted ok
     * return errors if found any
     */
    public static function testOrderForm() {
        $err = '';
        if ((empty($_POST['order-date'])) || (empty($_POST['customer'])) || ($_POST['order-date'] == null)) {
            $err = "Please fill in all the fields";
        } else {
        	$i = 1;
        	while(isset($_POST['desc'.$i])) {
        		if(empty($_POST['quantity'.$i])) {
        			$err = "Please fill in all the fields";
        			return $err;
        		}
        		$i++;
        	}
        	//all variables has been set
        }
        return $err;
    } 
    
    /**
     * Insert new order 
     */
    public static function insertNewOrder() {
    	$db = new Database();

    	$total_price = 0;
    	$headerResult = Order::insertHeader($db);
    	if($headerResult) { // Added new header
    		$order_id = Order::getLastAdded($db)[0]['LAST'];
    		$i = 1;
    		debug($_POST);
    		while(isset($_POST['desc'.$i])) { // Insert new rows to the new header
    			$price = explode(",",$_POST['desc'.$i])[1];
    			$_POST['desc'.$i] = explode(",",$_POST['desc'.$i])[0];
    			$p_id = Products::getProductId($_POST['desc'.$i]);
     			$rowResult = Order::insertRow($i, $order_id, $p_id, $_POST['quantity'.$i], $db);
     			if(strcmp($_POST['status'], 'Close') == 0) { // Add to Balance if Closed order
     				Balance::insertBalanceWithParameters($p_id, $_SESSION['id'], $_POST['quantity'.$i], $price,'Credit', $db);
     				Products::reduceQuantity($p_id, $_POST['quantity'.$i], $db);
     			}
    			$i++;
    		}
    		if(strcmp($_POST['status'], 'Close') == 0) { // Create Invoice if Close
    			Invoice::insertInvoice($order_id, $db);
    		}
    	} else { // Error in adding new header
    		return FALSE;
    	}
    }
    
    /**
     * Create new order header
     */
    public static function insertHeader($db) {
    	$q = "insert into orders_header(ORDER_DATE, CUST_ID, STATUS) values (to_date(:corder_date, 'dd/mm/yyyy'), :ccust_id, :cstatus)";
        $stid = $db->parseQuery($q);
        // Get the right date format to insert
        $formatDate = date("d/m/Y", strtotime($_POST['order-date']));
        oci_bind_by_name($stid, ':corder_date', $formatDate);
        // Get the cust id only
        $cust_id = substr($_POST['customer'], 0, strpos($_POST['customer'], ' '));
        oci_bind_by_name($stid, ':ccust_id', $cust_id);
        oci_bind_by_name($stid, ':cstatus', $_POST['status']);
        $r = oci_execute($stid);  // executes and commits
        return $r;
    }
    
    /**
     * Close the order
     * Make status -> 'Close'
     */
    public static function closeOrder($order_id, $db) {
    	$q = "update orders_header set status= 'Close' where order_id= :corder_id";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':corder_id', $order_id);
    	oci_execute($stid);  // executes and commits
    }
    
    /**
     * Create new  order row
     * @param $index - the row num
     */
    public static function insertRow($index, $order_id, $p_id, $quantity, $db) {
    	$q = "insert into orders_rows(ORDER_ID, ROW_NUM, P_ID, QUANTITY) values (:corder_id, :crow_num, :cp_id, :cquantity)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':corder_id', $order_id);
    	oci_bind_by_name($stid, ':crow_num', $index);
    	oci_bind_by_name($stid, ':cp_id', $p_id);
    	oci_bind_by_name($stid, ':cquantity', $quantity);
    	$r = oci_execute($stid);  // executes and commits
    	return $r;
    }
    
    /**
     * Get the last record added 
     */
    public static function getLastAdded($db) {
    	$q = "select max(order_id) as last from orders_header";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Update an order 
     */
    public static function editOrder() {
    	$db = new Database();
    	// Update Header
    	$q = "update orders_header set status= :cstatus where order_id= :corder_id";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cstatus', $_POST['status']);
    	oci_bind_by_name($stid, ':corder_id', $_POST['order_id']);
    	oci_execute($stid);  // executes and commits
    	// Update Rows
    	$i = 0;
    	while(isset($_POST['quantity_'.$i])) {
    		$q = "select max(row_num) as last from orders_rows r, products p where (r.p_id = p.p_id and r.order_id = '{$_POST['order_id']}')";
    		$last_row = $db->createQuery($q)[0]['LAST'];

    		$q = "select * from orders_rows r, products p where (r.p_id = p.p_id and r.order_id = '{$_POST['order_id']}' and r.p_id='{$_POST['p_id_'.$i]}')";
    		$product_row = $db->createQuery($q);
    		
    		if(count($product_row) > 0) { // Exist product
    			$row_num = $product_row[0]['ROW_NUM'];
    			if($_POST['quantity_'.$i] == 0) { // Delete row
    				$q = "delete from orders_rows where (order_id = :corder_id and row_num = :crow_num)";
    				$stid = $db->parseQuery($q);
    				oci_bind_by_name($stid, ':corder_id', $_POST['order_id']);
    				oci_bind_by_name($stid, ':crow_num', $row_num);
    				oci_execute($stid); // delete row
    			} else { // Update quantity
    				$q = "update orders_rows set quantity = :cquantity where (row_num = :crow_num and order_id = :corder_id)";
    				$stid = $db->parseQuery($q);
    				oci_bind_by_name($stid, ':cquantity', $_POST['quantity_'.$i]);
    				oci_bind_by_name($stid, ':crow_num', $row_num);
    				oci_bind_by_name($stid, ':corder_id', $_POST['order_id']);
    				oci_execute($stid);  // executes and commits
    			}
    		} else { // Doesn't exist - create row
    			$q = "insert into orders_rows(ORDER_ID, ROW_NUM, P_ID, QUANTITY) values (:corder_id, :crow_num, :cp_id, :cquantity)";
    			$stid = $db->parseQuery($q);
    			oci_bind_by_name($stid, ':corder_id', $_POST['order_id']);
    			$new_row = $last_row + 1;
    			oci_bind_by_name($stid, ':crow_num', $new_row);
    			oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
    			oci_bind_by_name($stid, ':cquantity', $_POST['quantity_'.$i]);
    			oci_execute($stid);  // executes and commits
    		}
    		$i++;
    	}	
    }
    
    /**
     * Delete an order by its id
     * @param int $order_id
     */
    public static function deleteOrder($order_id) {
    	$db = new Database();
    	// Delete all rows
    	$q = "delete from orders_rows where (order_id = :corder_id)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':corder_id', $order_id);
    	oci_execute($stid); // delete rows
    	
    	// Delete the header
    	$q = "delete from orders_header where (order_id = :corder_id)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':corder_id', $order_id);
    	oci_execute($stid); // delete header
    }
    
    /**
     * Find the order header details by its id
     * @param int $order_id
     * @return an array of the order header if found or FALSE otherwise
     */
    public static function getOrderHeader($order_id, $db) {
    	$q = "select * from orders_header where order_id='{$order_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    /**
     * Find the order rows by id
     * @param int $order_id
     * @return array of order rows if found or FALSE otherwise
     */
    public static function getOrderRows($order_id, $db) {
    	$q = "select p.p_id, p.description, p.price, r.quantity, (P.PRICE*R.QUANTITY) as Total from orders_rows r, products p where r.p_id = p.p_id and r.order_id = '{$order_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    /**
     * Get the order details
     * @param int $order_id
     * @param int $cust_id
     * @param Date $start_date
     * @param Date $end_date
     * @param String $first_name
     * @param String $last_name
     * @return array of orders
     */
    public static function getOrdersDetails($order_id, $cust_id, $start_date, $end_date, $first_name, $last_name) {
    	$db = new Database();
    	
    	$customers = Customer::getCustomersDetails($cust_id, $first_name, $last_name, $db);

    	if(count($customers) > 0) {
    		$cust_ids = "";
    		foreach ($customers as $index=>$customer) {
    			$cust_ids .= ($customer['CUST_ID'].',');
    		}
    		$cust_ids[strlen($cust_ids)-1] = "";
    	} else {
    		$cust_ids = "NULL";
    	}
    	
    	// Get the right date format to insert
    	$start = date("d/m/Y", strtotime($start_date));
    	$end = date("d/m/Y", strtotime($end_date));
    	 
    	$q = "select i.order_id, to_char(i.order_date, 'DD/MM/YYYY') as order_date, i.cust_id, i.status, c.first_name, c.last_name from orders_header i, customers c where i.cust_id=c.cust_id and i.order_id='{$order_id}'
	    	UNION
	    	select i.order_id, to_char(i.order_date, 'DD/MM/YYYY') as order_date, i.cust_id, i.status, c.first_name, c.last_name from orders_header i, customers c where i.cust_id=c.cust_id and i.cust_id='{$cust_id}'
	    	UNION
	    	select i.order_id, to_char(i.order_date, 'DD/MM/YYYY') as order_date, i.cust_id, i.status, c.first_name, c.last_name from orders_header i, customers c where i.cust_id=c.cust_id and i.order_date between to_date('{$start}', 'dd/mm/yyyy') and to_date('{$end}', 'dd/mm/yyyy')
	    	UNION
	    	select i.order_id, to_char(i.order_date, 'DD/MM/YYYY') as order_date, i.cust_id, i.status, c.first_name, c.last_name from orders_header i, customers c where  i.cust_id=c.cust_id and i.cust_id IN ({$cust_ids})";
    	$results = $db->createQuery($q);
    	return $results;
    }
    
    /**
     * Find total price of an order by its id
     * @param int $order_id
     * @return the total price of the order
     */
    public static function getTotal($order_id, $db) {
    	$q = "select sum(TOTAL) as total from (Select p.description, p.price, r.quantity, (P.PRICE*R.QUANTITY) as Total from orders_rows r, products p where r.p_id = p.p_id and r.order_id = '{$order_id}')";
    	$total = $db->createQuery($q);
    	if(count($total) == 0) {
    		$total[0]['TOTAL'] = 0;
    	}
    	return $total;
    }
    
    /**
     * Find order date by id
     * @param int $order_id
     * @return String of the order's date
     */
    public static function getOrderDate($order_id, $db) {
    	$q = "select TO_CHAR(ORDER_DATE, 'DD/MM/YYYY') AS ORDER_DATE from ORDERS_HEADER where order_id = '{$order_id}'";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Find all orders headers
     * @return array of headers
     */
    public static function getOrdersHeader($db) {
    	$q = "select * from orders_header order by order_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Find all Open orders headers
     * @return array of headers
     */
    public static function getOpenOrdersHeader($db) {
    	$q = "select * from orders_header where status='Open' order by order_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
}
