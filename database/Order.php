<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Products.php';

class Order {

    /*
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
    
    /*
     * Insert new order 
     */
    public static function insertNewOrder() {
    	$headerResult = Order::insertHeader();
    	if($headerResult) { // Added new header
    		$order_id = Order::getLastAdded()[0]['LAST'];
    		debug($order_id);
    		$i = 1;
    		while(isset($_POST['desc'.$i])) { // Insert new rows to the new header
     			$rowResult = Order::insertRow($i, $order_id);
    			$i++;
    		}
    	} else { // Error in adding new header
    		return FALSE;
    	}

    }
    
    /*
     * Create new order header
     */
    public static function insertHeader() {
    	$db = new Database();
    	$q = "insert into orders_header(ORDER_DATE, CUST_ID, STATUS) values (to_date(:corder_date, 'dd/mm/yyyy'), :ccust_id, :cstatus)";
        $stid = $db->parseQuery($q);
        // Get the right date format to insert
        $formatDate = date("d/m/Y", strtotime($_POST['order-date']));
        oci_bind_by_name($stid, ':corder_date', $formatDate);
        debug($formatDate);
        // Get the cust id only
        $cust_id = substr($_POST['customer'], 0, strpos($_POST['customer'], ' '));
        oci_bind_by_name($stid, ':ccust_id', $cust_id);
        oci_bind_by_name($stid, ':cstatus', $_POST['status']);
        $r = oci_execute($stid);  // executes and commits
        return $r;
    }
    
    /*
     * Create new  order row
     * @param $index - the row num
     */
    public static function insertRow($index, $order_id) {
    	$db = new Database();
    	$q = "insert into orders_rows(ORDER_ID, ROW_NUM, P_ID, QUANTITY) values (:corder_id, :crow_num, :cp_id, :cquantity)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':corder_id', $order_id);
    	oci_bind_by_name($stid, ':crow_num', $index);
    	$p_id = Products::getProductId($_POST['desc'.$index]);
    	oci_bind_by_name($stid, ':cp_id', $p_id);
    	oci_bind_by_name($stid, ':cquantity', $_POST['quantity'.$index]);
    	$r = oci_execute($stid);  // executes and commits
    	return $r;
    }
    
    /*
     * Get the last record added 
     */
    public static function getLastAdded() {
    	$db = new Database();
    	$q = "select max(order_id) as last from orders_header";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getOrderHeader($order_id) {
    	$db = new Database();
    	$q = "select * from orders_header where order_id = '{$order_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getOrderRows($order_id) {
    	$db = new Database();
    	$q = "select p.p_id, p.description, p.price, r.quantity, (P.PRICE*R.QUANTITY) as Total from orders_rows r, products p where r.p_id = p.p_id and r.order_id = '{$order_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getTotal($order_id) {
    	$db = new Database();
    	$q = "select sum(TOTAL) as total from (Select p.description, p.price, r.quantity, (P.PRICE*R.QUANTITY) as Total from orders_rows r, products p where r.p_id = p.p_id and r.order_id = '{$order_id}')";
    	$total = $db->createQuery($q);
    	if(count($total) == 0) {
    		$total[0]['TOTAL'] = 0;
    	}
    	return $total;
    }
    
    public static function getOrderDate($order_id) {
    	$db = new Database();
    	$q = "select TO_CHAR(ORDER_DATE, 'DD/MM/YYYY') AS ORDER_DATE from ORDERS_HEADER where order_id = '{$order_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getOrdersHeader() {
    	$db = new Database();
    	$q = "select * from orders_header order by order_id";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
}
