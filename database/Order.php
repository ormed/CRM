<?php
@session_start();

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
    
    public static function getOrderHeader($order_id) {
    	$db = new Database();
    	$q = "Select * from orders_header where order_id = '{$order_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getOrderRows($order_id) {
    	$db = new Database();
    	$q = "Select p.description, p.price, r.quantity, (P.PRICE*R.QUANTITY) as Total from orders_rows r, products p where r.p_id = p.p_id and r.order_id = '{$order_id}'";
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
